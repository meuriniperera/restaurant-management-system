-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 27, 2024 at 11:56 AM
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
-- Database: `restuarant_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `password`) VALUES
(1, 'Laura', 'laura1@gmail.com', '$2y$10$qNZMTXu.IJ6/pbkI8mop3OcxuAaAvwCtwkmL6DvIVJmuMm/cCvPIq'),
(2, 'Rafaela', 'rafaela123@gmail.com', '$2y$10$53CwMXI21xVfHR.Z8YGwC./EEGiWqZPsP5AjB0x03Ch5x2ePbtk0W'),
(3, 'Nethu', 'nethu123@gmail.com', '$2y$10$UUuFVNfGFRYGBNc2d9pxsuZ6Rmb9GpWmGxEqdiR4t7QmsAKM7xBxq');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` varchar(11) NOT NULL,
  `user_id` varchar(50) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `product_id`, `quantity`, `price`) VALUES
('5ePONLSHYHY', '2J0sndB2iDxl9oMkiMst', 1004, 3, 11.00),
('Rkm15hbdj41', '2J0sndB2iDxl9oMkiMst', 1011, 1, 23.00);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Sri Lankan Cuisine'),
(2, 'Italian Cuisine'),
(3, 'Thai Cuisine'),
(4, 'Chinese Cuisine'),
(5, 'Beverages');

-- --------------------------------------------------------

--
-- Table structure for table `menu_items`
--

