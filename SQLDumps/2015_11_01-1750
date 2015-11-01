-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Počítač: localhost
-- Vygenerováno: Ned 01. lis 2015, 17:49
-- Verze serveru: 5.5.46-0ubuntu0.14.04.2
-- Verze PHP: 5.5.9-1ubuntu4.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Databáze: `iisproject`
--

-- --------------------------------------------------------

--
-- Struktura tabulky `customer`
--

CREATE TABLE IF NOT EXISTS `customer` (
  `cust_id` int(11) NOT NULL AUTO_INCREMENT,
  `cust_name` varchar(255) NOT NULL,
  `cust_address` varchar(255) NOT NULL,
  `cust_email` varchar(255) NOT NULL,
  `cust_password` varchar(255) NOT NULL,
  `cust_telephone` varchar(255) NOT NULL,
  PRIMARY KEY (`cust_id`),
  UNIQUE KEY `cust_email` (`cust_email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `customer_hash`
--

CREATE TABLE IF NOT EXISTS `customer_hash` (
  `chash_id` int(11) NOT NULL AUTO_INCREMENT,
  `chash_value` varchar(255) NOT NULL,
  `chash_time` datetime NOT NULL,
  `chash_customer` int(11) NOT NULL,
  PRIMARY KEY (`chash_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `employee`
--

CREATE TABLE IF NOT EXISTS `employee` (
  `emp_id` int(11) NOT NULL AUTO_INCREMENT,
  `emp_fullname` varchar(255) NOT NULL,
  `emp_username` varchar(255) NOT NULL,
  `emp_email` varchar(255) NOT NULL,
  `emp_password` varchar(255) NOT NULL,
  `emp_address` varchar(255) NOT NULL,
  `emp_bcn` varchar(15) NOT NULL,
  `emp_role` int(11) NOT NULL,
  `emp_enabled` set('true','false') NOT NULL,
  `emp_phone` varchar(30) NOT NULL,
  PRIMARY KEY (`emp_id`,`emp_username`),
  UNIQUE KEY `emp_email` (`emp_email`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Vypisuji data pro tabulku `employee`
--

INSERT INTO `employee` (`emp_id`, `emp_fullname`, `emp_username`, `emp_email`, `emp_password`, `emp_address`, `emp_bcn`, `emp_role`, `emp_enabled`, `emp_phone`) VALUES
(1, 'Daniel Dušek', 'danny', 'dusekdan@gmail.com', 'AnHJmMc2CJWw.', 'Na Blahově 445, Vysoké Mýto 4', '940502/3958', 1, 'true', '+420721852506'),
(2, 'Anna Popková', 'popanda', 'popkova.ann@gmail.com', 'AnH49rfN/5aHU', 'Horní Štěpánov', '945107/4842', 2, 'true', '+420732378478');

-- --------------------------------------------------------

--
-- Struktura tabulky `employee_hash`
--

CREATE TABLE IF NOT EXISTS `employee_hash` (
  `ehash_id` int(11) NOT NULL AUTO_INCREMENT,
  `ehash_value` varchar(255) NOT NULL,
  `ehash_time` datetime NOT NULL,
  `ehash_employee` int(11) NOT NULL,
  PRIMARY KEY (`ehash_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

--
-- Vypisuji data pro tabulku `employee_hash`
--

INSERT INTO `employee_hash` (`ehash_id`, `ehash_value`, `ehash_time`, `ehash_employee`) VALUES
(10, '6c5aa9d9da692f3c9f60a31a7004750d', '2015-10-28 22:09:33', 1),
(4, '2b5c45c58a76b6db66b56791d80a3a58', '2015-10-28 19:22:06', 1),
(3, '1e379ee7b8324e1224c8f8b66889a7db', '2015-10-28 17:17:12', 1),
(14, '1c3a7066f7befff2f6f0ec36771e422b', '2015-10-30 14:41:56', 1);

-- --------------------------------------------------------

--
-- Struktura tabulky `employee_role`
--

CREATE TABLE IF NOT EXISTS `employee_role` (
  `erole_id` int(11) NOT NULL AUTO_INCREMENT,
  `erole_name` varchar(255) NOT NULL,
  PRIMARY KEY (`erole_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Vypisuji data pro tabulku `employee_role`
--

INSERT INTO `employee_role` (`erole_id`, `erole_name`) VALUES
(1, 'Zaměstnanec'),
(2, 'Vedení');
