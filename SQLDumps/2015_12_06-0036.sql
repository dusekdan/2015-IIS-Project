-- phpMyAdmin SQL Dump
-- version 2.11.4
-- http://www.phpmyadmin.net
--
-- Počítač: localhost
-- Vygenerováno: Neděle 06. prosince 2015, 00:36
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
  `cust_firstname` varchar(255) NOT NULL,
  `cust_lastname` varchar(255) NOT NULL,
  `cust_address` text NOT NULL,
  `cust_email` varchar(255) NOT NULL,
  `cust_password` varchar(255) NOT NULL,
  `cust_phone` varchar(255) NOT NULL,
  `cust_registerdate` datetime NOT NULL,
  `cust_gender` set('male','female','none') NOT NULL,
  PRIMARY KEY  (`cust_id`),
  UNIQUE KEY `cust_email` (`cust_email`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Vypisuji data pro tabulku `customer`
--

INSERT INTO `customer` (`cust_id`, `cust_firstname`, `cust_lastname`, `cust_address`, `cust_email`, `cust_password`, `cust_phone`, `cust_registerdate`, `cust_gender`) VALUES
(1, 'První', 'Zákazník', 'V prvákově\r\nPrvní město\r\n111 11', 'prvni.zakaznik@gmail.com', 'AnHJmMc2CJWw.', 'Zákazník', '2015-12-05 02:20:13', 'male'),
(2, 'Druhý', 'Zákazník', 'Toto je moje druhá adresa, Dobré ne?', 'druhy@gmail.com', 'AnHJmMc2CJWw.', '158', '2015-12-05 02:48:54', 'female');

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

--
-- Vypisuji data pro tabulku `customer_hash`
--

INSERT INTO `customer_hash` (`chash_id`, `chash_value`, `chash_time`, `chash_customer`) VALUES
(1, 'c393b0dd60336cbf23c2f43a840faced', '2015-12-05 02:35:53', 1),
(16, '7772e188039579b8fd12c60fdc3e16dc', '2015-12-06 00:25:36', 2);

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Vypisuji data pro tabulku `employee`
--

INSERT INTO `employee` (`emp_id`, `emp_fullname`, `emp_username`, `emp_email`, `emp_password`, `emp_address`, `emp_bcn`, `emp_role`, `emp_enabled`, `emp_phone`) VALUES
(1, 'Daniel Dušek', 'danny', 'dusekdan@gmail.com', 'AnHJmMc2CJWw.', 'Na Blahově 445, Vysoké Mýto 4', '940502/3958', 2, 'true', '+420721852506'),
(2, 'Anna Popková', 'popanda', 'popkova.ann@gmail.com', 'AnH49rfN/5aHU', 'Horní Štěpánov\r\nZa Rohem', '945107/5555', 1, '', '(+420) 732378478'),
(4, 'Pavel Röszler', 'pkiller2', 'pavel.resler@zabijak.gmail.com', 'AndkD7DKJBAV6', 'Můj kámoš z bytu.', '88', 1, '', '112'),
(5, 'Matěj Dušek', 'mates', 'mates@dusek.com', 'AnHJmMc2CJWw.', 'Na Blahově 445/4\r\nVysoké Mýto 4\r\n566 01', '12345789', 1, '', '721852506');

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=27 ;

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
(21, '272deed79248a4a5b6b0a5daf12c3d1a', '2015-12-02 14:12:38', 1),
(20, 'e80e23ca8f19a6cbec572a93690939c9', '2015-12-01 17:34:32', 1),
(22, '9b76176b305d3b4fcf1dbf56add6e8fb', '2015-12-02 17:57:54', 2),
(23, '2b9c71609c510eb87cb837e21dd2cb4c', '2015-12-02 22:25:46', 1),
(25, '9caa4f0e9bcb061268442e6e9f26ee17', '2015-12-05 01:17:40', 2),
(26, '93155ba511a6bf20eb63b7149b7716d9', '2015-12-05 21:47:58', 1);

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
-- Struktura tabulky `order_product`
--

CREATE TABLE IF NOT EXISTS `order_product` (
  `ordp_id` int(11) NOT NULL auto_increment,
  `ordp_product` int(11) NOT NULL,
  `ordp_order` int(11) NOT NULL,
  `ordp_quantity` int(11) NOT NULL,
  PRIMARY KEY  (`ordp_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Vypisuji data pro tabulku `order_product`
--

INSERT INTO `order_product` (`ordp_id`, `ordp_product`, `ordp_order`, `ordp_quantity`) VALUES
(1, 9, 1, 5),
(2, 6, 1, 2),
(3, 9, 2, 100),
(4, 8, 2, 100),
(5, 6, 3, 664);

-- --------------------------------------------------------

--
-- Struktura tabulky `orders`
--

CREATE TABLE IF NOT EXISTS `orders` (
  `ord_id` int(11) NOT NULL auto_increment,
  `ord_processed` set('true','false','waiting') NOT NULL,
  `ord_servedby` int(11) NOT NULL,
  `ord_orderedby` int(11) NOT NULL,
  `ord_time` datetime NOT NULL,
  PRIMARY KEY  (`ord_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Vypisuji data pro tabulku `orders`
--

INSERT INTO `orders` (`ord_id`, `ord_processed`, `ord_servedby`, `ord_orderedby`, `ord_time`) VALUES
(1, 'true', 0, 1, '2015-12-05 19:52:26'),
(2, 'true', 0, 2, '2015-12-05 22:11:50'),
(3, 'true', 0, 1, '2015-12-05 23:39:04');

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
  `pr_addtime` datetime NOT NULL,
  PRIMARY KEY  (`pr_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Vypisuji data pro tabulku `product`
--

INSERT INTO `product` (`pr_id`, `pr_name`, `pr_description`, `pr_quantity`, `pr_available`, `pr_imageurl`, `pr_price`, `pr_subcategory`, `pr_addedby`, `pr_supplier`, `pr_addtime`) VALUES
(4, 'Produkt č. 2 - bez initial stacku', 'Ahojky', 2, 'true', 'http://lorempixel.com/400/200/', 2, 13, 1, 20, '0000-00-00 00:00:00'),
(6, 'Nové jméno produktu', 'Nový popisek produktu.', 1000, 'true', 'http://cdn.meme.am/instances/61749589.jpg', 88, 6, 1, 3, '2015-12-05 00:00:00'),
(7, 'Další produkt do systému', 'Skvělý produkt za všechny peníze!', 10, 'true', 'http://caffaknitted.typepad.com/.a/6a00e54f8f86dc883401287636e5db970c-800wi', 2500, 14, 2, 9, '2015-12-05 14:17:55'),
(8, 'Nějaká houska', 'Toto je fakticky dobrý produkt!', 2120, 'true', 'https://photos-6.dropbox.com/t/2/AAAs_5jSd_2zGOSKO_kZDJMHl6YRdJ2ynwAHoCA0x8W1rA/12/9427999/jpeg/32x32/1/_/1/2/food-q-c-150-150-2.jpg/ELq6gwcY1-EBIAcoBw/YigVVcfgt27HHYQ-p8_SXhnijgmnJmaLAeeaL2UmFm4?size=1024x768&size_mode=3', 48, 17, 2, 5, '2015-12-05 14:20:27'),
(9, 'Celozrné housky', 'Od nás pro Vás!', 915, 'true', 'https://photos-2.dropbox.com/t/2/AAAZYJBVaGeh5tcrCbshOX-QP1YxON4PpFhO472POa0o3Q/12/9427999/jpeg/32x32/1/_/1/2/food-q-c-150-150-7.jpg/ELq6gwcY1-EBIAcoBw/VMOS5W_bZgfN8Zynt_G9dflCTumjXjIVn38GqFWBoWw?size=1024x768&size_mode=3', 12, 6, 2, 3, '2015-12-05 14:21:05');

-- --------------------------------------------------------

--
-- Struktura tabulky `product_category`
--

CREATE TABLE IF NOT EXISTS `product_category` (
  `pcat_id` int(11) NOT NULL auto_increment,
  `pcat_name` varchar(255) NOT NULL,
  `pcat_description` text NOT NULL,
  PRIMARY KEY  (`pcat_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=32 ;

--
-- Vypisuji data pro tabulku `product_category`
--

INSERT INTO `product_category` (`pcat_id`, `pcat_name`, `pcat_description`) VALUES
(1, 'První kategorie', 'Toto je historicky vůbec první kategorie přidaná do databáze dynamicky přes formulář.'),
(3, 'Huhu kategorie', 'Slabá kategorie... i když má silný tagy'),
(10, 'Další nadřazená kategorie', 'Tadadada to je jméno co?'),
(28, 'Hlavní kategorie', 'Demo pro Annu.'),
(29, 'Hlavní kategorie #2', 'Toto je fakt hlavní kategorie. Akorát je druhá no.'),
(30, 'Nějaká nadřazená kategorie', 'upraveno...'),
(31, 'zzA tohle je poslední nadřazená', 'padadadada');

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

--
-- Vypisuji data pro tabulku `product_subcategory`
--

INSERT INTO `product_subcategory` (`psub_id`, `psub_name`, `psub_description`, `psub_category`) VALUES
(1, 'První testovací podkategorie', 'První testovací podkategorie přidána prostřednictvím formuláře. Yuhůů!', 1),
(3, 'Zkracená kategorie', 'This <u>is</u> popisek....', 3),
(6, 'Subcategory test #1', 'Blah blah', 10),
(13, 'Vedlejší podkategorie ', 'Demo pro Annu.', 28),
(14, 'Vedlejší podkategorie #2', 'Toto je hlavní podkategorie #2', 29),
(15, 'Podkat #1', 'aaa', 10),
(16, 'Podkat #2', 'a', 10),
(17, 'Podkat #3', 'aaa', 10);

-- --------------------------------------------------------

--
-- Struktura tabulky `resupplyrequest`
--

CREATE TABLE IF NOT EXISTS `resupplyrequest` (
  `rsr_id` int(11) NOT NULL auto_increment,
  `rsr_employee` int(11) NOT NULL,
  `rsr_supplier` int(11) NOT NULL,
  `rsr_time` datetime NOT NULL,
  `rsr_product` int(11) NOT NULL,
  `rsr_quantity` int(11) NOT NULL,
  PRIMARY KEY  (`rsr_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Vypisuji data pro tabulku `resupplyrequest`
--


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
(9, 'Anna', 2, '123456789', 'true', 'Dodavatelka@anna.com', '123456789', 'Štěpánov'),
(11, 'novydodavatel', 2, '12', 'true', 'tada', 'm', 'aaaaa'),
(14, 'Matěj Dušek', 1, '123456789/55566', 'true', 'mates@dusek.cz', '721852506', 'Tadadadad.'),
(20, 'NOVY DODAVATEL', 1, '123456789', 'true', 'Blahblah', '123456789', '123456789'),
(27, 'Test supplier #1', 1, '1', 'true', '1', '1', '1'),
(29, 'Test supplier #2', 2, '12', 'true', '2', '2', '21');
