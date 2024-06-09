-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:4306
-- Generation Time: Jul 30, 2023 at 05:21 PM
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
-- Database: `itisdev`
--

-- --------------------------------------------------------

--
-- Table structure for table `dish`
--

CREATE TABLE `dish` (
  `dishID` int(11) NOT NULL,
  `dishName` varchar(45) NOT NULL,
  `dateCreated` datetime NOT NULL,
  `price` float NOT NULL,
  `Active` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `img` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `dish`
--

INSERT INTO `dish` (`dishID`, `dishName`, `dateCreated`, `price`, `Active`, `img`) VALUES
(2001, 'Spaghetti', '2023-07-13 13:43:16', 60, 'Yes', 'imgs/Spaghetti.png'),
(2002, 'Pizza', '2023-07-13 13:43:16', 150, 'Yes', 'imgs/Pizza.png');

-- --------------------------------------------------------

--
-- Table structure for table `disparity`
--

CREATE TABLE `disparity` (
  `logID` int(11) NOT NULL,
  `ingredientID` int(11) NOT NULL,
  `sQuantity` float NOT NULL,
  `mQuantity` float NOT NULL,
  `createdAt` datetime NOT NULL,
  `createdBy` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expired`
--

CREATE TABLE `expired` (
  `expiredID` int(11) NOT NULL,
  `ingredientID` int(11) NOT NULL,
  `quantity` float NOT NULL,
  `expiredDate` datetime NOT NULL,
  `updatedBy` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ingredient`
--

CREATE TABLE `ingredient` (
  `ingredientID` int(11) NOT NULL,
  `ingredientName` varchar(45) DEFAULT NULL,
  `quantity` float DEFAULT NULL,
  `unitID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `ingredient`
--

INSERT INTO `ingredient` (`ingredientID`, `ingredientName`, `quantity`, `unitID`) VALUES
(1001, 'Flour', 5000, 1004),
(1002, 'Chicken', 5000, 1002),
(1003, 'Egg', 500, 1005),
(1004, 'Corn', 500, 1002),
(1005, 'Pork', 5000, 1002),
(1008, 'Steak', 5000, 1002);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `orderID` int(11) NOT NULL,
  `total` float NOT NULL,
  `createdBy` int(11) NOT NULL,
  `createdAt` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`orderID`, `total`, `createdBy`, `createdAt`) VALUES
(62, 210, 2, '2023-07-27 10:11:50'),
(63, 180, 2, '2023-07-27 10:12:25'),
(64, 300, 2, '2023-07-26 10:12:35'),
(65, 270, 2, '2023-07-25 10:12:42'),
(66, 210, 2, '2023-07-24 10:13:12'),
(71, 210, 2, '2023-07-27 10:36:06'),
(72, 210, 2, '2023-07-30 13:45:40');

-- --------------------------------------------------------

--
-- Table structure for table `order_item`
--

CREATE TABLE `order_item` (
  `ItemID` int(11) NOT NULL,
  `orderID` int(11) NOT NULL,
  `dishID` int(11) NOT NULL,
  `quantity` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `order_item`
--

INSERT INTO `order_item` (`ItemID`, `orderID`, `dishID`, `quantity`) VALUES
(118, 62, 2001, 1),
(119, 62, 2002, 1),
(120, 63, 2001, 3),
(121, 64, 2002, 2),
(122, 65, 2001, 2),
(123, 65, 2002, 1),
(124, 66, 2001, 1),
(125, 66, 2002, 1),
(134, 71, 2001, 1),
(135, 71, 2002, 1),
(136, 72, 2001, 1),
(137, 72, 2002, 1);

-- --------------------------------------------------------

--
-- Table structure for table `pending_dish`
--

CREATE TABLE `pending_dish` (
  `nDishID` int(11) NOT NULL,
  `dishName` varchar(45) NOT NULL,
  `type` enum('Edit','New') NOT NULL,
  `dateCreated` varchar(45) NOT NULL,
  `approved` enum('Yes','No') DEFAULT NULL,
  `createdBy` int(11) NOT NULL,
  `img` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `pending_dish`
--

INSERT INTO `pending_dish` (`nDishID`, `dishName`, `type`, `dateCreated`, `approved`, `createdBy`, `img`) VALUES
(56, 'Bread', 'New', '2023-07-30 13:49:00', NULL, 2, 'imgs/64c5f9cc41254_Bread.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `pending_recipe`
--

CREATE TABLE `pending_recipe` (
  `nRecipeID` int(11) NOT NULL,
  `nDishID` int(11) NOT NULL,
  `ingredientID` int(11) NOT NULL,
  `quantity` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `pending_recipe`
--

INSERT INTO `pending_recipe` (`nRecipeID`, `nDishID`, `ingredientID`, `quantity`) VALUES
(46, 56, 1001, 1),
(47, 56, 1004, 1);

-- --------------------------------------------------------

--
-- Table structure for table `recipe`
--

CREATE TABLE `recipe` (
  `recipeID` int(11) NOT NULL,
  `dishID` int(11) NOT NULL,
  `ingredientID` int(11) NOT NULL,
  `quantity` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `recipe`
--

INSERT INTO `recipe` (`recipeID`, `dishID`, `ingredientID`, `quantity`) VALUES
(3001, 2001, 1003, 5),
(3002, 2002, 1002, 100),
(3003, 2002, 1001, 10);

-- --------------------------------------------------------

--
-- Table structure for table `replenish`
--

CREATE TABLE `replenish` (
  `transactionID` int(11) NOT NULL,
  `ingredientID` int(11) NOT NULL,
  `quantity` float NOT NULL,
  `boughtDate` datetime NOT NULL,
  `createdBy` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='t';

-- --------------------------------------------------------

--
-- Table structure for table `unit`
--

CREATE TABLE `unit` (
  `unitID` int(11) NOT NULL,
  `unitName` varchar(45) NOT NULL,
  `type` enum('Mass','Volume','Count') NOT NULL,
  `conversion` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `unit`
--

INSERT INTO `unit` (`unitID`, `unitName`, `type`, `conversion`) VALUES
(1001, 'Kg', 'Mass', 1000),
(1002, 'g', 'Mass', 1),
(1003, 'L', 'Volume', 1000),
(1004, 'mL', 'Volume', 1),
(1005, 'pc', 'Count', 1),
(1010, 'pound', 'Mass', 453.592);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `employeeID` int(11) NOT NULL,
  `username` varchar(45) NOT NULL,
  `password` varchar(200) NOT NULL,
  `firstName` varchar(45) NOT NULL,
  `lastName` varchar(45) NOT NULL,
  `role` varchar(45) NOT NULL,
  `hireDate` date NOT NULL,
  `terminateDate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`employeeID`, `username`, `password`, `firstName`, `lastName`, `role`, `hireDate`, `terminateDate`) VALUES
(1, 'jonjon123', '849bf69bddf1da737db2169d0b24f2f9', 'Jonathan', 'Lin', 'Admin', '2023-07-23', NULL),
(2, 'rafayell', '180daa9fb0c88c8ac77a0af6633ed412', 'Rafael Christian', 'Sanchez', 'Admin', '2023-07-23', NULL),
(3, 'Inventory', '4d604dd8f008145471dc845683399189', 'Inventory', 'Inventory', 'Inventory', '2023-07-23', NULL),
(4, 'Cashier', 'e7f85ad205399503a678592df8aadeb1', 'Cashier', 'Cashier', 'Cashier', '2023-07-23', NULL),
(5, 'Chef', '8fd82b8864d71ed7fa12b59e6e34cd1c', 'Chef', 'Chef', 'Chef', '2023-07-23', NULL),
(19, 'Admin', 'e3afed0047b08059d0fada10f400c1e5', 'Admin', 'Admin', 'Admin', '2023-07-25', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `dish`
--
ALTER TABLE `dish`
  ADD PRIMARY KEY (`dishID`),
  ADD UNIQUE KEY `dishID_UNIQUE` (`dishID`);

--
-- Indexes for table `disparity`
--
ALTER TABLE `disparity`
  ADD PRIMARY KEY (`logID`),
  ADD UNIQUE KEY `logID` (`logID`),
  ADD KEY `fk_Disaprity_Ingredient` (`ingredientID`),
  ADD KEY `fk_Disparity_User` (`createdBy`);

--
-- Indexes for table `expired`
--
ALTER TABLE `expired`
  ADD PRIMARY KEY (`expiredID`),
  ADD UNIQUE KEY `expiredID` (`expiredID`),
  ADD KEY `fk_Expired_Ingredient` (`ingredientID`),
  ADD KEY `fk_Expired_User` (`updatedBy`);

--
-- Indexes for table `ingredient`
--
ALTER TABLE `ingredient`
  ADD PRIMARY KEY (`ingredientID`),
  ADD UNIQUE KEY `IngredientID_UNIQUE` (`ingredientID`),
  ADD KEY `fk_Ingredient_Unit_idx` (`unitID`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`orderID`),
  ADD UNIQUE KEY `orderID_UNIQUE` (`orderID`);

--
-- Indexes for table `order_item`
--
ALTER TABLE `order_item`
  ADD PRIMARY KEY (`ItemID`),
  ADD UNIQUE KEY `ItemID_UNIQUE` (`ItemID`),
  ADD KEY `fk_Order_Dish` (`dishID`),
  ADD KEY `fk_Order_Item` (`orderID`);

--
-- Indexes for table `pending_dish`
--
ALTER TABLE `pending_dish`
  ADD PRIMARY KEY (`nDishID`),
  ADD KEY `fk_pDish_User_idx` (`createdBy`);

--
-- Indexes for table `pending_recipe`
--
ALTER TABLE `pending_recipe`
  ADD PRIMARY KEY (`nRecipeID`),
  ADD UNIQUE KEY `nRecipeID_UNIQUE` (`nRecipeID`),
  ADD KEY `fk_pRecipe_Ingredient_idx` (`ingredientID`),
  ADD KEY `fk_pRecipe_pDish_idx` (`nDishID`);

--
-- Indexes for table `recipe`
--
ALTER TABLE `recipe`
  ADD PRIMARY KEY (`recipeID`),
  ADD UNIQUE KEY `recipeID_UNIQUE` (`recipeID`),
  ADD KEY `fk_Recipe_Ingredients_idx` (`ingredientID`),
  ADD KEY `fk_Recipe_Dish` (`dishID`);

--
-- Indexes for table `replenish`
--
ALTER TABLE `replenish`
  ADD PRIMARY KEY (`transactionID`),
  ADD UNIQUE KEY `transactionID_UNIQUE` (`transactionID`),
  ADD KEY `fk_Transaction_User_idx` (`createdBy`),
  ADD KEY `fk_Replenish_Ingredient` (`ingredientID`);

--
-- Indexes for table `unit`
--
ALTER TABLE `unit`
  ADD PRIMARY KEY (`unitID`),
  ADD UNIQUE KEY `unitID_UNIQUE` (`unitID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`employeeID`),
  ADD UNIQUE KEY `Employee ID_UNIQUE` (`employeeID`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `dish`
--
ALTER TABLE `dish`
  MODIFY `dishID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2014;

--
-- AUTO_INCREMENT for table `disparity`
--
ALTER TABLE `disparity`
  MODIFY `logID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=122;

--
-- AUTO_INCREMENT for table `expired`
--
ALTER TABLE `expired`
  MODIFY `expiredID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `ingredient`
--
ALTER TABLE `ingredient`
  MODIFY `ingredientID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1009;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `orderID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `order_item`
--
ALTER TABLE `order_item`
  MODIFY `ItemID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=138;

--
-- AUTO_INCREMENT for table `pending_dish`
--
ALTER TABLE `pending_dish`
  MODIFY `nDishID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `pending_recipe`
--
ALTER TABLE `pending_recipe`
  MODIFY `nRecipeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `recipe`
--
ALTER TABLE `recipe`
  MODIFY `recipeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3016;

--
-- AUTO_INCREMENT for table `replenish`
--
ALTER TABLE `replenish`
  MODIFY `transactionID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `unit`
--
ALTER TABLE `unit`
  MODIFY `unitID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1011;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `employeeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `disparity`
--
ALTER TABLE `disparity`
  ADD CONSTRAINT `fk_Disaprity_Ingredient` FOREIGN KEY (`ingredientID`) REFERENCES `ingredient` (`ingredientID`),
  ADD CONSTRAINT `fk_Disparity_User` FOREIGN KEY (`createdBy`) REFERENCES `user` (`employeeID`);

--
-- Constraints for table `expired`
--
ALTER TABLE `expired`
  ADD CONSTRAINT `fk_Expired_Ingredient` FOREIGN KEY (`ingredientID`) REFERENCES `ingredient` (`ingredientID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Expired_User` FOREIGN KEY (`updatedBy`) REFERENCES `user` (`employeeID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `ingredient`
--
ALTER TABLE `ingredient`
  ADD CONSTRAINT `fk_Ingredient_Unit` FOREIGN KEY (`unitID`) REFERENCES `unit` (`unitID`);

--
-- Constraints for table `order_item`
--
ALTER TABLE `order_item`
  ADD CONSTRAINT `fk_Order_Dish` FOREIGN KEY (`dishID`) REFERENCES `dish` (`dishID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Order_Item` FOREIGN KEY (`orderID`) REFERENCES `orders` (`orderID`);

--
-- Constraints for table `pending_dish`
--
ALTER TABLE `pending_dish`
  ADD CONSTRAINT `fk_pDish_User` FOREIGN KEY (`createdBy`) REFERENCES `user` (`employeeID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `pending_recipe`
--
ALTER TABLE `pending_recipe`
  ADD CONSTRAINT `fk_pRecipe_Ingredient` FOREIGN KEY (`ingredientID`) REFERENCES `ingredient` (`ingredientID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_pRecipe_pDish` FOREIGN KEY (`nDishID`) REFERENCES `pending_dish` (`nDishID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `recipe`
--
ALTER TABLE `recipe`
  ADD CONSTRAINT `fk_Recipe_Dish` FOREIGN KEY (`dishID`) REFERENCES `dish` (`dishID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Recipe_Ingredient` FOREIGN KEY (`ingredientID`) REFERENCES `ingredient` (`ingredientID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `replenish`
--
ALTER TABLE `replenish`
  ADD CONSTRAINT `fk_Replenish_Ingredient` FOREIGN KEY (`ingredientID`) REFERENCES `ingredient` (`ingredientID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Replenish_User` FOREIGN KEY (`createdBy`) REFERENCES `user` (`employeeID`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
