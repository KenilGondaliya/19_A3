CREATE DATABASE shopping_cart;
USE shopping_cart;

CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    pname VARCHAR(150) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    picture VARCHAR(255),
    FOREIGN KEY (category_id) REFERENCES categories(id)
    ON DELETE CASCADE
);

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    roll_no VARCHAR(50),
    name VARCHAR(100),
    email VARCHAR(150),
    course VARCHAR(100),
    age INT
);

INSERT INTO categories(name) VALUES
('Garments'),
('Mobiles'),
('Laptops');

-- Password is: admin123
INSERT INTO admins(username,password)
VALUES (
    'admin',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa5Q3j3xv4zY6zqZzQJfQ0XQ3Oe'
);