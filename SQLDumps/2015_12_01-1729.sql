-- phpMyAdmin SQL Dump
-- version 2.11.4
-- http://www.phpmyadmin.net
--
-- Počítač: localhost
-- Vygenerováno: Úterý 01. prosince 2015, 17:29
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20 ;

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
(19, '519a28a7cac88893dcfd24cf76dc4795', '2015-12-01 14:30:30', 1);

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Vypisuji data pro tabulku `product`
--

INSERT INTO `product` (`pr_id`, `pr_name`, `pr_description`, `pr_quantity`, `pr_available`, `pr_imageurl`, `pr_price`, `pr_subcategory`, `pr_addedby`, `pr_supplier`) VALUES
(1, 'Produkt č. 1', 'Toto je pes obecný-pytlohlavý. Prodáváme i na náhradní díly.', 1, 'true', 'http://caffaknitted.typepad.com/.a/6a00e54f8f86dc883401287636e5db970c-800wi', 2500, 13, 1, 10),
(2, 'Produkt č. 2 - bez initial stacku', 'Testovací produkt bez vyplněného počátečního počtu (měl by být automaticky nastaven na 0 a available ''false''. ', 0, 'false', 'http://lorempixel.com/400/200/', 200, 13, 1, 10);

-- --------------------------------------------------------

--
-- Struktura tabulky `product_category`
--

