
CREATE DATABASE IF NOT EXISTS nexabankdb;
USE nexabankdb;

-- ============================================================
-- TABLE: users (from Week5)
-- (kept minimal reference - full schema available in Week5/database/Week5db.sql)
-- ============================================================
CREATE TABLE IF NOT EXISTS users (
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

-- ============================================================
-- TABLE: transactions (from Week5)
-- ============================================================
CREATE TABLE IF NOT EXISTS transactions (
	id             INT PRIMARY KEY AUTO_INCREMENT,
	user_id        INT            NOT NULL,
	type           ENUM('deposit','withdrawal','transfer') NOT NULL,
	amount         DECIMAL(15,2)  NOT NULL,
	balance_after  DECIMAL(15,2)  NOT NULL,
	description    VARCHAR(255),
	reference_no   VARCHAR(50)    UNIQUE,
	status         ENUM('success','failed','pending') DEFAULT 'success',
	created_at     TIMESTAMP      DEFAULT CURRENT_TIMESTAMP,
	FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
); -- NOTE: ON DELETE CASCADE can be risky. Consider changing to RESTRICT for financial data.

-- ============================================================
-- TABLE: transfers (from Week5)
-- ============================================================
CREATE TABLE IF NOT EXISTS transfers (
	id               INT PRIMARY KEY AUTO_INCREMENT,
	sender_id        INT           NOT NULL,
	receiver_id      INT           NOT NULL,
	amount           DECIMAL(15,2) NOT NULL,
	note             VARCHAR(255),
	status           ENUM('success','failed') DEFAULT 'success',
	created_at       TIMESTAMP     DEFAULT CURRENT_TIMESTAMP,
	FOREIGN KEY (sender_id)   REFERENCES users(id) ON DELETE RESTRICT,
	FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE RESTRICT
);

-- ============================================================
-- TABLE: admins (RBAC for administration panel)
-- Roles: 'super' = Super Admin, 'manager' = Manager Admin, 'normal' = Normal Admin
-- ============================================================
CREATE TABLE IF NOT EXISTS admins (
	id         INT PRIMARY KEY AUTO_INCREMENT,
	username   VARCHAR(100) NOT NULL UNIQUE,
	password   VARCHAR(255) NOT NULL,
	full_name  VARCHAR(150),
	email      VARCHAR(150),
	role       ENUM('super','manager','normal') NOT NULL DEFAULT 'normal',
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Sample admin users (passwords use the same bcrypt placeholder as demo users)
INSERT IGNORE INTO admins (username, password, full_name, email, role) VALUES
('superadmin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Super Administrator', 'super@nexabank.com', 'super'),
('manager1',   '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Manager Admin',      'manager@nexabank.com', 'manager'),
('normaladm',  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Normal Admin',       'normal@nexabank.com', 'normal');

-- Notes: Use Week5/database/Week5db.sql for full sample data for users/transactions.
