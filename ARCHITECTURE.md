# BrickLogic System Architecture

## System Overview

BrickLogic uses a **three-tier web application architecture**:

```
Frontend Layer (HTML, CSS, JavaScript)
         ↓
Business Logic Layer (PHP)
         ↓
Database Layer (MySQL)
```

## Technology Stack

| Component | Technology | Purpose |
|-----------|-----------|---------|
| **Server** | Apache (XAMPP) | Web server hosting |
| **Backend** | PHP | Server-side business logic |
| **Frontend** | HTML5, CSS3, JavaScript, jQuery | User interface |
| **Database** | MySQL | Data persistence |
| **Email** | PHPMailer | Email notifications |

## Project Structure

```
bricklogic/
│
├── Core Pages
│   ├── index.php              # Landing page
│   ├── header.php             # Navigation header (included in all pages)
│   └── footer.php             # Footer (included in all pages)
│
├── User Authentication
│   ├── register.php           # User registration
│   ├── signup.php             # Alternative signup form
│   ├── login.php              # User login
│   ├── logout.php             # Logout functionality
│   ├── change_password.php    # Change existing password
│   ├── forget_password.php    # Password recovery request
│   └── reset_password.php     # Reset password via token
│
├── User Dashboard & Features
│   ├── dashbord.php           # Main user dashboard
│   ├── wishlist.php           # Wishlist functionality
│   └── subscribe.php          # Subscription management
│
├── Design & Planning
│   ├── design.php             # Floor plan designer interface
│   └── save_design.php        # Save design proposals (AJAX)
│
├── Budget & Pricing
│   ├── budget.php             # Budget calculation tool
│   └── pricing.php            # Pricing information page
│
├── Shopping & Ordering
│   ├── selectmaterial.php     # Material catalog & search
│   ├── material_details.php   # Detailed material information
│   ├── cart.php               # Shopping cart management
│   ├── checkout.php           # Checkout process
│   └── process_payment.php    # Payment processing
│
├── Orders & Ratings
│   ├── view_orders.php        # View user's orders
│   ├── track_order.php        # Track order status
│   ├── submit_rating.php      # Submit order/supplier rating (AJAX)
│   └── supplier_rating.php    # View supplier ratings
│
├── Admin & Analytics
│   ├── admin.php              # Admin dashboard
│   ├── edit_materials.php     # Manage materials (add/edit/delete)
│   └── material_analysis.php  # Material trends & analytics
│
├── Database & Configuration
│   ├── db_connect.php         # MySQL connection configuration
│   ├── web.sql                # Database schema
│   └── .env.example           # Environment configuration template
│
└── Assets
    ├── design/                # SVG elements for floor planner
    ├── images/                # Static images
    ├── Uploads/               # User-uploaded material images
    └── PHPMailer/             # Email library
```

## Core Modules Explained

### 1. Authentication Module
**Files:** register.php, login.php, forget_password.php, change_password.php

- User registration with validation
- Login with session management
- Password recovery via email
- Password change functionality
- Session timeout enforcement

### 2. Design Module
**Files:** design.php, save_design.php

- Interactive SVG-based floor plan editor
- Drag-and-drop furniture and elements
- Design proposal saving (via AJAX)
- Real-time preview

### 3. Budget Calculator Module
**Files:** budget.php

- Cost estimation based on parameters
- Material cost aggregation
- Generates budget breakdown
- Exportable estimates

### 4. E-Commerce Module
**Files:** selectmaterial.php, material_details.php, cart.php, checkout.php

- Material catalog browsing
- Advanced search and filtering
- Shopping cart management
- Secure checkout process
- Order confirmation

### 5. Order Management Module
**Files:** view_orders.php, track_order.php, submit_rating.php

- View order history
- Real-time order tracking
- Order status updates
- Rating system for suppliers

### 6. Admin Module
**Files:** admin.php, edit_materials.php, material_analysis.php

- User management
- Material management (CRUD)
- Analytics and reporting
- Platform administration

## Database Schema

### Key Tables
- **users** – Customer and admin accounts
- **materials** – Construction materials catalog
- **orders** – Customer orders
- **order_items** – Items in each order
- **designs** – Saved floor plan designs
- **ratings** – Supplier and order ratings
- **cart** – Shopping cart items

See `web.sql` for complete database schema and relationships.

## Data Flow

### User Registration & Login
```
1. User fills registration form (register.php)
2. Input validation on server (db_connect.php)
3. Password hashing and storage
4. Session creation
5. Redirect to dashboard
```

### Material Purchase Flow
```
1. Browse materials (selectmaterial.php)
2. View details (material_details.php)
3. Add to cart (cart.php)
4. Review cart items
5. Proceed to checkout (checkout.php)
6. Process payment (process_payment.php)
7. Create order in database
8. Send confirmation email (PHPMailer)
9. Redirect to order tracking
```

### Design Creation Flow
```
1. User opens designer (design.php)
2. Drag elements onto canvas
3. Modify layout in real-time
4. Save proposal (save_design.php via AJAX)
5. Design stored in database
6. Associate with budget estimate
```

## Security Features Implemented

### Input Validation
- Server-side validation on all forms
- Type checking for numeric inputs
- String sanitization for database queries

### Authentication
- Session-based user authentication
- Password hashing on storage
- Session timeout enforcement
- Logout functionality

### Data Protection
- Prepared statements to prevent SQL injection
- Input escaping for HTML output
- Secure file upload handling (Uploads folder)
- Email verification for password recovery

### Email Security
- PHPMailer for secure email sending
- Configuration in .env file (not hardcoded)
- Verification tokens for password reset

## Performance Considerations

### Database Optimization
- Indexed primary and foreign keys
- Efficient query design
- Pagination for large result sets

### Frontend Optimization
- Minimal JavaScript dependencies
- AJAX for asynchronous operations
- Efficient CSS and image usage

### Caching
- Browser caching for static assets
- Session-based user data caching
- Pagination to reduce database queries

## Deployment

### Development Environment
```
XAMPP
├── Apache Server
├── MySQL Database
└── BrickLogic Application
```

### File Permissions
- Uploads folder must be writable
- Logs folder must be writable (if used)
- PHP must have read access to all files

### Configuration Steps
1. Place project in `htdocs` folder
2. Create MySQL database named `web`
3. Import `web.sql` schema
4. Update `db_connect.php` with credentials
5. Ensure PHP and MySQL are running
6. Access via `http://localhost/bricklogic`

## Future Improvements

- Mobile-responsive design enhancements
- Advanced search filters
- Real-time notifications
- Payment gateway integration
- Improved analytics dashboard
- User profile enhancements
- API for third-party integration

---

**Last Updated:** June 2025
**Version:** 1.0
**Developed by:** Areeba Mujtaba, Zainub Rashid, Eisha Tur Raazia