CREATE TABLE IF NOT EXISTS `product_category` (
  `pcat_id` int(11) NOT NULL auto_increment,
  `pcat_name` varchar(255) NOT NULL,
  `pcat_description` text NOT NULL,
  PRIMARY KEY  (`pcat_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=29 ;

--
-- Vypisuji data pro tabulku `product_category`
--

INSERT INTO `product_category` (`pcat_id`, `pcat_name`, `pcat_description`) VALUES
(1, 'První kategorie', 'Toto je historicky vůbec první kategorie přidaná do databáze dynamicky přes formulář.'),
(2, 'Test na SQL''OR1=1;Injection', 'Tadada <strong>hello</strong>'),
(3, '<strong>Kategorie</strong>', 'Silná kategorie'),
(4, 'jmeno', 'popisek'),
(5, 'fuck you', 'blahblabhal\r\n'),
(6, 'fuck you', 'blahblabhal\r\n'),
(7, 'fuck you', 'blahblabhal\r\n'),
(8, 'fuck you', 'blahblabhal\r\n'),
(9, 'fuck you', 'blahblabhal\r\n'),
(10, 'fuck you', 'blahblabhal\r\n'),
(11, 'fuck you', 'blahblabhal\r\n'),
(12, 'tadad', 'ahoj\r\n'),
(13, 'Ahojk', 'taadada'),
(14, 'Ahojk', 'taadada'),
(15, 'Ahojk', 'taadada'),
(16, 'Ahojk', 'taadada'),
(17, 'TEST NA DUPLICITU', 'Taaaaa'),
(18, 'TEST NA DUPLICITU2', 'Taaaaa'),
(19, 'aaaa', 'BBBBBBBBBBB'),
(20, 'aaaa2', 'BBBBBBBBBBB2'),
(21, 'REPAIRED', 'Tadadad'),
(22, 'Corrected entry', 'Corrected...'),
(23, 'Entry to check links', 'Testing entry to check links'),
(24, 'Linkbase test 2', 'Numero due'),
(25, 'Linkbase testing 3', 'Just decsription'),
(26, 'qqqq', 'bbb'),
(27, 'eqlší', 'a ještě'),
(28, 'Hlavní kategorie', 'Demo pro Annu.');

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

--
-- Vypisuji data pro tabulku `product_subcategory`
--

INSERT INTO `product_subcategory` (`psub_id`, `psub_name`, `psub_description`, `psub_category`) VALUES
(1, 'První testovací podkategorie', 'První testovací podkategorie přidána prostřednictvím formuláře. Yuhůů!', 1),
(2, 'Let''s test some SQL injection '' ''OR 1=1%%', '%% HELLO LICK MY BoOT(ing flash) devide '''' or ''''=='''' or string like %string%', 2),
(3, 'Also <strong>category</strong>', 'Should be really tested.', 3),
(4, 'test', 'test', 3),
(5, 'should be in prvni kategorie', 'gaada', 1),
(6, 'Subcategory test #1', 'Blah blah', 10),
(7, 'any difference', '...', 1),
(8, 'tada', 'tadada', 3),
(9, '2', '2', 3),
(10, 'aaaaaaaaaaa', 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', 4),
(11, 'a teď?', 'aaa...', 3),
(12, 'Taaaadadadad', 'adadadad', 3),
(13, 'Vedlejší podkategorie ', 'Demo pro Annu.', 28);

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
(1, 'Daniel'' as', 2, 'Testovací IČO', 'true', '', '', ''),
(2, 'danil2''s', 20, 'testovací', 'true', '', '', ''),
(3, '<strong>Daniel</strong>', 10, 'Nevím', 'true', '', '', ''),
(4, 'Danil Dodavatel', 0, '', 'true', '20', '123456789/55566', ''),
(5, 'Danil Dodavatel', 20, '123456789/55566', 'true', 'dodavatel@daniel.com', '721852506', 'Na Blahově 445/4\r\nVysoké Mýto\r\n566 01'),
(6, 'Testovací dodavatel na php 5.2.5', 1, '123456789/55566', 'true', 'Let''s check out unsafe string', '+430721852506', 'Na Blahově 445/4\r\nVysoké Mýto\r\nPod náserem\r\n155 12'),
(7, 'Neodešle', 1, 'Stále ne', 'true', 'Stále neodešle', 'Pořád ne', 'Už by mohl'),
(8, 'Double Post Back Test', 1, '123456789/55566', 'true', 'dan@dodava.com', '112', 'V prdeli.'),
(9, 'Anna', 1, '123456789', 'true', 'Dodavatelka@anna.com', '123456789', 'Štěpánov'),
(10, 'Anna Dodavatelka', 29, 'Není', 'true', 'Dodavatelka@anna.com', '112', 'Štěpínov'),
(11, 'novydodavatel', 2, '12', 'true', 'tada', 'm', 'aaaaa'),
(12, 'One', 4, '4', 'true', 'm', 'm', '4'),
(13, 'two', 5, '5', 'true', '5', '5', '5'),
(14, 'Matěj Dušek', 1, '123456789/55566', 'true', 'mates@dusek.cz', '721852506', 'Tadadadad.'),
(15, 'ahoj', 1, '1', 'true', 'ahoj', 'ahoj', '1'),
(16, 'NOVY DODAVATEL', 1, '123456789', 'true', 'Blahblah', '123456789', '123456789'),
(17, 'NOVY DODAVATEL', 1, '123456789', 'true', 'Blahblah', '123456789', '123456789'),
(18, 'NOVY DODAVATEL', 1, '123456789', 'true', 'Blahblah', '123456789', '123456789'),
(19, 'NOVY DODAVATEL', 1, '123456789', 'true', 'Blahblah', '123456789', '123456789'),
(20, 'NOVY DODAVATEL', 1, '123456789', 'true', 'Blahblah', '123456789', '123456789'),
(21, 'A poněkolikáté', 4, 'pada', 'true', 'data@to.com', '44', 'neco'),
(22, 'A poněkolikáté', 4, 'pada', 'true', 'data@to.com', '44', 'neco'),
(23, 'A poněkolikáté', 4, 'pada', 'true', 'data@to.com', '44', 'neco'),
(24, 'A poněkolikáté', 4, 'pada', 'true', 'data@to.com', '44', 'neco'),
(25, 'A poněkolikáté', 4, 'pada', 'true', 'data@to.com', '44', 'neco'),
(26, '&OnlyONE', 5, '&OnlyONE', 'true', '&OnlyONE', '&OnlyONE', '&OnlyONE'),
(27, 'Test supplier #1', 1, '1', 'true', '1', '1', '1'),
(28, 'Test supplier #1', 1, '1', 'true', '1', '1', '1'),
(29, 'Test supplier #2', 2, '12', 'true', '2', '2', '21'),
(30, 'Last testing supplier', 2, '88999888778/44', 'true', 'this@mail.com', '1158', 'Last fucking supplier ever.'),
(31, 'Alright, now it is a fucking last supplier', 5, '5', 'true', 'gmal@gay.com', '545', 'Tadadadad.'),
(32, 'Dodavatel pepa', 1, 'kokotko', 'true', 'Tvoje@mama.com', 'Polibsimrtko', 'penisko'),
(33, 'Dalsikokot', 5, 'sda', 'true', 'asdasd', 'asdas', 'asdasda');
