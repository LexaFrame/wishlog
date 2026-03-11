-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 04, 2026 at 02:11 PM
-- Server version: 8.4.3
-- PHP Version: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wishlog_dev`
--

CREATE DATABASE IF NOT EXISTS wishlog_dev
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_0900_ai_ci;
USE wishlog_dev;

-- --------------------------------------------------------

--
-- Table structure for table `wluser_wlwishlist`
--

CREATE TABLE `wluser_wlwishlist` (
  `user_id` int NOT NULL,
  `wishlist_id` int NOT NULL,
  `role_in_wishlist` varchar(50) NOT NULL,
  `date_joined_wishlist` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wlwishlist_wlproduct`
--

CREATE TABLE `wlwishlist_wlproduct` (
  `wishlist_id` int NOT NULL,
  `product_id` int NOT NULL,
  `category_id` int DEFAULT NULL,
  `product_quantity` int NOT NULL DEFAULT '1',
  `buy_decision` BOOLEAN NOT NULL DEFAULT FALSE,
  `purchase_cancelled` BOOLEAN NOT NULL DEFAULT FALSE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wl_category`
--

CREATE TABLE `wl_category` (
  `category_id` int NOT NULL,
  `category_name` varchar(50) NOT NULL,
  `created_at_category` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `wishlist_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wl_product`
--

CREATE TABLE `wl_product` (
  `product_id` int NOT NULL,
  `product_url` varchar(255) DEFAULT NULL,
  `shop_name` varchar(50) DEFAULT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_image_url` varchar(255) DEFAULT NULL,
  `product_description` text,
  `product_price` decimal(10,2) NOT NULL,
  `product_origin` varchar(50) NOT NULL,
  `created_at_product` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wl_role`
--

CREATE TABLE `wl_role` (
  `role_id` int NOT NULL,
  `role_name` varchar(50) NOT NULL,
  `role_description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wl_user`
--

CREATE TABLE `wl_user` (
  `user_id` int NOT NULL,
  `user_name` varchar(50) NOT NULL,
  `user_firstname` varchar(50) DEFAULT NULL,
  `user_lastname` varchar(50) DEFAULT NULL,
  `user_email` varchar(255) NOT NULL,
  `user_password_hash` varchar(255) NOT NULL,
  `user_birthdate` date DEFAULT NULL,
  `user_address` varchar(255) DEFAULT NULL,
  `user_postalcode` varchar(10) DEFAULT NULL,
  `user_city` varchar(50) DEFAULT NULL,
  `user_country` varchar(50) DEFAULT NULL,
  `created_at_user_account` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_login_at` DATETIME NULL,
  `role_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wl_wishlist`
--

CREATE TABLE `wl_wishlist` (
  `wishlist_id` int NOT NULL,
  `wishlist_name` varchar(50) NOT NULL,
  `event_type` varchar(50) DEFAULT NULL,
  `event_date` date DEFAULT NULL,
  `created_at_wishlist` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `hide_purchases` BOOLEAN NOT NULL DEFAULT FALSE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `wluser_wlwishlist`
--
ALTER TABLE `wluser_wlwishlist`
  ADD PRIMARY KEY (`user_id`,`wishlist_id`),
  ADD KEY `wishlist_id` (`wishlist_id`);

--
-- Indexes for table `wlwishlist_wlproduct`
--
ALTER TABLE `wlwishlist_wlproduct`
  ADD PRIMARY KEY (`wishlist_id`,`product_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `wl_category`
--
ALTER TABLE `wl_category`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `wishlist_id` (`wishlist_id`,`category_name`);

--
-- Indexes for table `wl_product`
--
ALTER TABLE `wl_product`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `wl_role`
--
ALTER TABLE `wl_role`
  ADD PRIMARY KEY (`role_id`),
  ADD UNIQUE KEY `role_name` (`role_name`);

--
-- Indexes for table `wl_user`
--
ALTER TABLE `wl_user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `user_name` (`user_name`),
  ADD UNIQUE KEY `user_email` (`user_email`),
  ADD KEY `role_id` (`role_id`);

--
-- Indexes for table `wl_wishlist`
--
ALTER TABLE `wl_wishlist`
  ADD PRIMARY KEY (`wishlist_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `wl_category`
--
ALTER TABLE `wl_category`
  MODIFY `category_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wl_product`
--
ALTER TABLE `wl_product`
  MODIFY `product_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wl_role`
--
ALTER TABLE `wl_role`
  MODIFY `role_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wl_user`
--
ALTER TABLE `wl_user`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wl_wishlist`
--
ALTER TABLE `wl_wishlist`
  MODIFY `wishlist_id` int NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `wluser_wlwishlist`
--
ALTER TABLE `wluser_wlwishlist`
  ADD CONSTRAINT `wluser_wlwishlist_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `wl_user` (`user_id`),
  ADD CONSTRAINT `wluser_wlwishlist_ibfk_2` FOREIGN KEY (`wishlist_id`) REFERENCES `wl_wishlist` (`wishlist_id`);

--
-- Constraints for table `wlwishlist_wlproduct`
--
ALTER TABLE `wlwishlist_wlproduct`
  ADD CONSTRAINT `wlwishlist_wlproduct_ibfk_1` FOREIGN KEY (`wishlist_id`) REFERENCES `wl_wishlist` (`wishlist_id`),
  ADD CONSTRAINT `wlwishlist_wlproduct_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `wl_product` (`product_id`),
  ADD CONSTRAINT `wlwishlist_wlproduct_ibfk_3` FOREIGN KEY (`category_id`) REFERENCES `wl_category` (`category_id`);

--
-- Constraints for table `wl_category`
--
ALTER TABLE `wl_category`
  ADD CONSTRAINT `wl_category_ibfk_1` FOREIGN KEY (`wishlist_id`) REFERENCES `wl_wishlist` (`wishlist_id`);

--
-- Constraints for table `wl_user`
--
ALTER TABLE `wl_user`
  ADD CONSTRAINT `wl_user_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `wl_role` (`role_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
