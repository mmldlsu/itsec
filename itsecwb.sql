-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 13, 2024 at 07:37 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `itsecwb`
--

-- --------------------------------------------------------

--
-- Table structure for table `failed_logins`
--

CREATE TABLE `failed_logins` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `failed_attempts` int(11) DEFAULT 0,
  `last_attempt` timestamp NOT NULL DEFAULT current_timestamp(),
  `locked_until` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profile_image` varchar(255) DEFAULT NULL
) ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `first_name`, `last_name`, `email`, `password`, `profile_image`) VALUES
(1, 'Admin', 'Administrator', 'admin@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$VWgzenlKYTJleHZ0STd4Yw$5EritJiqU7r7MQGvc5dJIZHPCGVrYfQr1zDSbaLOfPc', 'uploads/666b2cd4eae8d0.12591289.png'),
(2, 'Willem', 'Dafoe', 'dafoe@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$TnVUa3JQOUxMdGJ0M0JaRw$K6BCzr1GZ9DhARdFaBVQUwleITSKw8FCBN2sT6tMHuU', 'uploads/666b2e0e53aa30.23195735.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `failed_logins`
--
ALTER TABLE `failed_logins`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email` (`email`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `failed_logins`
--
ALTER TABLE `failed_logins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `failed_logins`
--
ALTER TABLE `failed_logins`
  ADD CONSTRAINT `failed_logins_ibfk_1` FOREIGN KEY (`email`) REFERENCES `users` (`email`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- Create posts Table

CREATE TABLE posts (
    post_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    content TEXT NOT NULL,
    numeric_input1 INT NOT NULL,
    numeric_input2 INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

-- Inserting dummy data into the posts table

INSERT INTO posts (user_id, content, numeric_input1, numeric_input2) VALUES
(1, 'This is a post by Admin.', 10, 20),
(1, 'Admin posts another interesting content.', 30, 40),
(2, 'Willem Dafoe shares his thoughts.', 50, 60),
(2, 'Another post by Willem Dafoe.', 70, 80);