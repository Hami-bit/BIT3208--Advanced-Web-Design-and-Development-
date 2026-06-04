-- Week 1 Database Export - NexaBank
-- BIT3208 Advanced Web Design and Development
-- This is the Week 1 initial database setup

-- Create the Week 1 database
CREATE DATABASE IF NOT EXISTS week1db;
USE week1db;

-- Initial test table to verify database works
CREATE TABLE IF NOT EXISTS test_table (
    id INT PRIMARY KEY AUTO_INCREMENT,
    message VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Test insert
INSERT INTO test_table (message) VALUES ('Week 1 database is working!');
