-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 01, 2024 at 05:10 PM
-- Server version: 8.2.0
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `blissful_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart_tbl`
--

CREATE TABLE `cart_tbl` (
  `cart` int NOT NULL,
  `cust_Num` int DEFAULT NULL,
  `prod_id` int DEFAULT NULL,
  `flower_id` int DEFAULT NULL,
  `quantity` int DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `cart_tbl`
--

INSERT INTO `cart_tbl` (`cart`, `cust_Num`, `prod_id`, `flower_id`, `quantity`) VALUES
(13, 20240010, 10, 0, 2),
(11, 20240010, 11, 1, 5),
(10, 20240010, 14, 0, 2);

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `category_id` int NOT NULL,
  `category_name` varchar(100) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`category_id`, `category_name`) VALUES
(1, 'Birthday'),
(2, 'Anniversary'),
(3, 'Ceremonies');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `cust_Num` int NOT NULL,
  `cust_Fname` varchar(255) DEFAULT NULL,
  `cust_Lname` varchar(255) DEFAULT NULL,
  `cust_username` varchar(255) DEFAULT NULL,
  `cust_email` varchar(255) DEFAULT NULL,
  `cust_password` varchar(255) DEFAULT NULL,
  `cust_PhoneNumber` varchar(50) DEFAULT NULL,
  `cust_Gender` varchar(20) DEFAULT NULL,
  `cust_Bdate` date DEFAULT NULL,
  `cust_profPic` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`cust_Num`, `cust_Fname`, `cust_Lname`, `cust_username`, `cust_email`, `cust_password`, `cust_PhoneNumber`, `cust_Gender`, `cust_Bdate`, `cust_profPic`) VALUES
