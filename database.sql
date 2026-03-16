CREATE DATABASE IF NOT EXISTS kitchen71 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE kitchen71;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  email VARCHAR(190) NOT NULL UNIQUE,
  phone VARCHAR(40) DEFAULT NULL,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('customer', 'admin') NOT NULL DEFAULT 'customer',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO users (name, email, phone, password_hash, role)
VALUES (
  'Kitchen 71 Admin',
  'admin@kitchen71.local',
  '+63 900 000 0000',
  -- password: admin123 (change in production)
  '$2y$10$u5O2l8h9T2vXy6S3iZ0E7On0t6gD9qE1lA9pQZxwR5vD6YvFz9k3K',
  'admin'
);

INSERT INTO users (name, email, phone, password_hash, role)
VALUES (
  'Kitchen 71 User',
  'user@kitchen71.local',
  '+63 900 000 0000',
  -- password: user123 (change in production)
  '$2y$10$.tv/yWoGGABzh32dQ6wvXe98ok.Sn1EmqCq2UkDq9vaYJybebnihu',
  'customer'
);

CREATE TABLE menu_categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  type ENUM('dine-in', 'catering') NOT NULL DEFAULT 'dine-in'
);

CREATE TABLE menu_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  category_id INT NOT NULL,
  name VARCHAR(150) NOT NULL,
  description TEXT,
  price DECIMAL(10,2) NOT NULL DEFAULT 0,
  is_available TINYINT(1) NOT NULL DEFAULT 1,
  FOREIGN KEY (category_id) REFERENCES menu_categories(id) ON DELETE CASCADE
);

CREATE TABLE announcements (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(200) NOT NULL,
  body TEXT NOT NULL,
  category ENUM('event', 'news') NOT NULL DEFAULT 'event',
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE bookings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  full_name VARCHAR(150) NOT NULL,
  email VARCHAR(190) NOT NULL,
  phone VARCHAR(40) NOT NULL,
  event_date DATE NOT NULL,
  event_time TIME NOT NULL,
  guests INT NOT NULL,
  location VARCHAR(255) NOT NULL,
  package_name VARCHAR(150) NOT NULL,
  notes TEXT,
  status ENUM('pending', 'approved', 'denied') NOT NULL DEFAULT 'pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT DEFAULT NULL,
  source ENUM('website', 'foodpanda', 'grabfood', 'page', 'walk-in') NOT NULL DEFAULT 'website',
  summary TEXT NOT NULL,
  payment_status ENUM('pending', 'paid') NOT NULL DEFAULT 'pending',
  status ENUM('received', 'preparing', 'ready', 'completed') NOT NULL DEFAULT 'received',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE payments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT DEFAULT NULL,
  booking_id INT DEFAULT NULL,
  amount DECIMAL(10,2) NOT NULL DEFAULT 0,
  method VARCHAR(50) DEFAULT NULL,
  status ENUM('pending', 'paid', 'cancelled') NOT NULL DEFAULT 'pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE SET NULL,
  FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE SET NULL
);

