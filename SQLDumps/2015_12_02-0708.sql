-- phpMyAdmin SQL Dump
-- version 2.11.4
-- http://www.phpmyadmin.net
--
-- Počítač: localhost
-- Vygenerováno: Středa 02. prosince 2015, 07:06
-- Verze MySQL: 5.0.51
-- Verze PHP: 5.2.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Databáze: `iisproject`
--
CREATE DATABASE `iisproject` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `iisproject`;

-- --------------------------------------------------------

--
-- Struktura tabulky `customer`
--

CREATE TABLE IF NOT EXISTS `customer` (
  `cust_id` int(11) NOT NULL auto_increment,
  `cust_name` varchar(255) NOT NULL,
  `cust_address` varchar(255) NOT NULL,
  `cust_email` varchar(255) NOT NULL,
  `cust_password` varchar(255) NOT NULL,
  `cust_telephone` varchar(255) NOT NULL,
  PRIMARY KEY  (`cust_id`),
  UNIQUE KEY `cust_email` (`cust_email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Vypisuji data pro tabulku `customer`
--


-- --------------------------------------------------------

--
-- Struktura tabulky `customer_hash`
--

CREATE TABLE IF NOT EXISTS `customer_hash` (
  `chash_id` int(11) NOT NULL auto_increment,
  `chash_value` varchar(255) NOT NULL,
  `chash_time` datetime NOT NULL,
  `chash_customer` int(11) NOT NULL,
  PRIMARY KEY  (`chash_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Vypisuji data pro tabulku `customer_hash`
--


-- --------------------------------------------------------

--
-- Struktura tabulky `employee`
--

CREATE TABLE IF NOT EXISTS `employee` (
  `emp_id` int(11) NOT NULL auto_increment,
  `emp_fullname` varchar(255) NOT NULL,
  `emp_username` varchar(255) NOT NULL,
  `emp_email` varchar(255) NOT NULL,
  `emp_password` varchar(255) NOT NULL,
  `emp_address` varchar(255) NOT NULL,
  `emp_bcn` varchar(15) NOT NULL,
  `emp_role` int(11) NOT NULL,
  `emp_enabled` set('true','false') NOT NULL,
  `emp_phone` varchar(30) NOT NULL,
  PRIMARY KEY  (`emp_id`,`emp_username`),
  UNIQUE KEY `emp_email` (`emp_email`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Vypisuji data pro tabulku `employee`
--

INSERT INTO `employee` (`emp_id`, `emp_fullname`, `emp_username`, `emp_email`, `emp_password`, `emp_address`, `emp_bcn`, `emp_role`, `emp_enabled`, `emp_phone`) VALUES
(1, 'Daniel Dušek', 'danny', 'dusekdan@gmail.com', 'AnHJmMc2CJWw.', 'Na Blahově 445, Vysoké Mýto 4', '940502/3958', 1, 'true', '+420721852506');

-- --------------------------------------------------------

--
-- Struktura tabulky `employee_hash`
--

CREATE TABLE IF NOT EXISTS `employee_hash` (
  `ehash_id` int(11) NOT NULL auto_increment,
  `ehash_value` varchar(255) NOT NULL,
  `ehash_time` datetime NOT NULL,
  `ehash_employee` int(11) NOT NULL,
  PRIMARY KEY  (`ehash_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21 ;

--
-- Vypisuji data pro tabulku `employee_hash`
--

INSERT INTO `employee_hash` (`ehash_id`, `ehash_value`, `ehash_time`, `ehash_employee`) VALUES
(14, '04beac073df79c7e291279ae25a3d435', '2015-11-23 20:20:44', 1),
(12, '034667d2600e80b62db4fe5baee86856', '2015-10-30 08:24:36', 1),
(11, '3d21414b720721589779dec1777ea737', '2015-10-29 21:44:53', 1),
(15, '25bb3f7c63445c8c022def50b727ae4c', '2015-11-25 22:10:32', 1),
(16, '075ae4de3790820f245984e6812b43c3', '2015-11-26 13:43:15', 1),
(17, '2a9330ea03260fd21a0e04fad88b6459', '2015-11-27 19:00:10', 1),
(18, '438591247e52fe8fb237fbb3430f6f0f', '2015-11-28 11:30:39', 1),
(19, '519a28a7cac88893dcfd24cf76dc4795', '2015-12-01 14:30:30', 1),
(20, 'e80e23ca8f19a6cbec572a93690939c9', '2015-12-01 17:34:32', 1);

-- --------------------------------------------------------

--
-- Struktura tabulky `employee_role`
--

CREATE TABLE IF NOT EXISTS `employee_role` (
  `erole_id` int(11) NOT NULL auto_increment,
  `erole_name` varchar(255) NOT NULL,
  PRIMARY KEY  (`erole_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Vypisuji data pro tabulku `employee_role`
--

INSERT INTO `employee_role` (`erole_id`, `erole_name`) VALUES
(1, 'Zaměstnanec'),
(2, 'Vedení');

-- --------------------------------------------------------

--
-- Struktura tabulky `product`
--

CREATE TABLE IF NOT EXISTS `product` (
  `pr_id` int(11) NOT NULL auto_increment,
  `pr_name` varchar(255) NOT NULL,
  `pr_description` text NOT NULL,
  `pr_quantity` int(10) unsigned NOT NULL,
  `pr_available` set('true','false') NOT NULL,
  `pr_imageurl` text NOT NULL,
  `pr_price` double NOT NULL,
  `pr_subcategory` int(11) NOT NULL,
  `pr_addedby` int(11) NOT NULL,
  `pr_supplier` int(11) NOT NULL,
  PRIMARY KEY  (`pr_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Vypisuji data pro tabulku `product`
--

INSERT INTO `product` (`pr_id`, `pr_name`, `pr_description`, `pr_quantity`, `pr_available`, `pr_imageurl`, `pr_price`, `pr_subcategory`, `pr_addedby`, `pr_supplier`) VALUES
(4, 'Produkt č. 2 - bez initial stacku', 'Ahojky', 2, 'true', 'http://lorempixel.com/400/200/', 2, 13, 1, 20);

-- --------------------------------------------------------

--
-- Struktura tabulky `product_category`
--

CREATE TABLE IF NOT EXISTS `product_category` (
  `pcat_id` int(11) NOT NULL auto_increment,
  `pcat_name` varchar(255) NOT NULL,
  `pcat_description` text NOT NULL,
  PRIMARY KEY  (`pcat_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=30 ;

--
-- Vypisuji data pro tabulku `product_category`
--

INSERT INTO `product_category` (`pcat_id`, `pcat_name`, `pcat_description`) VALUES
(1, 'První kategorie', 'Toto je historicky vůbec první kategorie přidaná do databáze dynamicky přes formulář.'),
(3, '<strong>Kategorie</strong>', 'Silná kategorie'),
(10, 'fuck you', 'blahblabhal\r\n'),
(28, 'Hlavní kategorie', 'Demo pro Annu.'),
(29, 'Hlavní kategorie #2', 'Toto je fakt hlavní kategorie. Akorát je druhá no.');

-- --------------------------------------------------------

--
-- Struktura tabulky `product_subcategory`
--

CREATE TABLE IF NOT EXISTS `product_subcategory` (
  `psub_id` int(11) NOT NULL auto_increment,
  `psub_name` varchar(255) NOT NULL,
  `psub_description` text NOT NULL,
  `psub_category` int(11) NOT NULL,
  PRIMARY KEY  (`psub_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

--
-- Vypisuji data pro tabulku `product_subcategory`
--

INSERT INTO `product_subcategory` (`psub_id`, `psub_name`, `psub_description`, `psub_category`) VALUES
(1, 'První testovací podkategorie', 'První testovací podkategorie přidána prostřednictvím formuláře. Yuhůů!', 1),
(3, 'Also <strong>category</strong>', 'Should be really tested.', 3),
(6, 'Subcategory test #1', 'Blah blah', 10),
(13, 'Vedlejší podkategorie ', 'Demo pro Annu.', 28),
(14, 'Vedlejší podkategorie #2', 'Toto je hlavní podkategorie #2', 29);

-- --------------------------------------------------------

--
-- Struktura tabulky `supplier`
--

CREATE TABLE IF NOT EXISTS `supplier` (
  `sup_id` int(11) NOT NULL auto_increment,
  `sup_name` varchar(255) NOT NULL,
  `sup_resupplytime` int(11) NOT NULL,
  `sup_ico` varchar(50) NOT NULL,
  `sup_enabled` set('true','false') NOT NULL,
  `sup_mail` varchar(255) NOT NULL,
  `sup_phone` varchar(50) NOT NULL,
  `sup_address` text NOT NULL,
  PRIMARY KEY  (`sup_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=34 ;

--
-- Vypisuji data pro tabulku `supplier`
--

INSERT INTO `supplier` (`sup_id`, `sup_name`, `sup_resupplytime`, `sup_ico`, `sup_enabled`, `sup_mail`, `sup_phone`, `sup_address`) VALUES
(3, '<strong>Daniel</strong>!', 1, '10', 'true', 'mail', '721852506', 'Address\r\nTadadada\r\nFrom Fagotiny\r\nFagotland 88'),
(5, 'Danil Dodavatel', 20, '123456789/55566', 'true', 'dodavatel@daniel.com', '721852506', 'Na Blahově 445/4\r\nVysoké Mýto\r\n566 01'),
(9, 'Anna', 1, '123456789', 'true', 'Dodavatelka@anna.com', '123456789', 'Štěpánov'),
(11, 'novydodavatel', 2, '12', 'true', 'tada', 'm', 'aaaaa'),
(14, 'Matěj Dušek', 1, '123456789/55566', 'true', 'mates@dusek.cz', '721852506', 'Tadadadad.'),
(20, 'NOVY DODAVATEL', 1, '123456789', 'true', 'Blahblah', '123456789', '123456789'),
(27, 'Test supplier #1', 1, '1', 'true', '1', '1', '1'),
(29, 'Test supplier #2', 2, '12', 'true', '2', '2', '21');
