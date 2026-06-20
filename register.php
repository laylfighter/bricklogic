<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

session_start();
require 'db_connect.php';

$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
error_log('Session: ' . print_r($_SESSION, true));

$action = $_POST['action'] ?? '';
$errors = [];
$success = false;

$gmailAddress = 'eishaturraazia.262@gmail.com';
$gmailAppPassword = 'lalagqcvrpwlklll';

if ($action === 'register') {
    $companyName = trim($_POST['companyName'] ?? '');
    $phoneNumber = trim($_POST['phoneNumber'] ?? '');
    $emailAddress = trim($_POST['emailAddress'] ?? '');
    $companyLocation = trim($_POST['companyLocation'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirmPassword'] ?? '';

    if (empty($companyName) || empty($phoneNumber) || empty($emailAddress) || empty($companyLocation) || empty($password) || empty($confirmPassword)) {
        $errors[] = 'All fields are required';
    }
    if (!filter_var($emailAddress, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email address';
    }
    if (!preg_match('/^\+\d{10,15}$/', $phoneNumber)) {
        $errors[] = 'Invalid phone number (e.g., +1234567890)';
    }
    if (strlen($companyName) > 150 || strlen($emailAddress) > 150 || strlen($companyLocation) > 100 || strlen($phoneNumber) > 20) {
        $errors[] = 'Input lengths exceed table limits';
    }
    if (strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters long';
    }
    if ($password !== $confirmPassword) {
        $errors[] = 'Passwords do not match';
    }

    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
            $stmt->execute(['email' => $emailAddress]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($user) {
                $errors[] = 'Email address already registered';
                error_log("Registration failed: Email $emailAddress already exists");
            } else {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, 'supplier')");
                $stmt->execute([
                    'name' => $companyName,
                    'email' => $emailAddress,
                    'password' => $hashedPassword
                ]);
                $usersId = $pdo->lastInsertId();
                error_log("User inserted: ID=$usersId, email=$emailAddress");

                $otp = rand(1000, 9999);
                $mail = new PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = $gmailAddress;
                    $mail->Password = $gmailAppPassword;
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;
                    $mail->setFrom($gmailAddress, 'BrickLogic');
                    $mail->addAddress($emailAddress);
                    $mail->isHTML(true);
                    $mail->Subject = 'Your OTP for BrickLogic Supplier Registration';
                    $mail->Body = "Dear $companyName,<br><br>Your OTP for supplier registration is: <strong>$otp</strong><br><br>Thank you,<br>BrickLogic Team";
                    $mail->AltBody = "Your OTP for BrickLogic supplier registration is: $otp";
                    $mail->send();
                    error_log("OTP $otp sent to $emailAddress");
                } catch (Exception $e) {
                    $errors[] = 'Failed to send OTP via email: ' . $mail->ErrorInfo;
                    error_log('PHPMailer Error: ' . $mail->ErrorInfo);
                }

                if (empty($errors)) {
                    $_SESSION['supplier_otp'] = $otp;
                    $_SESSION['supplier_data'] = compact('companyName', 'phoneNumber', 'emailAddress', 'companyLocation', 'usersId');
                    $success = true;
                    error_log('Session data set: ' . print_r($_SESSION, true));
                }
            }
        } catch (PDOException $e) {
            $errors[] = 'Database error: ' . $e->getMessage();
            error_log('Database error in registration: ' . $e->getMessage());
        }
    }
    header('Content-Type: application/json');
    echo json_encode(['success' => $success, 'errors' => $errors]);
    exit();
}

if ($action === 'verify_otp') {
    header('Content-Type: application/json');
    $otp = trim($_POST['otp'] ?? '');
    if (!isset($_SESSION['supplier_otp'])) {
        $errors[] = 'Session expired or OTP not generated. Please register again.';
        error_log('Session supplier_otp not set during OTP verification');
    } elseif (empty($otp) || $otp != $_SESSION['supplier_otp']) {
        $errors[] = 'Invalid OTP';
        error_log("Invalid OTP entered: $otp, expected: {$_SESSION['supplier_otp']}");
    } else {
        $supplierData = $_SESSION['supplier_data'] ?? [];
        if (empty($supplierData)) {
            $errors[] = 'No supplier data found';
            error_log('Session supplier_data not set during OTP verification');
        } else {
            try {
                $stmt = $pdo->prepare("INSERT INTO suppliers (users_email, users_id, company_name, phone, location) VALUES (:users_email, :users_id, :company_name, :phone, :location)");
                $stmt->execute([
                    'users_email' => $supplierData['emailAddress'],
                    'users_id' => $supplierData['usersId'],
                    'company_name' => $supplierData['companyName'],
                    'phone' => $supplierData['phoneNumber'],
                    'location' => $supplierData['companyLocation']
                ]);
                error_log('Supplier data inserted into database');
                unset($_SESSION['supplier_otp']);
                unset($_SESSION['supplier_data']);
                echo json_encode(['success' => true]);
                exit();
            } catch (PDOException $e) {
                $errors[] = 'Failed to insert supplier data: ' . $e->getMessage();
                error_log('Database error in OTP verification: ' . $e->getMessage());
            }
        }
    }
    echo json_encode(['success' => false, 'errors' => $errors]);
    exit();
}

