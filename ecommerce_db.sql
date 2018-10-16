-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Oct 16, 2018 at 02:22 PM
-- Server version: 5.7.21
-- PHP Version: 7.2.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ecommerce_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `id` int(40) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(175) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `join_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_login` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `permissions` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `full_name`, `email`, `password`, `join_date`, `last_login`, `permissions`) VALUES
(1, 'Shawn T Fox', 'crimtor@gmail.com', '$2y$10$.pFCKAF6K46D08BGfQRccenZAb.mDT8MLhHwLkEWO/mzCVFA9NqNK', '2018-10-04 15:10:56', '2018-10-05 09:43:14', 'admin,editor'),
(2, 'Party City', 'party@abc.com', '$2y$10$oE199eC7FauzS3zKgY39G.5wjO4AixIjyP1KNLd/JWXpMaevvYpN6', '2018-10-05 11:51:17', '2018-10-16 14:16:37', 'admin,editor');

-- --------------------------------------------------------

--
-- Table structure for table `brand`
--

DROP TABLE IF EXISTS `brand`;
CREATE TABLE IF NOT EXISTS `brand` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `brand` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `brand`
--

INSERT INTO `brand` (`id`, `brand`) VALUES
(1, 'Levis'),
(2, 'Nike'),
(3, 'Polo'),
(5, 'Sketchers'),
(6, 'Adidas');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

DROP TABLE IF EXISTS `cart`;
CREATE TABLE IF NOT EXISTS `cart` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `items` text COLLATE utf8_unicode_ci NOT NULL,
  `expire_date` date NOT NULL,
  `paid` tinyint(4) NOT NULL DEFAULT '0',
  `shipped` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `items`, `expire_date`, `paid`, `shipped`) VALUES
(5, '[{\"id\":\"1\",\"size\":\"32\",\"quantity\":\"3\"}]', '2018-11-07', 1, 1),
(6, '[{\"id\":\"1\",\"size\":\"28\",\"quantity\":\"1\"}]', '2018-11-08', 1, 0),
(7, '[{\"id\":\"2\",\"size\":\"Medium\",\"quantity\":\"4\"}]', '2018-11-08', 1, 0),
(8, '[{\"id\":\"4\",\"size\":\"Medium\",\"quantity\":2}]', '2018-11-08', 1, 0),
(15, '[{\"id\":\"2\",\"size\":\"Small\",\"quantity\":\"1\"}]', '2018-11-15', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `parent` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `category`, `parent`) VALUES
(1, 'Men', 0),
(2, 'Women', 0),
(3, 'Boys', 0),
(4, 'Girls', 0),
(5, 'Shirts', 1),
(6, 'Pants', 1),
(7, 'Shirts', 2),
(8, 'Pants', 2),
(9, 'Shoes', 2),
(10, 'Dresses', 2),
(11, 'Shoes', 1),
(12, 'Accessories', 1),
(13, 'Shirts', 3),
(14, 'Pants', 3),
(15, 'Dresses', 4),
(16, 'Shoes', 4),
(17, 'Accessories', 2),
(24, 'Gifts', 0),
(25, 'Home Decor', 24),
(27, 'Shoes', 3),
(28, 'Awesome Stuff', 24);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `list_price` decimal(10,2) NOT NULL,
  `brand` int(11) NOT NULL,
  `categories` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `image` text COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `featured` tinyint(4) NOT NULL DEFAULT '0',
  `sizes` text COLLATE utf8_unicode_ci NOT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT '0',
  `sounds_like` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `title`, `price`, `list_price`, `brand`, `categories`, `image`, `description`, `featured`, `sizes`, `deleted`, `sounds_like`) VALUES
(1, 'Levis Jeans', '29.99', '39.99', 1, '6', '/my-php-shop/images/products/men4.png', 'These jeans are amazing. They are super comfy and sexy! Buy them.', 1, '28:3:2,32:5:2,36:1:2', 0, 'LFS PNTS MN LFSJNS LFSJNS '),
(2, 'Beautiful Shirt', '19.99', '24.99', 1, '5', '/my-php-shop/images/products/men1.png', 'What a beautiful blue colored polo-shirt.', 1, 'Small:2:2,Medium:6:2,Large:9:2', 0, 'LFS XRTS MN BTFLXRT BTFLXRT '),
(3, 'Generic Shirt', '20.00', '15.00', 3, '13', '/my-php-shop/images/products/fbc3afb68d2b1f3203f3c1056879d2d5.png', 'This is a generic polo shirt for boys.', 1, 'Small:2:2,Medium:4:2,Large:6:2', 0, 'PL XRTS BS JNRKXRT JNRKXRT '),
(4, 'Jacket', '59.99', '89.99', 3, '5', '/my-php-shop/images/products/1fcde9b92f7522b7d38d92ffa646805b.png', 'Super Sick Jacket', 1, 'Small:2:2,Medium:3:2,Large:1:2', 0, 'PL XRTS MN JKT JKT '),
(5, 'Rocket', '1500.00', '1800.00', 2, '25', '/my-php-shop/images/products/a625e4aedee64be8cd730e8a783377bb.jpg', 'cdsfds', 0, 'large:2:2', 0, 'NK HMTKR JFTS RKT RKT '),
(6, 'Sweet Jacket', '25.50', '49.99', 2, '14', '/my-php-shop/images/products/f90afd1fc8a053872224e90ecd231c29.png', 'Sweet Red Jacket', 0, 'Small:2:2', 0, 'NK PNTS BS SWTJKT SWTJKT '),
(7, 'Chicken', '55.55', '77.77', 3, '25', '/my-php-shop/images/products/df7e4da6aa94ef48a650185fe7d5c43d.png,/my-php-shop/images/products/1671d2de6e54deb3989fdca7adb988ab.png,/my-php-shop/images/products/73467db3e5684cbeadc08382232685a7.png,/my-php-shop/images/products/605fd2d6b1b31ea24d5e9d29db568952.png,/my-php-shop/images/products/c5b1e32c711077bb63e0f2a614876ee5.jpg', 'Really cool Chicken Outfit', 0, 'small:2:2', 0, 'PL HMTKR JFTS XKN XKN '),
(8, 'King', '35.55', '77.77', 6, '14', '/my-php-shop/images/products/f16f507087662faf6769f26f5d7e229d.jpg', 'Yup these are shoes, totally not a beer. ', 0, '10:3:1', 0, 'ATTS PNTS BS KNK KNK ');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

