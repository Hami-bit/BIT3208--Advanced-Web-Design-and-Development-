-- Week7 database for NexaBank users (mirrors Week5/Week6 users schema)
CREATE DATABASE IF NOT EXISTS `week7db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `week7db`;

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

-- Sample demo users (password is bcrypt hash of 'password')
INSERT INTO users (first_name, last_name, email, phone, account_type, username, password, account_number, balance)
VALUES
('Dana','Mwangi','dana@example.com','+254700000005','savings','dana','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','NXB-0005-2024',12000.00),
('Eve','Kariuki','eve@example.com','+254700000006','current','eve','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','NXB-0006-2024',5300.00);
