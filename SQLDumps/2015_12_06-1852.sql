-- phpMyAdmin SQL Dump
-- version 2.11.4
-- http://www.phpmyadmin.net
--
-- Počítač: localhost
-- Vygenerováno: Neděle 06. prosince 2015, 18:52
-- Verze MySQL: 5.0.51
-- Verze PHP: 5.2.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

DROP DATABASE iisproject;

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
(1, 'První', 'Zákazník', 'V prvákově\r\nPrvní město\r\n111 11', 'prvni.zakaznik@gmail.com', 'AnHJmMc2CJWw.', '111', '2015-12-05 02:20:13', 'male'),
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

--
-- Vypisuji data pro tabulku `customer_hash`
--

INSERT INTO `customer_hash` (`chash_id`, `chash_value`, `chash_time`, `chash_customer`) VALUES
(1, 'c393b0dd60336cbf23c2f43a840faced', '2015-12-05 02:35:53', 1),
(17, '81f926270607ae8d241f10dba515a725', '2015-12-06 12:46:20', 1);

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Vypisuji data pro tabulku `employee`
--

INSERT INTO `employee` (`emp_id`, `emp_fullname`, `emp_username`, `emp_email`, `emp_password`, `emp_address`, `emp_bcn`, `emp_role`, `emp_enabled`, `emp_phone`) VALUES
(1, 'Daniel Dušek', 'danny', 'dusekdan@gmail.com', 'AnHJmMc2CJWw.', 'Na Blahově 445, Vysoké Mýto 4', '940502/3958', 2, 'true', '721852506'),
(2, 'Anna Popková', 'popanda', 'p.op.kova.ann@gmail.com', 'AnH49rfN/5aHU', 'Informace o popandě', '000000/0000', 1, 'true', '732378478'),
(4, 'Pavel Röszler', 'pkiller2', 'pavel.resler@zabijak.gmail.com', 'AndkD7DKJBAV6', 'Můj kámoš z bytu.', '121212/1212', 2, 'false', '112112112'),
(7, 'Martin Vaníček', 'varan', 'varan@vanicek.com', 'AnHJmMc2CJWw.', 'Někde v ústí', '000000/0000', 1, 'true', '158158158'),
(6, 'Tomáš Kaplan', 'kaplis', 'tomas.kaplan@faggot.com', 'AnHJmMc2CJWw.', 'Další spolubydlící z bytu...', '555555/1122', 1, 'true', '123123123'),
(8, 'Martin Svoboda', 'Svod', 'Svod@gmail.com', 'AnHJmMc2CJWw.', 'asd', '121212/1212', 1, 'true', '');

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=41 ;

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
(34, '51ac562e56a4df460d39d8793be23ec3', '2015-12-06 16:57:13', 7),
(26, '93155ba511a6bf20eb63b7149b7716d9', '2015-12-05 21:47:58', 1),
(27, 'e5d0d4533fefdd9154859ed030a5e55f', '2015-12-06 13:08:34', 1),
(31, '5527831503b450240e5c2e51b3e8797e', '2015-12-06 16:18:40', 2),
(32, '30729474ccede3fac4b5f6d4d993ac8b', '2015-12-06 16:49:18', 2),
(36, '84d066b40ba796d7b2946f0f2808a3d3', '2015-12-06 17:03:02', 2),
(40, 'ff9903d1f98b23f0fb3952f63838301e', '2015-12-06 18:51:00', 1);

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Vypisuji data pro tabulku `order_product`
--

INSERT INTO `order_product` (`ordp_id`, `ordp_product`, `ordp_order`, `ordp_quantity`) VALUES
(1, 9, 1, 5),
(2, 6, 1, 2),
(3, 9, 2, 100),
(4, 8, 2, 100),
(5, 6, 3, 664),
(6, 7, 4, 5),
(7, 7, 5, 5),
(8, 4, 5, 15),
(9, 9, 5, 25),
(10, 8, 5, 5);

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Vypisuji data pro tabulku `orders`
--

INSERT INTO `orders` (`ord_id`, `ord_processed`, `ord_servedby`, `ord_orderedby`, `ord_time`) VALUES
(1, 'true', 0, 1, '2015-12-05 19:52:26'),
(2, 'true', 0, 2, '2015-12-05 22:11:50'),
(3, 'true', 0, 1, '2015-12-05 23:39:04'),
(4, 'true', 0, 1, '2015-12-06 12:46:29'),
(5, 'true', 0, 1, '2015-12-06 13:05:37');

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- Vypisuji data pro tabulku `product`
--

