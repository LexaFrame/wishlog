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

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `wlwishlist_wlproduct`;
DROP TABLE IF EXISTS `wluser_wlwishlist`;
DROP TABLE IF EXISTS `wl_product`;
DROP TABLE IF EXISTS `wl_category`;
DROP TABLE IF EXISTS `wl_wishlist`;
DROP TABLE IF EXISTS `wl_user`;
DROP TABLE IF EXISTS `wl_role`;

SET FOREIGN_KEY_CHECKS = 1;

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
  `created_at_product` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `category_id` int DEFAULT NULL
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
  `user_postalcode` varchar(20) DEFAULT NULL,
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
  ADD KEY `product_id` (`product_id`);

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
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `category_id` (`category_id`);

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
  ADD CONSTRAINT `wlwishlist_wlproduct_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `wl_product` (`product_id`);

--
-- Constraints for table `wl_category`
--
ALTER TABLE `wl_category`
  ADD CONSTRAINT `wl_category_ibfk_1` FOREIGN KEY (`wishlist_id`) REFERENCES `wl_wishlist` (`wishlist_id`);

--
--
-- Constraints for table `wl_product`
--
ALTER TABLE `wl_product`
  ADD CONSTRAINT `wl_product_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `wl_category` (`category_id`);

--
-- Constraints for table `wl_user`
--
ALTER TABLE `wl_user`
  ADD CONSTRAINT `wl_user_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `wl_role` (`role_id`);

INSERT INTO `wl_role`(`role_name`,`role_description`)
VALUES 
('Administrator', 'The Administrator can upload, edit and delete anything he deems necessary on the website'),
('User', 'The User can register, login, create, customize, update, delete and share a wishlist');

INSERT INTO `wl_user`(`user_name`, `user_firstname`,`user_lastname`,`user_email`, `user_password_hash`, `user_birthdate`, `user_address`, `user_postalcode`, `user_city`, `user_country`,`role_id`)
VALUES ('PAQ', 'Francisca', 'Canals', 'francisca.canals@mail.com', '$2y$12$6.0KJVdrDIs54r.GM8NUPuz2R.ythMf3Qbpt5wDeV/nb7PJc2rvdG', '2001-01-01', '6 calle Jeronimo Estades', '6872Y', 'Soller', 'España', 1);

INSERT INTO `wl_user`(`user_name`, `user_firstname`,`user_lastname`,`user_email`, `user_password_hash`, `user_country`, `role_id`)
VALUES ('Vic', 'Victor', 'Alter', 'victor.alter@mail.com', '$2y$12$KahzF.TLr9OUnGF0fluqKO4EV9uvpoN1pr7NDO1BlcARsZDx/y7IK', 'France', 2);

INSERT INTO `wl_user`(`user_name`, `user_email`, `user_password_hash`, `role_id`)
VALUES ('MCE','maria.nadal@mail.com', '$2y$12$4Hf3DPyyJIIBk781GXwY4eJFuXN4MaXt4c3xDtwyvyrnggjHGcsWe', 2);

INSERT INTO `wl_wishlist`(`wishlist_name`, `event_type`, `event_date`, `hide_purchases`)
VALUES ('Anniversaire', 'birthday','2026-08-01', TRUE);

INSERT INTO `wl_wishlist`(`wishlist_name`, `hide_purchases`)
VALUES ('Ma liste d\'envies', TRUE);

INSERT INTO `wl_wishlist`(`wishlist_name`, `event_type`, `event_date`, `hide_purchases`)
VALUES ('La liste de bébé A.', 'baby','2026-03-02', TRUE);

INSERT INTO `wl_category`(`category_name`, `wishlist_id`)
VALUES 
('Jewelry', 1),
('Books', 2),
('Clothes', 2);

INSERT INTO `wl_product`(`product_url`, `shop_name`, `product_name`, `product_image_url`, `product_description`, `product_price`, `product_origin`, `category_id`)
VALUES 
('https://www.lego.com/fr-fr/product/dinosaur-fossils-tyrannosaurus-rex-76968?CMP=AFC-AffiliateFR-TnL5HPStwNw-2116208-1706080-10', 'Lego', 'LEGO Tyrannosaurus Rex', 'https://www.lego.com/cdn/cs/set/assets/blt490fcaeb0097ae2f/76968_Prod.png?format=webply&fit=bounds&quality=75&width=800&height=800&dpr=1', 'Partagez votre passion pour les dinosaures avec un modèle impressionnant à exposer avec fierté : Les fossiles de dinosaures : le Tyrannosaurus rex (76968). Ce modèle LEGO® Jurassic World contient un fossile de T. rex à l\’échelle 1/12 doté d\’un crâne, de mâchoires, de bras et d\’une queue mobiles. Une plaque descriptive peut être fixée sur le support, et le site de fouilles du modèle inclut des surprises, dont un morceau « d\’ambre » et 2 minifigurines du film Jurassic Park de 1993 : Ellie Sattler et Alan Grant.', 249.99, 'manual', NULL),
('https://www.amazon.fr/Au-coeur-lesprit-critique-manipulations/dp/2416009052/ref=sr_1_1?__mk_fr_FR=%C3%85M%C3%85%C5%BD%C3%95%C3%91&crid=K079NVJUR114&dib=eyJ2IjoiMSJ9.-JR84T4-ig3vqNONb7SJ38_iGpA4FN_m-IIqgvoBAfuejWu7Y1D83W0czENvWSrOq7JXPvNdL93pAAkwTZ5ar0VwsbMWdIBn7myuAVQhHyn16ExnqRrMZC0k6_PPlFxKlVMjanwGnR51XOlUOM326bK0VQqOy2tFPXVbgNPY-m3440-YiHhfzkWVesYV-zG2whoW8D1jDtjl_99jU0sqBA.94E6qaJv1g4__8hhg4Rt2LFLwdAZhXgjfSXaJerii78&dib_tag=se&keywords=au+coeur+de+l%27esprit+critique&qid=1765873127&sprefix=au+coeur+de+l%27esprit+critique%2Caps%2C94&sr=8-1','Amazon','Au coeur de l\'esprit critique','https://m.media-amazon.com/images/I/610das5U2NL._SL1400_.jpg','Comment démêler le vrai du faux, face au flux constant d\'informations auquel nous sommes exposés ? Fake news, théories du complot, désinformation, conseils bien intentionnés du voisin : de fausses croyances s\'immiscent dans nos jugements. Quelles sont les bonnes pratiques pour trier au mieux les informations ? Pourquoi faut-il se méfier de soi-même ? Comment lutter contre les fausses croyances ? Qu\'est-ce que l`\'esprit critique et quelle est son utilité ? Qui sont les zététiciens, ces vulgarisateurs sceptiques ? Quels éclairages apporte la psychologie ? L\'objectif de ce livre est d\'offrir des ressources, des outils et des méthodes à tous ceux qui souhaitent développer leur esprit critique, afin de déjouer les pièges et manipulations (rhétorique fallacieuse, biais cognitifs, etc.), pour des prises de décision plus éclairées et plus libres.',18.00,'manual', NULL),
('https://odette-et-lulu.com/shop/foulard-balte/','Odette & Lulu','Foulard Balte Bleu Klein','https://odette-et-lulu.com/wp-content/uploads/2023/08/foulard-balte_bleu-klein_odette-et-lulu_05.jpg','Foulard en acrylique, taille 125cm x 125cm',42.00, 'manual', NULL);

INSERT INTO `wluser_wlwishlist` (`user_id`,`wishlist_id`,`role_in_wishlist`)
VALUES 
(1,1, 'owner'),
(2,2, 'owner'),
(3,3, 'owner');

INSERT INTO `wlwishlist_wlproduct` (`wishlist_id`, `product_id`, `product_quantity`,`buy_decision`, `purchase_cancelled`)
VALUES
(1, 3, 1, FALSE, FALSE),
(2, 1, 1, FALSE, FALSE),
(3, 2, 1, FALSE, FALSE);

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