CREATE TABLE `menu_items` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu_items`
--

INSERT INTO `menu_items` (`id`, `name`, `description`, `price`, `image_url`, `category_id`) VALUES
(1000, 'Mango Lassi', 'A refreshing and creamy yogurt-based drink blended with ripe mangoes, honey, and a touch of cardamom, often enjoyed chilled.\r\n', 10.00, 'bev1.webp', 5),
(1001, 'Thai Iced Tea', 'A sweet and aromatic tea made from strongly brewed black tea, sweetened with condensed milk, and served over ice with a splash of evaporated milk.', 11.00, 'bev2.jpg', 5),
(1002, 'Bubble Tea (Boba Tea)', 'A fun and flavorful drink made from tea (black, green, or flavored), milk or fruit syrups, and chewy tapioca pearls, shaken to create a frothy texture.', 10.00, 'bev3.jpg', 5),
(1003, 'Matcha Latte', 'A soothing green tea beverage made with finely ground matcha powder mixed with steamed milk and sweetened to taste, often served hot or iced.', 12.00, 'bev4.jpg', 5),
(1004, 'Classic Mojito', 'A refreshing cocktail made with white rum, fresh lime juice, mint leaves, sugar, and soda water, served over ice for a crisp and cooling experience.', 11.00, 'bev5.jpg', 5),
(1005, 'Chai Tea Latte', 'A spiced tea latte made from black tea brewed with a blend of spices like cinnamon, cardamom, and cloves, combined with steamed milk and sweetened to taste.', 12.00, 'bev6.jpg', 5),
(1006, 'Smoothie Bowl', 'A nutrient-packed blend of fruits, yogurt, and sometimes greens, poured into a bowl and topped with granola, fresh fruit, and seeds for a satisfying breakfast or snack.', 15.00, 'bev7.jpg', 5),
(1007, 'Cold Brew Coffee', 'A smooth and less acidic coffee made by steeping coarsely ground coffee beans in cold water for an extended period, resulting in a rich and robust flavor.', 12.00, 'bev8.jpg', 5),
(1008, 'Pad Thai', 'A classic stir-fried noodle dish made with rice noodles, eggs, tofu or shrimp, bean sprouts, and peanuts, flavored with tamarind paste, fish sauce, and lime.', 20.00, 'thaidish1.webp', 3),
(1009, 'Green Curry', 'A spicy and aromatic curry made with green curry paste, coconut milk, bamboo shoots, Thai eggplants, and a choice of meat or tofu, typically served with jasmine rice.\r\n', 21.00, 'card.jpg', 3),
(1010, 'Tom Yum Goong', 'A tangy and spicy shrimp soup made with lemongrass, kaffir lime leaves, galangal, mushrooms, and chili, offering a perfect balance of sour and spicy flavors.', 20.00, 'thaidish2.jpg', 3),
(1011, 'Som Tum (Green Papaya Salad)', 'A refreshing and spicy salad made from shredded green papaya, tomatoes, green beans, peanuts, and chilies, tossed in a tangy dressing of lime juice and fish sauce.', 23.00, 'thaidish3.jpg', 3),
(1012, 'Massaman Curry', 'A rich and mildly spicy curry with influences from Persian cuisine, featuring tender beef or chicken cooked in a creamy sauce made with coconut milk, potatoes, and peanuts.', 24.99, 'thaidish4.jpg', 3),
(1013, 'Pad Krapow Moo (Basil Pork Stir-Fry)', 'A flavorful stir-fry made with ground pork, Thai basil, garlic, and chili, served over rice and often topped with a fried egg.', 23.00, 'thaidish5.webp', 3),
(1014, 'Panang Curry', 'A creamy and slightly sweet curry made with Panang curry paste, coconut milk, and a choice of meat or tofu, garnished with finely sliced kaffir lime leaves.', 23.00, 'thaidish6.jpg', 3),
(1015, 'Mango Sticky Rice', 'A popular Thai dessert made with glutinous rice cooked in coconut milk, served with ripe mango slices and a sprinkle of toasted sesame seeds or mung beans.', 25.00, 'thaidish7.jpg', 3),
(1016, 'Peking Duck', 'A famous Beijing dish featuring crispy roasted duck with a golden, crackling skin, served with thin pancakes, hoisin sauce, and sliced cucumbers.', 23.00, 'chinesedish1.jpg', 4),
(1017, 'Kung Pao Chicken', 'A spicy stir-fry made with chicken, peanuts, and vegetables, tossed in a tangy sauce with Sichuan peppercorns for a distinctive flavor.', 24.00, 'chinesedish2.jpg', 4),
(1018, 'Sweet and Sour Pork', 'Tender pork pieces coated in a crispy batter and tossed in a vibrant, tangy sauce made from vinegar, sugar, and ketchup, often accompanied by bell peppers and pineapple.', 20.00, 'chinesedish4.jpg', 4),
(1019, 'Dumplings (Jiaozi)', 'Savory parcels of dough filled with a mixture of ground meat and vegetables, steamed or pan-fried, and served with a soy-based dipping sauce.', 26.00, 'chinesedish3.jpg', 4),
(1020, 'Ma Po Tofu', 'A spicy Sichuan dish featuring soft tofu cubes simmered in a sauce made with ground pork, fermented bean paste, and Sichuan peppercorns for a spicy kick.', 22.00, 'chinesedish5.jpg', 4),
(1021, 'Fried Rice', 'A versatile dish made with cooked rice stir-fried with vegetables, eggs, and a choice of meat or seafood, seasoned with soy sauce and sometimes garnished with green onions.', 30.00, 'chinesedish6.jpg', 4),
(1022, 'Chow Mein', 'Stir-fried noodles mixed with vegetables, meat or seafood, and a savory sauce, offering a satisfying combination of crispy and tender textures.', 23.00, 'chinesedish7.jpg', 4),
(1023, 'Chinese Dim Sum Platter', 'A delightful assortment of steamed and fried dim sum, including dumplings, buns, and rolls, served with a variety of dipping sauces.', 24.99, 'card2.jpg', 4),
(1024, 'Sri Lankan Crab Curry', 'A rich and spicy crab curry made with fresh local crabs and a blend of aromatic spices, served with steamed rice.', 24.00, 'card1.jpg', 1),
(1025, 'Sri Lankan Kottu Roti', 'A popular street food made with chopped roti, vegetables, eggs, and a choice of chicken, beef, or seafood, stir-fried with spices.', 27.00, 'card4.jpg', 1),
(1026, 'Hoppers (Appa)', 'A crispy, bowl-shaped pancake made from a fermented rice flour and coconut milk batter, often enjoyed with a fried egg in the center and a side of spicy sambol.', 23.00, 'sldish1.jpg', 1),
(1027, 'Lamprais', 'A Dutch-influenced dish featuring rice boiled in stock, accompanied by a mix of meat curry, brinjal pahi (eggplant pickle), and sambol, all wrapped and baked in a banana leaf.', 11.00, 'sldish2.jpg', 1),
(1028, 'Pol Sambol', 'A spicy coconut relish made with freshly grated coconut, red onions, dried chilies, lime juice, and salt, often served as a condiment with rice and curry.', 12.00, 'sldish4.jpg', 1),
(1029, 'Fish Ambul Thiyal', 'A sour fish curry made with chunks of fish simmered in a blend of spices and goraka (a souring agent), resulting in a tangy and spicy flavor.', 15.00, 'sldish5.jpg', 1),
(1030, 'Dhal Curry (Parippu)', 'A comforting lentil curry cooked with red lentils, coconut milk, and a variety of spices, often served as a staple with rice and bread.', 20.00, 'sldish6.jpg', 1),
(1031, 'Kiribath (Milk Rice)', 'A traditional dish made by cooking rice with coconut milk until it becomes creamy, then cut into diamond shapes and served with lunu miris (a spicy onion relish).', 23.00, 'sldish7.jpg', 1),
(1032, 'Italian Margherita Pizza', 'A classic pizza topped with fresh mozzarella, vine-ripened tomatoes, and fragrant basil, baked to perfection in our stone oven.', 30.00, 'card3.webp', 2),
(1033, 'Italian Tiramisu', 'A decadent dessert featuring layers of coffee-soaked ladyfingers, rich mascarpone cream, and a dusting of cocoa powder.', 20.00, 'card5.jpg', 2),
(1034, 'Lasagna', 'Layers of pasta, rich meat sauce, creamy béchamel, and melted cheese, baked until bubbly and golden, creating a hearty and satisfying dish.', 23.00, 'italydish1.webp', 2),
(1035, 'Spaghetti Carbonara', 'A creamy pasta dish made with eggs, Parmesan cheese, pancetta (or bacon), and black pepper, creating a rich and savory sauce.', 15.00, 'italydish2.jpg', 2),
(1036, 'Risotto alla Milanese', 'A creamy pasta dish made with eggs, Parmesan cheese, pancetta (or bacon), and black pepper, creating a rich and savory sauce.', 24.00, 'italydish3.jpg', 2),
(1037, 'Caprese Salad', 'A fresh salad made with slices of ripe tomatoes, mozzarella cheese, and basil leaves, drizzled with olive oil and balsamic vinegar.', 28.00, 'italydish4.jpg', 2),
(1038, 'Osso Buco', 'A traditional Milanese dish consisting of braised veal shanks cooked with vegetables, white wine, and broth, often served with a gremolata (lemon zest, garlic, and parsley) for added freshness.', 30.00, 'italydish6.jpg', 2),
(1039, 'Fettuccine Alfredo', 'A creamy pasta dish made with fettuccine noodles coated in a sauce of butter, cream, and Parmesan cheese, creating a rich and velvety texture.', 29.00, 'italydish5.jpg', 2);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `number` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `address_type` varchar(50) NOT NULL,
  `method` varchar(50) NOT NULL,
  `product_id` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `name`, `number`, `email`, `address`, `address_type`, `method`, `product_id`, `price`, `quantity`, `created_at`, `status`) VALUES
(23, 2, 'Miya', '0764626244', 'miya1234@gmail.com', '01, Colombo Street, Colombo, Sri Lanka, 23821', 'home', 'Cash on Delivery', 1036, 24.00, 1, '2024-07-25 12:42:41', 'Completed'),
(24, 2, 'Miya', '0764626244', 'miya1234@gmail.com', '01, Colombo Street, Colombo, Sri Lanka, 23821', 'home', 'Cash on Delivery', 1000, 10.00, 10, '2024-07-25 12:42:41', 'Completed'),
(25, 2, 'Miya', '0783831822', 'miya1234@gmail.com', '01, Colombo Street, Colombo, Sri Lanka, 23821', 'home', 'Cash on Delivery', 1018, 20.00, 15, '2024-07-26 11:54:59', 'Completed'),
(26, 2, 'Miya', '1233421112', 'miya1234@gmail.com', '01, Colombo Street, Colombo, Sri Lanka, 23821', 'home', 'Cash on Delivery', 1013, 23.00, 1, '2024-07-30 18:19:39', 'Processing'),
(27, 2, 'Miya', '1233421112', 'miya1234@gmail.com', '01, Colombo Street, Colombo, Sri Lanka, 23821', 'home', 'Cash on Delivery', 1025, 27.00, 6, '2024-07-30 18:19:39', 'Processing');

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `id` int(11) NOT NULL,
  `user_id` varchar(50) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `table_size` enum('small','medium','large') NOT NULL,
  `reservation_date` date NOT NULL,
  `reservation_time` time NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`id`, `user_id`, `name`, `email`, `phone`, `table_size`, `reservation_date`, `reservation_time`, `created_at`, `status`) VALUES
(6, '2J0sndB2iDxl9oMkiMst', 'Miya', 'miya1234@gmail.com', '0765432381', 'small', '2024-08-03', '19:00:00', '2024-07-23 14:05:54', 'Confirmed'),
(7, '2J0sndB2iDxl9oMkiMst', 'Miya', 'miya1234@gmail.com', '0765432381', 'large', '2024-08-10', '19:00:00', '2024-07-25 12:46:36', 'Cancelled'),
(8, '2J0sndB2iDxl9oMkiMst', 'Miya', 'miya1234@gmail.com', '0765432381', 'medium', '2024-10-12', '09:00:00', '2024-07-26 11:52:54', 'Cancelled'),
(9, '2J0sndB2iDxl9oMkiMst', 'Miya', 'miya1234@gmail.com', '0765432381', 'small', '2024-08-09', '12:00:00', '2024-07-30 18:20:56', 'Confirmed'),
(10, '2J0sndB2iDxl9oMkiMst', 'Miya', 'miya1234@gmail.com', '0765432381', 'small', '2024-08-10', '10:00:00', '2024-07-31 04:25:20', 'Confirmed');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`id`, `name`, `email`, `password`) VALUES
(1, 'John', 'john05@gmail.com', '$2y$10$j1qUSXr2nX0S3WyVqTUeVOCaJ/ieCrI9A.zqR7ja.h0ttNaUyeU3O'),
(2, 'Nimshani', 'nimshani222@gmail.com', '$2y$10$yJxKWwNfSG8/DO0vd/cVWu/P.Yju3WKn2cp/vvYeXBgvujjWUh8Cu'),
(3, 'Linda', 'linda1@gmail.com', '$2y$10$SaaOovlYEqwwZU4eS6iCiufuTkdtm7UwTL6wGYCRH4SJceDofJafu');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`) VALUES
('2J0sndB2iDxl9oMkiMst', 'Miya', 'miya1234@gmail.com', '$2y$10$gSDgGPDmlLQds6tgCWans.qQZ/..rhdqpdVitwFjDlEhnPchezGbq'),
('6TwIIdE9qK4ZhRieOI6T', 'Meurini ', 'nethup1890@gmail.com', '$2y$10$tE1QSL40egzn2NxpJ3KL1.xscjy60UB0.qwkZ0YP3tV4H8U1waSfa'),
('HjZu5jnoc4cFRbGa2Lt2', 'Layla', 'layla123@gmail.com', '$2y$10$MFuw9U2/xAIjFvU/XRweyO65pFANYpssX2undGmwXhOirOoopZSPa'),
('udXZKD7RHTR6nha6GLmk', 'Kavindya', 'kavindya444@gmail.com', '$2y$10$2Bz7CGZigq3L.foMLSvDce5bFV43Wz0cjlJ.eiGViYcJK5SmQ3Q.m');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `id` varchar(11) NOT NULL,
  `user_id` varchar(50) NOT NULL,
  `product_id` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wishlist`
--

INSERT INTO `wishlist` (`id`, `user_id`, `product_id`, `price`) VALUES
('nW6oSSHnURw', '2J0sndB2iDxl9oMkiMst', 1025, 27.00),
('xfWqKjWkd7b', '2J0sndB2iDxl9oMkiMst', 1013, 23.00);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `user_id` (`user_id`,`product_id`) USING BTREE;

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menu_items`
--
ALTER TABLE `menu_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `user_id` (`user_id`,`product_id`) USING BTREE;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `menu_items` (`id`);

--
-- Constraints for table `menu_items`
--
ALTER TABLE `menu_items`
  ADD CONSTRAINT `menu_items_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Constraints for table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD CONSTRAINT `wishlist_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `wishlist_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `menu_items` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