INSERT INTO `product` (`pr_id`, `pr_name`, `pr_description`, `pr_quantity`, `pr_available`, `pr_imageurl`, `pr_price`, `pr_subcategory`, `pr_addedby`, `pr_supplier`, `pr_addtime`) VALUES
(11, 'Produkt č. 2 - bez initial stacku', '', 0, 'false', 'HTTP_DEFAULT_URL_FILLIN', 2500, 14, 2, 29, '2015-12-06 16:19:09'),
(6, 'Nové jméno produktu', 'Nový popisek produktu.', 1000, 'true', 'http://cdn.meme.am/instances/61749589.jpg', 88, 6, 1, 3, '2015-12-05 00:00:00'),
(7, 'Další produkt do systému', 'Skvělý produkt za všechny peníze!', 10, 'true', 'http://caffaknitted.typepad.com/.a/6a00e54f8f86dc883401287636e5db970c-800wi', 2500, 14, 2, 9, '2015-12-05 14:17:55'),
(8, 'Nějaká houska', 'Toto je fakticky dobrý produkt!', 2265, 'true', 'https://photos-6.dropbox.com/t/2/AAAs_5jSd_2zGOSKO_kZDJMHl6YRdJ2ynwAHoCA0x8W1rA/12/9427999/jpeg/32x32/1/_/1/2/food-q-c-150-150-2.jpg/ELq6gwcY1-EBIAcoBw/YigVVcfgt27HHYQ-p8_SXhnijgmnJmaLAeeaL2UmFm4?size=1024x768&size_mode=3', 48, 17, 2, 5, '2015-12-05 14:20:27'),
(9, 'Celozrné housky', 'Od nás pro Vás!', 900, 'true', 'https://photos-2.dropbox.com/t/2/AAAZYJBVaGeh5tcrCbshOX-QP1YxON4PpFhO472POa0o3Q/12/9427999/jpeg/32x32/1/_/1/2/food-q-c-150-150-7.jpg/ELq6gwcY1-EBIAcoBw/VMOS5W_bZgfN8Zynt_G9dflCTumjXjIVn38GqFWBoWw?size=1024x768&size_mode=3', 12, 6, 2, 3, '2015-12-05 14:21:05'),
(10, 'Prdel', '', 10, 'true', 'HTTP_DEFAULT_URL_FILLIN', 100, 6, 2, 5, '2015-12-06 13:57:21');

-- --------------------------------------------------------

--
-- Struktura tabulky `product_category`
--

CREATE TABLE IF NOT EXISTS `product_category` (
  `pcat_id` int(11) NOT NULL auto_increment,
  `pcat_name` varchar(255) NOT NULL,
  `pcat_description` text NOT NULL,
  PRIMARY KEY  (`pcat_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=34 ;

--
-- Vypisuji data pro tabulku `product_category`
--

INSERT INTO `product_category` (`pcat_id`, `pcat_name`, `pcat_description`) VALUES
(1, 'První kategorie', 'Toto je historicky vůbec první kategorie přidaná do databáze dynamicky přes formulář.'),
(3, 'Huhu kategorie', 'Slabá kategorie... i když má silný tagy'),
(10, 'Tvoje už ne tak moc', ''),
(32, 'Kategorie bez popisku', ''),
(33, 'Kategorie', 'tadada'),
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20 ;

--
-- Vypisuji data pro tabulku `product_subcategory`
--

INSERT INTO `product_subcategory` (`psub_id`, `psub_name`, `psub_description`, `psub_category`) VALUES
(1, 'První testovací podkategorie', 'První testovací podkategorie přidána prostřednictvím formuláře. Yuhůů!', 1),
(3, 'Zkracená kategorie', 'This <u>is</u> popisek....', 3),
(6, 'Subcategory test #1', 'Blah blah', 10),
(13, 'Vedlejší podkategorie ', 'Demo pro Annu.', 28),
(14, 'Vedlejší podkategorie #2', 'Toto je hlavní podkategorie #2', 29),
(15, 'Podkat #1', '', 29),
(16, 'Podkat #2', 'a', 10),
(17, 'Podkat #3', 'aaa', 10),
(18, 'Podkategorie bez popisku', '', 32),
(19, 'should be in prvni kategorie', '', 1);

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=36 ;

--
-- Vypisuji data pro tabulku `supplier`
--

INSERT INTO `supplier` (`sup_id`, `sup_name`, `sup_resupplytime`, `sup_ico`, `sup_enabled`, `sup_mail`, `sup_phone`, `sup_address`) VALUES
(3, 'Daniel<strong>a</strong>', 5, '00000000', 'true', 'mail@mail.com', '158158158', 'Na stromě\r\nKuří noha'),
(5, 'Danil Dodavatel', 20, '123456789/55566', 'true', 'dodavatel@daniel.com', '721852506', 'Na Blahově 445/4\r\nVysoké Mýto\r\n566 01'),
(9, 'Anna', 2, '123456789', 'true', 'Dodavatelka@anna.com', '123456789', 'Štěpánov 23'),
(11, 'novydodavatel', 2, '12', 'true', 'tada', 'm', 'aaaaa'),
(14, 'Matěj Dušek', 1, '123456789/55566', 'true', 'mates@dusek.cz', '721852506', 'Tadadadad.'),
(20, 'NOVY DODAVATEL', 1, '123456789', 'true', 'Blahblah', '123456789', '123456789'),
(27, 'Test supplier #1', 1, '1', 'true', '1', '1', '1'),
(29, 'Test supplier #2', 2, '12', 'true', '2', '2', '21');
