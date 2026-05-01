DROP DATABASE IF EXISTS enrollment_db;
CREATE DATABASE IF NOT EXISTS enrollment_db;
USE enrollment_db;
CREATE TABLE IF NOT EXISTS users (
    id BIGINT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    passwordhash VARCHAR(255) NOT NULL,
    role int default 3, -- administrator 0, clerk 1, faculty 2, student 3
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
INSERT INTO users (first_name, last_name, email, passwordhash, role) 
values ('Enrollment', 'Administrator', 'administrator@enrollment-mail.com', '$2y$10$pnecftsJk/Nr4r/ayxiw1.7XHrU6NqGOCjqMaUB.sPc8z449yjzqC', 0);