if ($action === 'resend_otp') {
    header('Content-Type: application/json');
    $supplierData = $_SESSION['supplier_data'] ?? [];
    if (empty($supplierData)) {
        $errors[] = 'No supplier data found for resend';
        error_log('Session supplier_data not set during OTP resend');
    } else {
        try {
            $otp = rand(1000, 9999);
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = $gmailAddress;
                $mail->Password = $gmailAppPassword;
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;
                $mail->setFrom($gmailAddress, 'BrickLogic');
                $mail->addAddress($supplierData['emailAddress']);
                $mail->isHTML(true);
                $mail->Subject = 'Your New OTP for BrickLogic Supplier Registration';
                $mail->Body = "Dear {$supplierData['companyName']},<br><br>Your new OTP for supplier registration is: <strong>$otp</strong><br><br>Thank you,<br>BrickLogic Team";
                $mail->AltBody = "Your new OTP for BrickLogic supplier registration is: $otp";
                $mail->send();
                error_log("New OTP $otp sent to {$supplierData['emailAddress']}");
                $_SESSION['supplier_otp'] = $otp;
                echo json_encode(['success' => true]);
                exit();
            } catch (Exception $e) {
                $errors[] = 'Failed to resend OTP: ' . $mail->ErrorInfo;
                error_log('PHPMailer Error on resend: ' . $mail->ErrorInfo);
            }
        } catch (Exception $e) {
            $errors[] = 'Error during OTP resend: ' . $e->getMessage();
            error_log('Error during OTP resend: ' . $e->getMessage());
        }
    }
    echo json_encode(['success' => false, 'errors' => $errors]);
    exit();
}

