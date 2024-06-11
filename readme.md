run the following query to initialize db in mysql

CREATE DATABASE itsecwb;  -- Create the database named "myproject"

USE itsecwb;  -- Use the newly created database

CREATE TABLE users (
  user_id INT AUTO_INCREMENT PRIMARY KEY,  -- Unique identifier for each user (auto-incrementing)
  full_name VARCHAR(255) NOT NULL,  -- User's full name
  email VARCHAR(255) NOT NULL UNIQUE,  -- User's email address (unique to prevent duplicates)
  password VARCHAR(255) NOT NULL,  -- User's password (store securely using a hashing algorithm)
  profile_image VARCHAR(255) DEFAULT NULL,  -- URL or path to the user's profile image (optional)
  CONSTRAINT password_length CHECK (LENGTH(password) >= 8)  -- Enforce password length (at least 8 characters)
);

CREATE TABLE failed_logins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    failed_attempts INT DEFAULT 0,
    last_attempt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    locked_until TIMESTAMP NULL
);


Then, change port number sa xampp nung mysql if kaya to port 3307 para wala na babaguhin for connect.php.
