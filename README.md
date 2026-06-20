# BrickLogic

A comprehensive web platform for construction planning and material procurement. Design floor layouts, estimate project budgets, browse and order construction materials from verified suppliers, and track orders in real-time.

## 🎯 Features

- **Interactive Floor Plan Designer** – Create custom floor layouts with drag-and-drop furniture and building elements
- **Budget Calculator** – Estimate construction costs with accurate material and labor pricing
- **Material Catalog & Ordering** – Browse materials, add to cart, and purchase from suppliers
- **Supplier Ratings & Reviews** – Rate and review suppliers based on product quality and service
- **Order Tracking** – Track order status and delivery information
- **User Dashboard** – Manage orders, designs, and account settings
- **Wishlist** – Save favorite materials for future reference
- **Admin Panel** – Manage users, materials, and platform analytics
- **Material Analytics** – View material trends and demand analysis

## 🛠️ Tech Stack

**Frontend:**
- HTML5, CSS3
- JavaScript, jQuery
- SVG-based design editor

**Backend:**
- PHP (OOP architecture)
- AJAX for seamless interactions
- Session management & security

**Database:**
- MySQL with web.sql schema

**Libraries & Tools:**
- PHPMailer (email notifications)

## 📋 System Requirements

- Apache Server (via XAMPP)
- PHP 7.0+
- MySQL 5.0+
- Modern web browser (Chrome, Firefox, Safari)
- 256MB RAM minimum

## 🚀 Installation

1. **Clone the repository:**
   ```bash
   git clone https://github.com/laylfighter/bricklogic.git
   cd bricklogic
   ```

2. **Set up XAMPP:**
   - Place the project in `C:\Xampp\htdocs\bricklogic\`
   - Start Apache and MySQL from XAMPP Control Panel

3. **Database Setup:**
   - Open phpMyAdmin (http://localhost/phpmyadmin)
   - Create a new database named `web`
   - Import `web.sql` from the project root
   ```bash
   mysql -u root web < web.sql
   ```

4. **Configure Database Connection:**
   - Update database credentials in `db_connect.php` if needed
   - Default: localhost, root user, no password

5. **Access the Application:**
   ```
   http://localhost/bricklogic
   ```

## 📁 Project Structure

```
bricklogic/
├── design/                  # SVG assets for floor plan elements
├── images/                  # Image assets
├── Uploads/                 # Material images and user uploads
├── PHPMailer/               # Email library for notifications
│
├── Core Files
├── index.php                # Home page
├── header.php               # Navigation header
├── footer.php               # Page footer
├── db_connect.php           # Database connection
│
├── User Features
├── dashbord.php             # User dashboard
├── register.php             # User registration
├── signup.php               # Alternative signup
├── login.php                # User login
├── logout.php               # Logout functionality
├── change_password.php      # Password change
├── forget_password.php      # Password recovery
├── reset_password.php       # Reset password via email
│
├── Design & Planning
├── design.php               # Floor plan designer
├── save_design.php          # Save design data
├── budget.php               # Budget calculator
├── pricing.php              # Pricing information
│
├── Shopping & Materials
├── selectmaterial.php       # Browse materials
├── material_details.php     # Material information
├── cart.php                 # Shopping cart
├── checkout.php             # Checkout process
├── process_payment.php      # Payment processing
│
├── Orders & Tracking
├── view_orders.php          # View user orders
├── track_order.php          # Track order status
├── submit_rating.php        # Rate orders
├── supplier_rating.php      # View supplier ratings
├── wishlist.php             # Wishlist management
│
├── Analytics & Admin
├── material_analysis.php    # Material analytics
├── edit_materials.php       # Edit material details
├── admin.php                # Admin dashboard
├── subscribe.php            # Subscription management
│
├── Database
├── web.sql                  # Database schema
└── README.md                # This file
```

## 🎮 Usage

### For Users:
1. **Register/Login** – Create an account or sign in
2. **Browse Materials** – View construction materials in the catalog
3. **Design Layouts** – Create custom floor plans using the design editor
4. **Estimate Budget** – Calculate project costs
5. **Add to Cart** – Select materials and add to shopping cart
6. **Checkout** – Complete purchase
7. **Track Orders** – Monitor delivery status
8. **Rate Suppliers** – Leave feedback after order completion
9. **Manage Wishlist** – Save materials for later

### For Admins:
1. **Manage Users** – View and manage user accounts
2. **Manage Materials** – Add, edit, or remove materials
3. **View Analytics** – Track platform usage and material trends
4. **Process Orders** – Monitor and manage orders
5. **User Management** – Handle user registrations and subscriptions

## 🔐 Security Features

- Secure user authentication with session management
- Password hashing for secure storage
- Input validation and sanitization
- SQL injection prevention via prepared statements
- AJAX security for asynchronous operations
- Email verification for account recovery

## 👥 Contributors

This project was developed as a final year university project by:
- Areeba Mujtaba (F22BSCS032)
- Zainub Rashid (F22BSCS003)
- Eisha Tur Raazia (F22BSCS014)

**Institution:** Kinnaird College for Women University, Lahore, Pakistan

## 🚧 Future Enhancements

- Mobile app (Android/iOS)
- Advanced filtering and search capabilities
- Payment gateway integration
- Real-time notifications
- Improved analytics dashboard
- Multi-language support
- Enhanced design editor features
- Supplier inventory management system

## 📝 License

This project is available under the MIT License. See LICENSE file for details.

## 📧 Support

For questions, issues, or contributions, please open an issue on GitHub.

---

**Built for simplifying construction planning and material procurement**