DROP TABLE IF EXISTS `transactions`;
CREATE TABLE IF NOT EXISTS `transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `charge_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `cart_id` int(11) NOT NULL,
  `full_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `street` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `street2` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(155) COLLATE utf8_unicode_ci NOT NULL,
  `state` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `country` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `zip` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `sub_total` decimal(10,2) NOT NULL,
  `tax` decimal(10,2) NOT NULL,
  `grand_total` decimal(10,2) NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `trn_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `trn_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `charge_id`, `cart_id`, `full_name`, `email`, `street`, `street2`, `city`, `state`, `country`, `zip`, `sub_total`, `tax`, `grand_total`, `description`, `trn_type`, `trn_date`) VALUES
(1, 'ch_1DJUe7BPNNzrXzDb3N4YjarC', 5, '', 'crimtor@gmail.com', '3264 E Elgin St.', '', 'Gilbert', 'United States', 'US', '85295', '89.97', '7.83', '97.80', '3 items from Shawns Place.', 'charge', '2018-10-09 16:29:51'),
(2, 'ch_1DJUjOBPNNzrXzDbICjA822R', 6, 'Fake Name', 'fake@gmail.com', '123 Fake St.', '', 'Sedona', 'AZ', 'USA', '85295', '29.99', '2.61', '32.60', '1 item from Shawns Place.', 'charge', '2018-10-09 16:35:17'),
(3, 'ch_1DJUlJBPNNzrXzDbO543mPeg', 7, 'Shawn Fox', 'crimtor@gmail.com', '3264 E Elgin St.', '', 'Gilbert', 'United States', 'US', '85295', '79.96', '6.96', '86.92', '4 items from Shawns Place.', 'charge', '2018-10-09 16:37:17'),
(4, 'ch_1DJVVfBPNNzrXzDb9tmRgthT', 8, 'Fake Name', 'fake@gmail.com', '123 Fake St.', '', 'Sedona', 'AZ', 'US', '85295', '119.98', '10.44', '130.42', '2 items from Shawns Place.', 'charge', '2018-10-09 17:25:11'),
(5, 'ch_1DJVYeBPNNzrXzDbVADsyG6M', 9, 'Shawn Fox', 'crimtor@gmail.com', '3264 E Elgin St.', '', 'Gilbert', 'United States', 'US', '85295', '59.99', '5.22', '65.21', '1 item from Shawns Place.', 'charge', '2018-10-09 17:28:15'),
(6, 'ch_1DJVh3BPNNzrXzDbppjLkLBC', 10, 'Fake Name', 'fake@gmail.com', '123 Fake St.', '', 'Sedona', 'AZ', 'US', '85295', '119.98', '10.44', '130.42', '2 items from Shawns Place.', 'charge', '2018-10-09 17:36:56'),
(7, 'ch_1DJVidBPNNzrXzDbaXck8TbW', 11, 'Shawn Fox', 'crimtor@gmail.com', '3264 E Elgin St.', '', 'Gilbert', 'United States', 'US', '85295', '59.99', '5.22', '65.21', '1 item from Shawns Place.', 'charge', '2018-10-09 17:38:35'),
(8, 'ch_1DJVlXBPNNzrXzDbV1h7aNG6', 12, 'Shawn Fox', 'crimtor@gmail.com', '3264 E Elgin St.', '', 'Gilbert', 'United States', 'US', '85295', '59.99', '5.22', '65.21', '1 item from Shawns Place.', 'charge', '2018-10-09 17:41:35'),
(9, 'ch_1DLzp0BPNNzrXzDbiUnhuUXC', 15, 'Shawn T Fox', 'sfox28@wgu.edu', '3264 E Elgin St.', '', 'Gilbert', 'AZ', 'US', '85295', '19.99', '1.74', '21.73', '1 item from Shawns Place.', 'charge', '2018-10-16 14:11:23');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
