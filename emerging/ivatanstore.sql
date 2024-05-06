-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 06, 2024 at 01:42 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ivatanstore`
--

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

CREATE TABLE `address` (
  `id` int(11) NOT NULL,
  `street_address` varchar(150) NOT NULL,
  `city` varchar(100) NOT NULL,
  `region` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `address`
--

INSERT INTO `address` (`id`, `street_address`, `city`, `region`) VALUES
(4, 'Admin Street', 'Admin', 'Admin'),
(6, 'Basco Batanes', 'Batanes', 'Region 2');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_date` datetime NOT NULL,
  `payment_method_id` int(11) NOT NULL,
  `shipping_address_id` int(11) NOT NULL,
  `order_total` float NOT NULL,
  `delivery_date` date NOT NULL,
  `order_status_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `product_id`, `qty`, `order_id`) VALUES
(20, 28, 3, 16),
(21, 22, 1, 16),
(22, 27, 1, 16),
(29, 27, 1, 23),
(39, 23, 1, 33),
(40, 28, 1, 34);

-- --------------------------------------------------------

--
-- Table structure for table `order_status`
--

CREATE TABLE `order_status` (
  `id` int(11) NOT NULL,
  `status` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_status`
--

INSERT INTO `order_status` (`id`, `status`) VALUES
(1, 'Ordered'),
(2, 'Shipped'),
(3, 'On the way'),
(4, 'Delivered');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` varchar(2000) DEFAULT NULL,
  `price` float NOT NULL,
  `pet_type` varchar(200) DEFAULT NULL,
  `product_type` varchar(200) DEFAULT NULL,
  `brand` mediumtext DEFAULT NULL,
  `is_for_subscription` tinyint(1) NOT NULL,
  `image_url` varchar(300) DEFAULT NULL,
  `size_options` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `pet_type`, `product_type`, `brand`, `is_for_subscription`, `image_url`, `size_options`) VALUES
(17, 'Batanes Tshirts (Black)', 'Great quality T-shirt from Batanes,Philippines. A great souvenir for travel, vacation and vacationers.', 170, NULL, 'Shirt', 'BATANES LIGHTHOUSE Tayid', 1, 'https://m.media-amazon.com/images/I/A13usaonutL._CLa%7C2140%2C2000%7C81bNtZvSaFL.png%7C0%2C0%2C2140%2C2000%2B0.0%2C0.0%2C2140.0%2C2000.0_AC_SX679_.png', 'Small: 170,\nMedium: 170,\nLarge: 170,\nXL: 200,\nXXL: 200'),
(18, 'Batanes Tshirts(Navy Blue)', 'Great quality T-shirt from Batanes,Philippines. A great souvenir for travel, vacation and vacationers.', 170, NULL, 'Shirts', 'BATANES LIGHTHOUSE Tayid', 1, 'https://m.media-amazon.com/images/I/A1vJUKBjc2L._CLa%7C2140%2C2000%7C81bNtZvSaFL.png%7C0%2C0%2C2140%2C2000%2B0.0%2C0.0%2C2140.0%2C2000.0_AC_SX679_.png', 'Small: 170,\nMedium: 170,\nLarge: 170,\nXL: 200,\nXXL: 200'),
(19, 'Batanes Tshirts(Red)', 'Great quality T-shirt from Batanes,Philippines. A great souvenir for travel, vacation and vacationers.', 170, NULL, 'Shirts', 'BATANES LIGHTHOUSE Tayid', 1, 'https://m.media-amazon.com/images/I/B1DnWZEQ8ES._CLa%7C2140%2C2000%7C81bNtZvSaFL.png%7C0%2C0%2C2140%2C2000%2B0.0%2C0.0%2C2140.0%2C2000.0_AC_SX679_.png', 'Small: 170,\nMedium: 170,\nLarge: 170,\nXL: 200,\nXXL: 200'),
(20, 'Batanes Vintage T-Shirt(Black)', 'A vintage T-shirt made our of great quality from Batanes,Philippines. A great souvenir for travel, vacation and vacationers.', 170, NULL, 'Shirts', 'BATANES LIGHTHOUSE Tayid', 1, 'https://m.media-amazon.com/images/I/A13usaonutL._CLa%7C2140%2C2000%7C81J4-WYP2PL.png%7C0%2C0%2C2140%2C2000%2B0.0%2C0.0%2C2140.0%2C2000.0_AC_SX679_.png', 'Small: 170,\nMedium: 170,\nLarge: 170,\nXL: 200,\nXXL: 200'),
(21, 'Batanes Vintage T-Shirt(Grey)', 'A vintage T-shirt made our of great quality from Batanes,Philippines. A great souvenir for travel, vacation and vacationers.', 170, NULL, 'Shirts', 'BATANES LIGHTHOUSE Tayid', 1, 'https://m.media-amazon.com/images/I/B17H79+I8tS._CLa%7C2140%2C2000%7C81J4-WYP2PL.png%7C0%2C0%2C2140%2C2000%2B0.0%2C0.0%2C2140.0%2C2000.0_AC_SX679_.png', 'Small: 170,\nMedium: 170,\nLarge: 170,\nXL: 200,\nXXL: 200'),
(22, 'Batanes Vintage T-Shirt(Brown)\r\n', 'A vintage T-shirt made our of great quality from Batanes,Philippines. A great souvenir for travel, vacation and vacationers.', 170, NULL, 'Shirts', 'BATANES LIGHTHOUSE Tayid', 1, 'https://m.media-amazon.com/images/I/B1F9XqluwtS._CLa%7C2140%2C2000%7C81J4-WYP2PL.png%7C0%2C0%2C2140%2C2000%2B0.0%2C0.0%2C2140.0%2C2000.0_AC_SX679_.png', 'Small: 170,\nMedium: 170,\nLarge: 170,\nXL: 200,\nXXL: 200'),
(23, 'Batanes Vintage T-Shirt(Navy Blue)', 'A vintage T-shirt made our of great quality from Batanes,Philippines. A great souvenir for travel, vacation and vacationers.', 170, NULL, 'Shirts', 'BATANES LIGHTHOUSE Tayid', 1, 'https://m.media-amazon.com/images/I/A1vJUKBjc2L._CLa%7C2140%2C2000%7C81J4-WYP2PL.png%7C0%2C0%2C2140%2C2000%2B0.0%2C0.0%2C2140.0%2C2000.0_AC_SX679_.png', 'Small: 170,\nMedium: 170,\nLarge: 170,\nXL: 200,\nXXL: 200'),
(24, 'Batanes Lighthouse T-shirt(Black)', 'Great quality T-shirt displaying the lighthouse from Batanes, Philippines. A great souvenir for travel, vacation and vacationers.', 170, NULL, 'Shirts', 'Batanes Shirt', 1, 'https://down-ph.img.susercontent.com/file/sg-11134202-7rcca-lstofsi0cloud6', 'Small: 170,\nMedium: 170,\nLarge: 170,\nXL: 200,\nXXL: 200'),
(25, 'Batanes Vintage T-Shirt(Royal Blue)', 'A vintage T-shirt made our of great quality from Batanes,Philippines. A great souvenir for travel, vacation and vacationers.', 170, NULL, 'Shirts', 'BATANES LIGHTHOUSE Tayid', 1, 'https://m.media-amazon.com/images/I/B1EryObaEWS._CLa%7C2140%2C2000%7C81J4-WYP2PL.png%7C0%2C0%2C2140%2C2000%2B0.0%2C0.0%2C2140.0%2C2000.0_AC_SX679_.png', 'Small: 170,\nMedium: 170,\nLarge: 170,\nXL: 200,\nXXL: 200'),
(26, 'Tumeric Teas', 'In terms of traditional Filipino beverages, Ginger Turmeric Tea or Salabat Tea definitely takes the crown. Besides its pungent properties, it is also renowned for its numerous health benefits', 200, NULL, 'Foods', 'Batanes Tea', 1, 'https://down-ph.img.susercontent.com/file/9d087c98dcb6463fcb4d2d1f7afaddb0', '360g: 200'),
(27, 'Bamboo cone hat', 'Bamboo is a used for making bags, hats, and baskets in the Philippines as it is a common material. Enjoy a bamboo cone hat that can protect you from the sun when you are in Batanes.', 150, NULL, 'Hats', 'Batanes Hats', 1, 'https://m.media-amazon.com/images/I/41+9fQjtkDL._AC_.jpg', 'Medium: 150'),
(28, 'Philippines ref magnet', 'Enjoy putting this magnet on your refrigerator door as a souvenir or as a memory of the time you visited the Philippines.\r\n', 75, NULL, 'Magnet', 'Batanes Souvenir', 1, 'https://m.media-amazon.com/images/I/715fVnwpXdL._AC_UL640_FMwebp_QL65_.jpg', 'Medium: 75'),
(29, 'Bamboo Basket', 'The bamboo basket is made of 100% bamboo by the locals in Batanes City. The basket can be used for storage.', 150, NULL, 'Basket', 'Batanes Bamboo', 1, 'https://cdn.myonlinestore.eu/9420d998-6be1-11e9-a722-44a8421b9960/image/cache/full/dcb40e5bd4e9070b383d66a624dbefa8caa04d43.jpg?20240502131218', 'Medium: 150');

-- --------------------------------------------------------

--
-- Table structure for table `shopping_cart`
--

CREATE TABLE `shopping_cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shopping_cart`
--

INSERT INTO `shopping_cart` (`id`, `user_id`) VALUES
(6, 1),
(22, 5);

-- --------------------------------------------------------

--
-- Table structure for table `shopping_cart_item`
--

CREATE TABLE `shopping_cart_item` (
  `id` int(11) NOT NULL,
  `cart_id` int(11) DEFAULT NULL,
  `product_item_id` int(11) DEFAULT NULL,
  `qty` int(11) NOT NULL,
  `size` varchar(50) DEFAULT NULL,
  `subscription` varchar(100) DEFAULT NULL,
  `price` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--

CREATE TABLE `subscriptions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `address_id` int(11) NOT NULL,
  `payment_method_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `frequency` varchar(100) NOT NULL,
  `qty` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subscriptions`
