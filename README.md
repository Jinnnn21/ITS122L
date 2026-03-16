# Kitchen 71 – Web-Based Restaurant & Catering System

A **PHP/MySQL web application** for the Kitchen 71 restaurant and catering business, built as part of ITS122L (Web Systems and Technologies 2). The system supports user registration and login, catering bookings, menu browsing, announcements, and a full admin dashboard with CRUD operations.

## Pages included

- `index.php` – Home / landing page
- `menu.php` – Dine-in and catering menu (loaded from database)
- `catering.php` – Catering packages and "Book an Event" form
- `announcements.php` – Events & Promotions, News & Updates
- `login.php`, `register.php` – Authentication pages
- `profile.php` – User profile, "My Bookings", "My Orders"
- `admin.php` – Admin dashboard (stats overview)
- `admin_menu.php` – Manage menu items (Create, Read, Delete)
- `admin_announcements.php` – Manage announcements (Create, Read, Delete)
- `logout.php` – Clears session and redirects to home

Shared styling lives in `assets/css/styles.css`, with a simple script in `assets/js/main.js` for the mobile nav and active links.


## Requirements

- XAMPP (or any Apache + PHP + MySQL stack)
- PHP 7.4 or higher
- MySQL / MariaDB

## How to run

1. Copy the `Kitchen71` folder into `C:\xampp\htdocs\`.
2. Start **Apache** and **MySQL** in the XAMPP Control Panel.
3. Open [phpMyAdmin](http://localhost/phpmyadmin).
4. Go to the **Import** tab and import `database.sql` — this creates the `kitchen71` database, all tables, and the default admin account.
5. Visit `http://localhost/Kitchen71/` in your browser.

## Default admin credentials

| Field    | Value                  |
|----------|------------------------|
| Email    | admin@kitchen71.local  |
| Password | admin123               |

## Test customer credentials

| Field    | Value                  |
|----------|------------------------|
| Email    | user@kitchen71.local  |
| Password | user123               |


## Database

The `database.sql` file sets up the following tables:

| Table             | Description                                      |
|-------------------|--------------------------------------------------|
| `users`           | Registered customers and admins (roles: customer, admin) |
| `menu_categories` | Category groupings for menu items                |
| `menu_items`      | Dine-in and catering dishes with price and availability |
| `announcements`   | Events & Promotions and News & Updates posts     |
| `bookings`        | Catering booking requests submitted by users     |
| `orders`          | Orders from website, Foodpanda, GrabFood, etc.   |
| `payments`        | Payment records linked to orders or bookings     |

## Known limitations

- `order-online.php` and `about.php` are not yet implemented — nav links to these will return a 404.
- The admin menu and announcements pages support Create and Delete, but do not have a pre-filled edit form for Update.
- No CSRF protection on forms (acceptable for academic/local use).
