
DROP DATABASE IF EXISTS hotel_menu_db;
CREATE DATABASE hotel_menu_db;
USE hotel_menu_db;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(20) NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    fullname VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO admins (username, password, fullname)
VALUES (
    'admin',
    '$2y$10$wHf6qKXq3pV1Q7rY3EwQkeTz8zYvG3bF9WvN7mM1Jt4hH8yLkZP2K',
    'System Administrator'
);
