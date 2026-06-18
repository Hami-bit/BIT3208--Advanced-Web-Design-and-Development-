-- Week6 database for NexaBank users CRUD (mirrors Week5 users schema)
CREATE DATABASE IF NOT EXISTS `week6db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `week6db`;

CREATE TABLE IF NOT EXISTS `users` (
    id             INT PRIMARY KEY AUTO_INCREMENT,
    first_name     VARCHAR(100)   NOT NULL,
    last_name      VARCHAR(100)   NOT NULL,
    email          VARCHAR(150)   NOT NULL UNIQUE,
    phone          VARCHAR(20),
    account_type   ENUM('savings','current','fixed') DEFAULT 'savings',
    username       VARCHAR(100)   NOT NULL UNIQUE,
    password       VARCHAR(255)   NOT NULL,
    account_number VARCHAR(30)    NOT NULL UNIQUE,
    balance        DECIMAL(15,2)  DEFAULT 0.00,
    status         ENUM('active','suspended','closed') DEFAULT 'active',
    created_at     TIMESTAMP      DEFAULT CURRENT_TIMESTAMP,
    updated_at     TIMESTAMP      DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Sample demo users (passwords are bcrypt hash of 'password')
INSERT INTO users (first_name, last_name, email, phone, account_type, username, password, account_number, balance)
VALUES
('Alice','Wanjiru','alice@example.com','+254700000003','savings','alice','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','NXB-0003-2024',22000.00),
('Bob','Milton','bob.smith@example.com','+254700000002','savings','bob','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','NXB-0002-2024',45200.00),
('Charlie','Ng','charlie.ng@example.com','+254700000004','current','charlie','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','NXB-0004-2024',15000.00);
