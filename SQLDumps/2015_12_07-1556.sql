-- phpMyAdmin SQL Dump
-- version 2.11.4
-- http://www.phpmyadmin.net
--
-- Počítač: localhost
-- Vygenerováno: Pondělí 07. prosince 2015, 15:56
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
(1, 'Maskovaný', 'První Zákazník', 'V prvákově\r\nPrvní městoů\r\n111 11', 'prvni.zakaznik@gmail.com', 'AnHJmMc2CJWw.', '111111111', '2015-12-05 02:20:13', 'none'),
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20 ;

--
-- Vypisuji data pro tabulku `customer_hash`
--

INSERT INTO `customer_hash` (`chash_id`, `chash_value`, `chash_time`, `chash_customer`) VALUES
(1, 'c393b0dd60336cbf23c2f43a840faced', '2015-12-05 02:35:53', 1),
(17, '81f926270607ae8d241f10dba515a725', '2015-12-06 12:46:20', 1),
(19, 'a3d41f2a82597fba0034f2f3fdb62930', '2015-12-07 02:25:54', 1);

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Vypisuji data pro tabulku `employee_hash`
--

INSERT INTO `employee_hash` (`ehash_id`, `ehash_value`, `ehash_time`, `ehash_employee`) VALUES
(1, '7164448a4337b9360ca513c1c733b5f8', '2015-12-07 14:03:32', 1);

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

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
(10, 8, 5, 5),
(11, 6, 6, 100),
(12, 10, 7, 1),
(13, 7, 8, 1),
(14, 7, 9, 1),
(15, 7, 10, 1),
(16, 7, 11, 1),
(17, 7, 12, 1);

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- Vypisuji data pro tabulku `orders`
--

INSERT INTO `orders` (`ord_id`, `ord_processed`, `ord_servedby`, `ord_orderedby`, `ord_time`) VALUES
(1, 'true', 0, 1, '2015-12-05 19:52:26'),
(2, 'true', 0, 2, '2015-12-05 22:11:50'),
(3, 'true', 0, 1, '2015-12-05 23:39:04'),
(4, 'true', 0, 1, '2015-12-06 12:46:29'),
(5, 'true', 0, 1, '2015-12-06 13:05:37'),
(6, 'true', 0, 1, '2015-12-07 01:26:51'),
(7, 'true', 0, 1, '2015-12-07 01:34:03'),
(8, 'true', 0, 1, '2015-12-07 01:34:18'),
(9, 'true', 0, 1, '2015-12-07 01:34:26'),
(10, 'true', 0, 1, '2015-12-07 01:34:32'),
(11, 'true', 0, 1, '2015-12-07 01:34:46'),
(12, 'true', 0, 1, '2015-12-07 03:08:39');

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
(6, 'Nové jméno produktu', 'Nový popisek produktu.', 900, 'true', 'http://cdn.meme.am/instances/61749589.jpg', 88, 6, 1, 3, '2015-12-05 00:00:00'),
(7, 'Další produkt do systému', 'Skvělý produkt za všechny peníze!', 5, 'true', 'http://caffaknitted.typepad.com/.a/6a00e54f8f86dc883401287636e5db970c-800wi', 2500, 14, 2, 9, '2015-12-05 14:17:55'),
(8, 'Nějaká houska', 'Toto je fakticky dobrý produkt!', 2265, 'true', 'https://photos-6.dropbox.com/t/2/AAAs_5jSd_2zGOSKO_kZDJMHl6YRdJ2ynwAHoCA0x8W1rA/12/9427999/jpeg/32x32/1/_/1/2/food-q-c-150-150-2.jpg/ELq6gwcY1-EBIAcoBw/YigVVcfgt27HHYQ-p8_SXhnijgmnJmaLAeeaL2UmFm4?size=1024x768&size_mode=3', 48, 17, 2, 5, '2015-12-05 14:20:27'),
(9, 'Celozrné housky', 'Od nás pro Vás!', 900, 'true', 'https://photos-2.dropbox.com/t/2/AAAZYJBVaGeh5tcrCbshOX-QP1YxON4PpFhO472POa0o3Q/12/9427999/jpeg/32x32/1/_/1/2/food-q-c-150-150-7.jpg/ELq6gwcY1-EBIAcoBw/VMOS5W_bZgfN8Zynt_G9dflCTumjXjIVn38GqFWBoWw?size=1024x768&size_mode=3', 12, 6, 2, 3, '2015-12-05 14:21:05'),
(10, 'Prdel', '', 9, 'true', 'HTTP_DEFAULT_URL_FILLIN', 100, 6, 2, 5, '2015-12-06 13:57:21');

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
-- Struktura tabulky `supplier` This is sparta
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
