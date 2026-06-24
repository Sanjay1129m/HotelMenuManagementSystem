-- Hotel Menu Management System Database Schema

-- Create database
CREATE DATABASE IF NOT EXISTS hotel_menu_db;
USE hotel_menu_db;

-- Users table (for customers)
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    fullname VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(20) NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Admins table
CREATE TABLE admins (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    fullname VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Hotels table
CREATE TABLE hotels (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    address TEXT NOT NULL,
    phone VARCHAR(20),
    email VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Menu categories
CREATE TABLE categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    hotel_id INT,
    FOREIGN KEY (hotel_id) REFERENCES hotels(id) ON DELETE CASCADE
);

-- Menu items
CREATE TABLE menu_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    category_id INT,
    hotel_id INT,
    image_url VARCHAR(255),
    is_available BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,
    FOREIGN KEY (hotel_id) REFERENCES hotels(id) ON DELETE CASCADE
);

-- Orders table
CREATE TABLE orders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    hotel_id INT,
    total_amount DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'preparing', 'ready', 'delivered', 'cancelled') DEFAULT 'pending',
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (hotel_id) REFERENCES hotels(id) ON DELETE CASCADE
);

-- Order items table
CREATE TABLE order_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT,
    menu_item_id INT,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (menu_item_id) REFERENCES menu_items(id) ON DELETE CASCADE
);

-- Insert sample admin (password: admin123)
INSERT INTO admins (username, password, fullname) 
VALUES ('admin', '$2y$10$YourHashedPasswordHere', 'System Administrator');

-- Insert sample hotel
INSERT INTO hotels (name, address, phone, email) 
VALUES ('Grand Hotel', '123 Main Street, City', '+1234567890', 'info@grandhotel.com');

-- Insert sample categories
INSERT INTO categories (name, hotel_id) VALUES 
('Appetizers', 1),
('Main Course', 1),
('Desserts', 1),
('Beverages', 1);

-- Insert sample menu items
INSERT INTO menu_items (name, description, price, category_id, hotel_id) VALUES 
('Caesar Salad', 'Fresh romaine lettuce with Caesar dressing', 8.99, 1, 1),
('Garlic Bread', 'Toasted bread with garlic butter', 4.99, 1, 1),
('Grilled Salmon', 'Atlantic salmon with lemon butter sauce', 22.99, 2, 1),
('Beef Steak', '8oz ribeye steak with vegetables', 24.99, 2, 1),
('Chocolate Cake', 'Rich chocolate cake with ganache', 7.99, 3, 1),
('Ice Cream Sundae', 'Vanilla ice cream with toppings', 6.99, 3, 1),
('Orange Juice', 'Freshly squeezed orange juice', 3.99, 4, 1),
('Coffee', 'Freshly brewed coffee', 2.99, 4, 1);