(20240011, 'Kaiser', 'Zamora', 'Kaiser22', 'kaiser@gmail.com', '@Kaiser0201', '09388952457', 'Male', '2003-12-02', 'uploads/66593637897b6_Cute-Ball-Help-icon.png'),
(20240009, 'Sheryll', 'Javier', 'Sheryll03', 'sheryll@gmail.com', '@Sheryll0201', '09388952457', 'Female', '2003-12-25', 'uploads/online.png'),
(20240008, 'Ayeng', 'Labiste', 'ayeng100', 'aldohinog00158@usep.edu.ph', '@Ayeng0201', '09388952457', 'Male', '2001-12-02', 'uploads/6647728e15504_ayeng.png'),
(20240010, 'Miralie', 'Lyka', 'lyka08', 'lyka08@gmail.com', '@Miralie0201', '09388952457', 'Female', '2002-06-08', 'uploads/marina-summers-drag-race-uk-insert-9-1707479427.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `cust_address_tbl`
--

CREATE TABLE `cust_address_tbl` (
  `Address_ID` int NOT NULL,
  `cust_Num` int NOT NULL,
  `cust_fullName` varchar(255) NOT NULL,
  `cust_phoneNumber` varchar(60) NOT NULL,
  `cust_Street` varchar(255) NOT NULL,
  `cust_Purok` varchar(60) NOT NULL,
  `cust_Barangay` varchar(255) NOT NULL,
  `cust_City` varchar(255) NOT NULL,
  `cust_Province` varchar(60) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `cust_address_tbl`
--

INSERT INTO `cust_address_tbl` (`Address_ID`, `cust_Num`, `cust_fullName`, `cust_phoneNumber`, `cust_Street`, `cust_Purok`, `cust_Barangay`, `cust_City`, `cust_Province`) VALUES
(12, 20240011, 'Kaiser Zamora', '09388952457', 'Gloria Orang street', 'Purok-2', 'Apokon', 'Tagum City', 'Davao del Norte'),
(10, 20240008, 'Ariel Dohinog', '09388952457', 'Gloria Orang street', 'Purok-3', 'Apokon', 'Tagum City', 'Davao del Norte'),
(11, 20240009, 'Sheryll Javier', '09388952457', 'Gloria Orang street', 'Purok-2', 'Apokon', 'Tagum City', 'Davao del Norte');

-- --------------------------------------------------------

--
-- Table structure for table `flowers_tbl`
--

CREATE TABLE `flowers_tbl` (
  `flower_id` int NOT NULL,
  `flower_name` varchar(100) DEFAULT NULL,
  `flower_price` decimal(10,2) NOT NULL,
  `flower_img` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `flowers_tbl`
--

INSERT INTO `flowers_tbl` (`flower_id`, `flower_name`, `flower_price`, `flower_img`) VALUES
(1, 'Tulips', 20.00, 'Flower/occassion.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int NOT NULL,
  `prod_id` int NOT NULL,
  `cust_Num` int NOT NULL,
  `variations` varchar(255) NOT NULL,
  `quantity` int NOT NULL,
  `totalPrice` decimal(10,2) NOT NULL,
  `cash` decimal(10,2) NOT NULL,
  `date_purchase` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` varchar(50) DEFAULT 'Pending'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `prod_id`, `cust_Num`, `variations`, `quantity`, `totalPrice`, `cash`, `date_purchase`, `status`) VALUES
(1, 10, 20240010, '', 2, 1400.00, 1500.00, '2024-06-01 13:02:14', 'Pending'),
(2, 11, 20240010, 'Flower: Tulips', 5, 2020.00, 2020.00, '2024-06-01 13:04:43', 'Pending'),
(3, 10, 20240010, 'none', 2, 1400.00, 1400.00, '2024-06-01 13:10:49', 'Pending'),
(4, 11, 20240010, 'Tulips', 5, 2020.00, 2020.00, '2024-06-01 13:11:33', 'Pending'),
(5, 10, 20240010, 'Tulips', 2, 1420.00, 3420.00, '2024-06-01 13:14:47', 'Pending'),
(6, 11, 20240010, 'none', 5, 2000.00, 3420.00, '2024-06-01 13:14:47', 'Pending'),
(7, 10, 20240010, 'Tulips', 2, 1420.00, 4220.00, '2024-06-01 13:15:55', 'Pending'),
(8, 11, 20240010, 'none', 5, 2000.00, 4220.00, '2024-06-01 13:15:55', 'Pending'),
(9, 14, 20240010, 'none', 2, 800.00, 4220.00, '2024-06-01 13:15:55', 'Pending'),
(10, 10, 20240010, 'none', 2, 1400.00, 1400.00, '2024-06-01 13:35:47', 'Pending'),
(11, 10, 20240010, 'none', 2, 1400.00, 1400.00, '2024-06-01 13:43:23', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `prod_id` int NOT NULL,
  `category_name` varchar(100) DEFAULT NULL,
  `prod_name` varchar(255) DEFAULT NULL,
  `prod_origPrice` int DEFAULT NULL,
  `prod_discountPrice` int DEFAULT NULL,
  `prod_qoh` int DEFAULT NULL,
  `prod_image` varchar(255) DEFAULT NULL,
  `rating` int DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`prod_id`, `category_name`, `prod_name`, `prod_origPrice`, `prod_discountPrice`, `prod_qoh`, `prod_image`, `rating`) VALUES
(8, 'Anniversary', 'Waw Sanaol', 1500, 800, 50, 'uploads/Anniversary/7.png', 0),
(7, 'Anniversary', 'Happy Anniversary', 1200, 1000, 200, 'uploads/Anniversary/10.png', 0),
(6, 'Birthday', 'Happy Birthday', 1000, 500, 100, 'uploads/Birthday/flower13.png', 1),
(9, 'Birthday', 'Hala ohh', 600, 450, 60, 'uploads/Birthday/1.png', 12),
(10, 'Anniversary', 'Happy Mot.x', 862, 700, 10, 'uploads/Anniversary/5.png', 0),
(11, 'Anniversary', 'edi sanaol', 600, 400, 300, 'uploads/Anniversary/4.png', 0),
(12, 'Ceremonies', ' happy weekend', 450, 350, 300, 'uploads/Ceremonies/3.png', 0),
(13, 'Anniversary', 'Hello', 600, 300, 20, 'sample/Anniversary/7.png', 0),
(14, 'Birthday', 'Plawers Por You', 500, 400, 200, 'sample/Birthday/3.png', 0);

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

CREATE TABLE `status` (
  `status_num` int NOT NULL,
  `status_name` varchar(50) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `status`
--

INSERT INTO `status` (`status_num`, `status_name`) VALUES
(1, 'Pending'),
(2, 'Shipped'),
(3, 'Delivered');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist_tbl`
--

CREATE TABLE `wishlist_tbl` (
  `wishlist_num` int NOT NULL,
  `prod_id` int NOT NULL,
  `cust_Num` int DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `wishlist_tbl`
--

INSERT INTO `wishlist_tbl` (`wishlist_num`, `prod_id`, `cust_Num`) VALUES
(23, 14, 20240010),
(16, 11, 20240010),
(24, 10, 20240010);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart_tbl`
--
ALTER TABLE `cart_tbl`
  ADD PRIMARY KEY (`cart`),
  ADD KEY `cust_Num` (`cust_Num`),
  ADD KEY `prod_id` (`prod_id`),
  ADD KEY `flower_id` (`flower_id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `category_name` (`category_name`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`cust_Num`);

--
-- Indexes for table `cust_address_tbl`
--
ALTER TABLE `cust_address_tbl`
  ADD PRIMARY KEY (`Address_ID`,`cust_Num`),
  ADD KEY `cust_Num` (`cust_Num`);

--
-- Indexes for table `flowers_tbl`
--
ALTER TABLE `flowers_tbl`
  ADD PRIMARY KEY (`flower_id`),
  ADD UNIQUE KEY `flower_name` (`flower_name`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `prod_id` (`prod_id`),
  ADD KEY `cust_Num` (`cust_Num`),
  ADD KEY `fk_status` (`status`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`prod_id`),
  ADD KEY `category_name` (`category_name`);

--
-- Indexes for table `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`status_num`),
  ADD UNIQUE KEY `status_name` (`status_name`);

--
-- Indexes for table `wishlist_tbl`
--
ALTER TABLE `wishlist_tbl`
  ADD PRIMARY KEY (`wishlist_num`,`prod_id`),
  ADD KEY `prod_id` (`prod_id`),
  ADD KEY `cust_Num` (`cust_Num`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart_tbl`
--
ALTER TABLE `cart_tbl`
  MODIFY `cart` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `cust_Num` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20240012;

--
-- AUTO_INCREMENT for table `cust_address_tbl`
--
ALTER TABLE `cust_address_tbl`
  MODIFY `Address_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `flowers_tbl`
--
ALTER TABLE `flowers_tbl`
  MODIFY `flower_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `prod_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `wishlist_tbl`
--
ALTER TABLE `wishlist_tbl`
  MODIFY `wishlist_num` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
