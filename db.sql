CREATE DATABASE gng_panel CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE gng_panel;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('user','reseller','admin') DEFAULT 'reseller',
  balance DECIMAL(15,2) DEFAULT 99964999.00,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE licenses (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  game VARCHAR(50) NOT NULL,
  plan VARCHAR(50) DEFAULT 'PREMIUM',
  duration_type ENUM('preset','custom') NOT NULL,
  duration_value VARCHAR(50) NOT NULL,
  max_devices INT DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