$pdo = null;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supplier Registration - BrickLogic</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <style>
        /* Your existing CSS */
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .modal-content {
            border-radius: 12px;
            max-width: 450px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .modal-header {
            border-bottom: none;
            padding-bottom: 0;
        }

        .modal-title {
            font-size: 1.5rem;
            font-weight: 600;
        }

        .form-control {
            border-radius: 6px;
            border: 1px solid #ced4da;
            padding: 10px;
        }

        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.3);
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
            border-radius: 6px;
            padding: 10px;
            font-weight: 500;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .error-message {
            color: #dc3545;
            font-size: 0.9rem;
            margin-top: 10px;
        }

        .otp-container {
            display: flex;
            gap: 10px;
            justify-content: center;
        }

        .otp-input {
            width: 50px;
            height: 50px;
            text-align: center;
            font-size: 1.2rem;
            border: 1px solid #ced4da;
            border-radius: 6px;
            padding: 0;
        }

        .otp-input:focus {
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.3);
            outline: none;
        }

        .resend-container {
            margin-top: 15px;
            text-align: center;
        }

        .resend-btn {
            color: #007bff;
            background: none;
            border: none;
            font-size: 0.9rem;
            cursor: pointer;
        }

        .resend-btn:disabled {
            color: #6c757d;
            cursor: not-allowed;
        }

        .countdown {
            color: #6c757d;
            font-size: 0.9rem;
        }

        .form-container {
            max-width: 450px;
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .login-link {
            display: block;
            text-align: center;
            margin-top: 15px;
            font-size: 0.9rem;
            color: #007bff;
            text-decoration: none;
        }

        .login-link:hover {
            color: #0056b3;
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="form-container">
        <h2 class="text-center mb-4">Supplier Registration</h2>
        <form id="supplierForm" method="POST" action="">
            <input type="hidden" name="action" value="register">
            <div class="form-group">
                <label for="companyName">Company Name</label>
                <input type="text" class="form-control" id="companyName" name="companyName" required>
            </div>
            <div class="form-group">
                <label for="phoneNumber">Phone Number</label>
                <input type="tel" class="form-control" id="phoneNumber" name="phoneNumber" placeholder="+1234567890" required>
            </div>
            <div class="form-group">
                <label for="emailAddress">Email Address</label>
                <input type="email" class="form-control" id="emailAddress" name="emailAddress" required>
            </div>
            <div class="form-group">
                <label for="companyLocation">Company Location</label>
                <input type="text" class="form-control" id="companyLocation" name="companyLocation" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="confirmPassword">Confirm Password</label>
                <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Submit</button>
        </form>
        <a href="login.php" class="login-link">Already have an account? Login</a>
    </div>

    <div class="modal fade" id="otpModal" tabindex="-1" aria-labelledby="otpModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="otpModalLabel">OTP Verification</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="otpForm" method="POST" action="">
                        <input type="hidden" name="action" value="verify_otp">
                        <div class="form-group">
                            <label>Enter OTP</label>
                            <div class="otp-container">
                                <input type="text" class="otp-input" maxlength="1" required>
                                <input type="text" class="otp-input" maxlength="1" required>
                                <input type="text" class="otp-input" maxlength="1" required>
                                <input type="text" class="otp-input" maxlength="1" required>
                                <input type="hidden" name="otp" id="otpHidden">
                            </div>
                        </div>
                        <div class="resend-container">
                            <button type="button" class="resend-btn" id="resendBtn" disabled>Resend OTP</button>
                            <div class="countdown" id="countdown">Resend in 60s</div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Verify</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
    <script>
        // Supplier Form Submission
        document.getElementById('supplierForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const fields = ['companyName', 'phoneNumber', 'emailAddress', 'companyLocation', 'password', 'confirmPassword'];
            for (let field of fields) {
                if (!document.getElementById(field).value.trim()) {
                    alert('Please fill in all fields.');
                    return;
                }
            }
            const emailAddress = document.getElementById('emailAddress').value.trim();
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(emailAddress)) {
                alert('Please enter a valid email address.');
                return;
            }
            const phoneNumber = document.getElementById('phoneNumber').value.trim();
            const phonePattern = /^\+\d{10,15}$/;
            if (!phoneNumber.match(phonePattern)) {
                alert('Please enter a valid phone number (e.g., +923001234567).');
                return;
            }
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            if (password.length < 8) {
                alert('Password must be at least 8 characters long.');
                return;
            }
            if (password !== confirmPassword) {
                alert('Passwords do not match.');
                return;
            }
            const formData = new FormData(this);
            fetch(window.location.href, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        $('.form-container').hide();
                        $('#otpModal').modal('show');
                    } else {
                        alert('Registration failed: ' + (data.errors || ['Unknown error']).join('\n'));
                    }
                })
                .catch(error => {
                    console.error('Form submission error:', error);
                    alert('An error occurred. Please try again.');
                });
        });

        // OTP Input Handling
        const otpInputs = document.querySelectorAll('.otp-input');
        const otpHidden = document.getElementById('otpHidden');
        otpInputs.forEach((input, index) => {
            input.addEventListener('input', (e) => {
                if (e.target.value.length === 1 && index < otpInputs.length - 1) {
                    otpInputs[index + 1].focus();
                }
                updateHiddenOTP();
            });
            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && !e.target.value && index > 0) {
                    otpInputs[index - 1].focus();
                }
            });
            input.addEventListener('paste', (e) => {
                e.preventDefault();
                const pastedData = e.clipboardData.getData('text').slice(0, 4);
                for (let i = 0; i < pastedData.length && i < otpInputs.length; i++) {
                    otpInputs[i].value = pastedData[i];
                }
                updateHiddenOTP();
                if (pastedData.length === 4) {
                    otpInputs[3].focus();
                }
            });
        });

        function updateHiddenOTP() {
            const otp = Array.from(otpInputs).map(input => input.value).join('');
            otpHidden.value = otp;
        }

        // OTP Form Submission
        document.getElementById('otpForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const otp = otpHidden.value;
            if (!otp || otp.length !== 4 || !/^\d+$/.test(otp)) {
                alert('Please enter a valid 4-digit OTP.');
                return;
            }
            const formData = new FormData(this);
            fetch(window.location.href, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Supplier account created successfully!');
                        $('#otpModal').modal('hide');
                        window.location.href = 'admin.php';
                    } else {
                        alert('OTP verification failed: ' + (data.errors || ['Unknown error']).join('\n'));
                    }
                })
                .catch(error => {
                    console.error('OTP submission error:', error);
                    alert('An error occurred. Please try again.');
                });
        });

        // Resend OTP Countdown
        let countdown = 60;
        const countdownElement = document.getElementById('countdown');
        const resendBtn = document.getElementById('resendBtn');

        function updateCountdown() {
            if (countdown > 0) {
                countdown--;
                countdownElement.textContent = `Resend in ${countdown}s`;
                setTimeout(updateCountdown, 1000);
            } else {
                resendBtn.disabled = false;
                countdownElement.style.display = 'none';
            }
        }

        resendBtn.addEventListener('click', function() {
            fetch(window.location.href, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'action=resend_otp'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('New OTP sent successfully!');
                        countdown = 60;
                        resendBtn.disabled = true;
                        countdownElement.style.display = 'block';
                        countdownElement.textContent = `Resend in ${countdown}s`;
                        updateCountdown();
                    } else {
                        alert('Failed to resend OTP: ' + (data.errors || ['Unknown error']).join('\n'));
                    }
                })
                .catch(error => {
                    console.error('Resend OTP error:', error);
                    alert('An error occurred while resending OTP.');
                });
        });

        $('#otpModal').on('shown.bs.modal', function() {
            updateCountdown();
        });
    </script>
</body>

</html>