--

INSERT INTO `subscriptions` (`id`, `user_id`, `product_id`, `address_id`, `payment_method_id`, `start_date`, `frequency`, `qty`) VALUES
(19, 1, 28, 1, 19, '2024-05-03', '1 Week', 3),
(20, 1, 22, 1, 19, '2024-05-03', '1 Month', 1),
(21, 1, 27, 1, 19, '2024-05-03', '1 Week', 1),
(22, 5, 27, 6, 26, '2024-05-06', '2 Weeks', 1),
(23, 5, 23, 6, 36, '2024-05-06', '', 1),
(24, 5, 28, 6, 37, '2024-05-06', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(50) NOT NULL,
  `email_address` varchar(200) NOT NULL,
  `password` varchar(100) NOT NULL,
  `phone_number` varchar(15) NOT NULL,
  `is_admin` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email_address`, `password`, `phone_number`, `is_admin`) VALUES
(1, 'Alex', 'Alexei@gmail.com', '$2y$10$Kv4we/lx4l7nW1OunHH1eOblHRGNoUDB9XMAQZFj5HPwNU5FCVGt2', '09123124231', 0),
(3, 'user', 'user@gmail.com', '$2y$10$UWG1hh5cNGwtc14g.Ygm3uscwE5XSvq9eLQJ3F8fCJjVExx20WX0G', '09123671322', 0),
(4, 'admin', 'admin@gmail.com', '$2y$10$vY91bJ1jcWbY6aHVeLDaTOAi7If5oab01cvwSo7MbCMr5eP99uN2i', '09319287325', 1),
(5, 'Alexei Tolledo', 'alezt.att@gmail.com', '$2y$10$b4mGHOw9qp0wH1.SZtzos.avWUAR7cRT5O3S2/MomtGr8NX5U7ik6', '09281503667', 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_address`
--

CREATE TABLE `user_address` (
  `user_id` int(11) NOT NULL,
  `address_id` int(11) NOT NULL,
  `is_default` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_address`
--

INSERT INTO `user_address` (`user_id`, `address_id`, `is_default`) VALUES
(4, 4, 1),
(5, 6, 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_payment_method`
--

CREATE TABLE `user_payment_method` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `provider` varchar(100) NOT NULL,
  `card_name` varchar(100) NOT NULL,
  `card_number` varchar(100) NOT NULL,
  `expiration_date` varchar(10) NOT NULL,
  `cvv` varchar(10) NOT NULL,
  `is_default` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_payment_method`
--

INSERT INTO `user_payment_method` (`id`, `user_id`, `provider`, `card_name`, `card_number`, `expiration_date`, `cvv`, `is_default`) VALUES
(19, 1, 'Gcash', 'Alexei Tolledo', '29187312342', '01/34', '451', 0),
(26, 5, 'Gcash', 'Alexei Tolledo', '1293712311', '01/23', '123', 0),
(36, 5, 'Gcash', 'Alexei Tolledo', '123412341234', '123', '123', 0),
(37, 5, 'Gcash', 'Alexei Tolledo', '123412341234', '123', '123', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_status` (`order_status_id`),
  ADD KEY `payment_method_orders` (`payment_method_id`),
  ADD KEY `shipping_address_orders` (`shipping_address_id`),
  ADD KEY `users_orders` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_order_items` (`product_id`);

--
-- Indexes for table `order_status`
--
ALTER TABLE `order_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shopping_cart`
--
ALTER TABLE `shopping_cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cart` (`user_id`);

--
-- Indexes for table `shopping_cart_item`
--
ALTER TABLE `shopping_cart_item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cart_id` (`cart_id`),
  ADD KEY `product_id` (`product_item_id`);

--
-- Indexes for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `products` (`product_id`),
  ADD KEY `address_id` (`address_id`),
  ADD KEY `payment_method_id` (`payment_method_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_address`
--
ALTER TABLE `user_address`
  ADD PRIMARY KEY (`user_id`,`address_id`),
  ADD KEY `address` (`address_id`);

--
-- Indexes for table `user_payment_method`
--
ALTER TABLE `user_payment_method`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `address`
--
ALTER TABLE `address`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `order_status`
--
ALTER TABLE `order_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `shopping_cart`
--
ALTER TABLE `shopping_cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `shopping_cart_item`
--
ALTER TABLE `shopping_cart_item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `user_payment_method`
--
ALTER TABLE `user_payment_method`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `order_status` FOREIGN KEY (`order_status_id`) REFERENCES `order_status` (`id`),
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`shipping_address_id`) REFERENCES `address` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payment_method_orders` FOREIGN KEY (`payment_method_id`) REFERENCES `user_payment_method` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `shipping_address_orders` FOREIGN KEY (`shipping_address_id`) REFERENCES `address` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `users_orders` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `product_order_items` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `shopping_cart`
--
ALTER TABLE `shopping_cart`
  ADD CONSTRAINT `cart` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `shopping_cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `shopping_cart_item`
--
ALTER TABLE `shopping_cart_item`
  ADD CONSTRAINT `cart_id` FOREIGN KEY (`cart_id`) REFERENCES `shopping_cart` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `product_id` FOREIGN KEY (`product_item_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `shopping_cart_item_ibfk_1` FOREIGN KEY (`cart_id`) REFERENCES `shopping_cart` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `shopping_cart_item_ibfk_2` FOREIGN KEY (`product_item_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_address`
--
ALTER TABLE `user_address`
  ADD CONSTRAINT `address` FOREIGN KEY (`address_id`) REFERENCES `address` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_address_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_address_ibfk_2` FOREIGN KEY (`address_id`) REFERENCES `address` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_payment_method`
--
ALTER TABLE `user_payment_method`
  ADD CONSTRAINT `user_payment_method_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
