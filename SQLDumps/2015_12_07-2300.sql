-- phpMyAdmin SQL Dump
-- version 3.5.8.2
-- http://www.phpmyadmin.net
--
-- Počítač: wm83.wedos.net:3306
-- Vygenerováno: Pon 07. pro 2015, 22:50
-- Verze serveru: 5.6.23
-- Verze PHP: 5.4.23

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Databáze: `d111348_iisproj`
--

-- --------------------------------------------------------

--
-- Struktura tabulky `customer`
--

CREATE TABLE IF NOT EXISTS `customer` (
  `cust_id` int(11) NOT NULL AUTO_INCREMENT,
  `cust_firstname` varchar(255) NOT NULL,
  `cust_lastname` varchar(255) NOT NULL,
  `cust_address` text NOT NULL,
  `cust_email` varchar(255) NOT NULL,
  `cust_password` varchar(255) NOT NULL,
  `cust_phone` varchar(255) NOT NULL,
  `cust_registerdate` datetime NOT NULL,
  `cust_gender` set('male','female','none') NOT NULL,
  PRIMARY KEY (`cust_id`),
  UNIQUE KEY `cust_email` (`cust_email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Vypisuji data pro tabulku `customer`
--

INSERT INTO `customer` (`cust_id`, `cust_firstname`, `cust_lastname`, `cust_address`, `cust_email`, `cust_password`, `cust_phone`, `cust_registerdate`, `cust_gender`) VALUES
(3, 'Zákazník', 'Daniel', 'Na Blahově 445\r\nVysoké Mýto 5\r\n566 01', 'dusekdan@gmail.com', 'AnHJmMc2CJWw.', '721852606', '2015-12-07 19:02:44', 'male'),
(4, 'Zákaznice', 'Ann', 'Horní Štěpánov', 'popkova.ann@gmail.com', 'AnH49rfN/5aHU', '732378478', '2015-12-07 19:19:40', 'female'),
(5, 'Karolína', 'Světlá', 'V mýtě\r\nza oborou\r\n880 98', 'karola.svetla@seznam.cz', 'AnHJmMc2CJWw.', '', '2015-12-07 21:32:25', 'female');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=25 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Vypisuji data pro tabulku `employee`
--

INSERT INTO `employee` (`emp_id`, `emp_fullname`, `emp_username`, `emp_email`, `emp_password`, `emp_address`, `emp_bcn`, `emp_role`, `emp_enabled`, `emp_phone`) VALUES
(1, 'Daniel Dušek', 'danny', 'dusekdan@gmail.com', 'AnHJmMc2CJWw.', 'Na Blahově 445, Vysoké Mýto 4', '940502/3958', 2, 'true', '721852506'),
(2, 'Anna Popková', 'popanda', 'p.op.kova.ann@gmail.com', 'AnH49rfN/5aHU', 'Informace o popandě', '000000/0000', 1, 'true', '732378478'),
(9, 'Demonstrátor Mazačník', 'demo-mazat', 'demo@mazat.com', 'AnHJmMc2CJWw.', 'Pod mostem 99/3', '000000/0000', 1, 'true', ''),
(10, 'Demonstrátor Editačník', 'demo-editovat', 'demo@edit.com', 'AnHJmMc2CJWw.', 'Taky bydlí pod mostem.', '000000/0000', 2, 'true', '');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Vypisuji data pro tabulku `employee_hash`
--

INSERT INTO `employee_hash` (`ehash_id`, `ehash_value`, `ehash_time`, `ehash_employee`) VALUES
(1, '7164448a4337b9360ca513c1c733b5f8', '2015-12-07 14:03:32', 1),
(3, 'a2c0dff46f7380a95f2c0f704d74bb1f', '2015-12-07 18:12:33', 2),
(6, '5ff397b04b774c122da7bc57fa781703', '2015-12-07 19:30:11', 1),
(7, '7250a85d86191feb9d38102f2b38fd44', '2015-12-07 22:00:53', 2);

-- --------------------------------------------------------

--
-- Struktura tabulky `employee_role`
--

CREATE TABLE IF NOT EXISTS `employee_role` (
  `erole_id` int(11) NOT NULL AUTO_INCREMENT,
  `erole_name` varchar(255) NOT NULL,
  PRIMARY KEY (`erole_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Vypisuji data pro tabulku `employee_role`
--

INSERT INTO `employee_role` (`erole_id`, `erole_name`) VALUES
(1, 'Zaměstnanec'),
(2, 'Vedení');

-- --------------------------------------------------------

--
-- Struktura tabulky `orders`
--

CREATE TABLE IF NOT EXISTS `orders` (
  `ord_id` int(11) NOT NULL AUTO_INCREMENT,
  `ord_processed` set('true','false','waiting') NOT NULL,
  `ord_servedby` int(11) NOT NULL,
  `ord_orderedby` int(11) NOT NULL,
  `ord_time` datetime NOT NULL,
  PRIMARY KEY (`ord_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=19 ;

--
-- Vypisuji data pro tabulku `orders`
--

INSERT INTO `orders` (`ord_id`, `ord_processed`, `ord_servedby`, `ord_orderedby`, `ord_time`) VALUES
(13, 'false', 0, 3, '2015-12-07 19:05:33'),
(14, 'false', 0, 3, '2015-12-07 19:18:59');

-- --------------------------------------------------------

--
-- Struktura tabulky `order_product`
--

CREATE TABLE IF NOT EXISTS `order_product` (
  `ordp_id` int(11) NOT NULL AUTO_INCREMENT,
  `ordp_product` int(11) NOT NULL,
  `ordp_order` int(11) NOT NULL,
  `ordp_quantity` int(11) NOT NULL,
  PRIMARY KEY (`ordp_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=22 ;

--
-- Vypisuji data pro tabulku `order_product`
--

INSERT INTO `order_product` (`ordp_id`, `ordp_product`, `ordp_order`, `ordp_quantity`) VALUES
(18, 48, 13, 1),
(19, 37, 13, 1),
(20, 16, 14, 10),
(21, 13, 18, 1);

-- --------------------------------------------------------

--
-- Struktura tabulky `product`
--

CREATE TABLE IF NOT EXISTS `product` (
  `pr_id` int(11) NOT NULL AUTO_INCREMENT,
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
  PRIMARY KEY (`pr_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=53 ;

--
-- Vypisuji data pro tabulku `product`
--

INSERT INTO `product` (`pr_id`, `pr_name`, `pr_description`, `pr_quantity`, `pr_available`, `pr_imageurl`, `pr_price`, `pr_subcategory`, `pr_addedby`, `pr_supplier`, `pr_addtime`) VALUES
(12, 'Pořadač Josef Modrý', 'Modrý pořadač od české firmy pořynor, kapacita 250 A4 fólií, doporučováno předními českými byrokraty', 20, 'true', 'http://iis.fhfstudio.com/SI/1.png', 33.9, 20, 2, 37, '2015-12-07 18:35:45'),
(13, 'Pořadač Josef Červený', 'Červený pořadač od české firmy pořynor, kapacita 250 A4 fólií, doporučováno předními českými byrokraty.', 20, 'true', 'http://iis.fhfstudio.com/SI/1.png', 33.9, 20, 2, 37, '2015-12-07 18:38:49'),
(14, 'Sešívačka Jitka', 'Sešívačka Jitka je skvělou pomocnicí v práci i domácnosti', 5, 'true', 'http://iis.fhfstudio.com/SI/1.png', 127.9, 21, 2, 37, '2015-12-07 18:39:54'),
(15, 'Děrovač Robin Fialový', 'Děrovač Robin má až 44 nastavitelných velikostí děr', 5, 'true', 'http://iis.fhfstudio.com/SI/3.png', 150, 21, 2, 37, '2015-12-07 18:40:16'),
(16, 'Děrovač Robin Růžový', 'Děrovač Robin má až 44 nastavitelných velikostí děr, v růžovém provedení', 5, 'true', 'http://iis.fhfstudio.com/SI/3.png', 150, 21, 2, 37, '2015-12-07 18:41:45'),
(17, 'Razer Deathadder nálepka - zelená', 'Nálepka s logem oblíbené herní myši Razer. Zelený podklad.', 100, 'true', 'http://iis.fhfstudio.com/SI/5.png', 99.9, 22, 2, 37, '2015-12-07 18:42:09'),
(18, 'Razer Deathadder nálepka - růžová', '', 100, 'true', 'http://iis.fhfstudio.com/SI/default_picture.png', 99.9, 22, 2, 37, '2015-12-07 18:42:32'),
(19, 'Razer Deathadder nálepka - modrá', 'Nálepka s logem oblíbené herní myši Razer. Modrý podklad.', 100, 'true', 'http://iis.fhfstudio.com/SI/6.png', 99.9, 22, 2, 37, '2015-12-07 18:42:53'),
(20, 'Razer Deathadder nálepka - červená', 'Nálepka s logem oblíbené herní myši Razer. Červený podklad.', 100, 'true', 'http://iis.fhfstudio.com/SI/4.png', 99.9, 22, 2, 37, '2015-12-07 18:43:08'),
(21, 'Papírová složka - modrá', 'Modrá papírová složka, kapacita 100 stránek.', 100, 'true', 'http://iis.fhfstudio.com/SI/9.png', 25, 23, 2, 37, '2015-12-07 18:43:50'),
(22, 'Papírová složka - zelená', 'Zelená papírová složka, kapacita 100 stránek.', 0, 'false', 'http://iis.fhfstudio.com/SI/7.png', 25, 23, 2, 37, '2015-12-07 18:44:06'),
(23, 'Papírová složka - žlutá', 'Žlutá papírová složka, kapacita 100 stránek.', 100, 'true', 'http://iis.fhfstudio.com/SI/8.png', 25, 23, 2, 37, '2015-12-07 18:44:44'),
(24, 'Tužka 3 - zelená', 'Zelená tužka č. 3, vhodná pro rýsování', 100, 'true', 'http://iis.fhfstudio.com/SI/default_picture.png', 9, 24, 2, 38, '2015-12-07 18:45:19'),
(25, 'Tužka 2 - modrá', 'Modrá tužka č. 3, vhodná pro rýsování', 100, 'true', 'http://iis.fhfstudio.com/SI/default_picture.png', 9, 24, 2, 38, '2015-12-07 18:45:37'),
(26, 'Tužka 2 - červená', 'Červená tužka č. 3, vhodná pro rýsování', 100, 'true', 'http://iis.fhfstudio.com/SI/default_picture.png', 9, 24, 2, 38, '2015-12-07 18:46:08'),
(27, 'Pilot propiska Albert, modrá', 'Populární propiska Albert od zahraniční firmy PILOT. Nic nevydrží déle, s ničím se nepíše lépe.', 30, 'true', 'http://iis.fhfstudio.com/SI/default_picture.png', 150, 25, 2, 38, '2015-12-07 18:47:12'),
(28, 'Pilot propiska Albert, červená', 'Populární propiska Albert od zahraniční firmy PILOT. Nic nevydrží déle, s ničím se nepíše lépe.', 25, 'true', 'http://iis.fhfstudio.com/SI/default_picture.png', 150, 25, 2, 38, '2015-12-07 18:47:36'),
(29, 'Pilot propiska Albert, zelená', 'Populární propiska Albert od zahraniční firmy PILOT. Nic nevydrží déle, s ničím se nepíše lépe.', 20, 'true', 'http://iis.fhfstudio.com/SI/default_picture.png', 150, 25, 2, 38, '2015-12-07 18:47:55'),
(30, 'Popisovač David, černý', 'Černý popisovač CD a DVD David. Oblíbený u všech lidí co ještě používají CD a DVD.', 20, 'true', 'http://iis.fhfstudio.com/SI/default_picture.png', 15, 26, 2, 38, '2015-12-07 18:48:32'),
(31, 'Psací sada Magdalena', 'Psací sada vytvořená speciálně pro potřeby mladých spisovatelů a spisovatelek. Nechybí ani malý blok na náhlé nápady. Obsahuje 3 tužky a 2 kuličková pera.', 10, 'true', 'http://iis.fhfstudio.com/SI/default_picture.png', 500, 27, 2, 38, '2015-12-07 18:48:59'),
(32, 'Guma na gumování Pepino', 'Po dlouholetých zkušenostech v oboru výroby velice kvalitní a spolehlivé pánské ochrany se rozhodla firma Pepino expandovat i na místní trh s papírnickými potřebami. 9 z 10 uživatelů doporučuje.', 300, 'true', 'http://iis.fhfstudio.com/SI/10.png', 30, 28, 2, 38, '2015-12-07 18:49:36'),
(33, 'Ořezávátko Martin', 'Jak již bylo nastíněno v názvu kategorie, i ta nejostřejší tužka jednou otupí. A přesně proto je tu Martin!', 20, 'true', 'http://iis.fhfstudio.com/SI/default_picture.png', 50, 29, 2, 38, '2015-12-07 18:50:05'),
(34, 'Kružítko Hana', 'Multifunkční kružítko Hana bodne ve všech situacích.', 50, 'true', 'http://iis.fhfstudio.com/SI/default_picture.png', 100, 31, 2, 38, '2015-12-07 18:50:42'),
(35, 'Mikrotužka Jaroslav', 'Mikrotužka Jaroslav je velice oblíbené zboží u studentů Stavební fakulty, nastavitelná šířka hrotu, přítlaku a jemnosti jsou ideální vlastnosti pro rýsovací potřebu stavařů.', 10, 'true', 'http://iis.fhfstudio.com/SI/default_picture.png', 75, 24, 2, 38, '2015-12-07 18:51:11'),
(36, 'Linkovaný sešit František', 'Sešit s Měkkou vazbou, nelinkovaný, 60 stránek', 0, 'false', 'http://iis.fhfstudio.com/SI/default_picture.png', 20, 32, 2, 39, '2015-12-07 18:51:39'),
(37, 'Batoh Dakine černý', 'Školní batoh od oblíbené firmy Dakine, v černém provedení.', 12, 'true', 'http://iis.fhfstudio.com/SI/11.png', 1150, 36, 2, 37, '2015-12-07 18:52:43'),
(38, 'Batoh Dakine modrý', 'Školní batoh od oblíbené firmy Dakine, v modrém provedení.', 12, 'true', 'http://iis.fhfstudio.com/SI/12.png', 1150, 36, 2, 37, '2015-12-07 18:53:05'),
(39, 'Penál Karel červený', 'Školní penál Karel, sloty pro 25 propisek či tužek, červené provedení.', 5, 'true', 'http://iis.fhfstudio.com/SI/default_picture.png', 64, 37, 2, 38, '2015-12-07 18:53:39'),
(40, 'Penál Karel modrý', 'Školní penál Karel, sloty pro 25 propisek či tužek, modré provedení.', 12, 'true', 'http://iis.fhfstudio.com/SI/default_picture.png', 64, 37, 2, 38, '2015-12-07 18:53:58'),
(41, 'Kapsář Filip tmavě růžový', 'Kapsář s kapacitou 15 litrů, zavěsitelný pod lavici, růžově obatikovaný.\r\n', 20, 'true', 'http://iis.fhfstudio.com/SI/default_picture.png', 50, 38, 2, 36, '2015-12-07 18:54:50'),
(42, 'Kapsář Filip tmavě modrý', 'Kapsář s kapacitou 15 litrů, zavěsitelný pod lavici, modře obatikovaný', 20, 'true', 'http://iis.fhfstudio.com/SI/default_picture.png', 50, 38, 2, 36, '2015-12-07 18:55:28'),
(43, 'Pevné desky A4, LANA', 'Pevné desky na A4 sešity a učebnice, motiv Lana Del Ray.', 20, 'true', 'http://iis.fhfstudio.com/SI/default_picture.png', 100, 39, 2, 39, '2015-12-07 18:55:58'),
(44, 'Sada obalů, 25ks', 'Obal na sešit nebo učebnici, A4, A5', 100, 'true', 'http://iis.fhfstudio.com/SI/default_picture.png', 50, 39, 2, 39, '2015-12-07 18:56:22'),
(45, 'Tempery 5 základních barev', '5 základních temperových barev - červená, modrá, zelená, žlutá a černá.', 12, 'true', 'http://iis.fhfstudio.com/SI/15.png', 50, 40, 2, 40, '2015-12-07 18:56:47'),
(46, 'Štětka Marie', 'Štětka Marie je ideální volba pro začínající malíře.', 23, 'true', 'http://iis.fhfstudio.com/SI/default_picture.png', 85, 41, 2, 40, '2015-12-07 18:57:25'),
(47, 'Modelína, 10 kusů, 5 barev', 'Skvělá tandemová modelína pro Vaše dvě ratolesti. Ale i jedináček si s tím jistě poradí!', 24, 'true', 'http://iis.fhfstudio.com/SI/16.png', 100, 42, 2, 40, '2015-12-07 18:57:57'),
(48, 'Skicák Pravdomil', 'Skicovací sešit nejen pro ty z Vás, kteří milují pravdu, ale i pro ty z Vás kteří milují kreslit a malovat. To je Pravdomil.', 100, 'true', 'http://iis.fhfstudio.com/SI/14.png', 75, 43, 2, 40, '2015-12-07 18:58:15'),
(49, 'Plátno Eliška', 'Skvělé a rovné plátno pro začínající, ale i zkušené malíře.', 1, 'true', 'http://iis.fhfstudio.com/SI/default_picture.png', 99.9, 44, 2, 40, '2015-12-07 18:58:50'),
(50, 'Stojan Dan', 'Skvělý stojan pro všechny příležitosti. Dodáván společně jedině s paletou Annou.', 1, 'true', 'http://iis.fhfstudio.com/SI/default_picture.png', 666, 45, 2, 40, '2015-12-07 18:59:24'),
(51, 'Paleta Anna', 'Skvělá a elegantní paleta pro všechny příležitosti. Dodávána jedině společně se stojanem Danem.', 1, 'true', 'http://iis.fhfstudio.com/SI/default_picture.png', 666000000, 45, 2, 40, '2015-12-07 18:59:56'),
(52, 'Sada fixů', 'Sada lehce vypratelných fixů.', 2, 'true', 'http://iis.fhfstudio.com/SI/13.png', 200, 26, 2, 40, '2015-12-07 19:00:19');

-- --------------------------------------------------------

--
-- Struktura tabulky `product_category`
--

CREATE TABLE IF NOT EXISTS `product_category` (
  `pcat_id` int(11) NOT NULL AUTO_INCREMENT,
  `pcat_name` varchar(255) NOT NULL,
  `pcat_description` text NOT NULL,
  PRIMARY KEY (`pcat_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=39 ;

--
-- Vypisuji data pro tabulku `product_category`
--

INSERT INTO `product_category` (`pcat_id`, `pcat_name`, `pcat_description`) VALUES
(34, 'Kancelářské potřeby', 'Potřeby pro Vaši kancelář'),
(35, 'Psací potřeby', 'Psací potřeby pro Vás i Vaše dítka'),
(36, 'Sešity, bloky', 'Máte-li už čím psát, můžete zvážit ještě výběr něčeho, do čeho budete psát!'),
(37, 'Školní vybavení', 'Chystáte se Vy, nebo Vaše ratolesti do školy? Vybavte se s námi!'),
(38, 'Umělecké vybavení', 'Nebo jste spíše umělecky zaměření? I tak u nás najdete co hledáte!');

-- --------------------------------------------------------

--
-- Struktura tabulky `product_subcategory`
--

CREATE TABLE IF NOT EXISTS `product_subcategory` (
  `psub_id` int(11) NOT NULL AUTO_INCREMENT,
  `psub_name` varchar(255) NOT NULL,
  `psub_description` text NOT NULL,
  `psub_category` int(11) NOT NULL,
  PRIMARY KEY (`psub_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=46 ;

--
-- Vypisuji data pro tabulku `product_subcategory`
--

INSERT INTO `product_subcategory` (`psub_id`, `psub_name`, `psub_description`, `psub_category`) VALUES
(20, 'Pořadače a šanony', 'Pořadače, eurofolie a šanony v žádné kanceláři nesmí chybět!', 34),
(21, 'Sešívačky a děrovačky', 'Pro efektivní práci i kratochvilné momenty se může sešívačka či děrovačka hodit!', 34),
(22, 'Nálepky a etikety', 'Snadnější a efektivnější rozdělení šanonů, nebo jen dekorace? Nálepky pomůžou!', 34),
(23, 'Složky', 'Pracovní složky, papírové obaly apod.', 34),
(24, 'Tužky', 'Tužky na psaní, rýsování i kreslení.', 35),
(25, 'Propisky', 'Psát nemusíte nutně jen perem.', 35),
(26, 'Fixy a popisovače', 'Potřebujete popsat CD, nebo zabavit dítě? Zkuste fixy!', 35),
(27, 'Psací sady', 'Nikdo neříká, že si musíte oblíbit pouze jednu propisku.', 35),
(28, 'Gumy', 'Ne, není to to, co si myslíte.', 35),
(29, 'Ořezávátka', 'I ta sebeostřejší tužka se jednou ztupí', 35),
(30, 'Pravítka', 'Rýsovat od ruky už dnes není v kurzu', 35),
(31, 'Kružítka', '', 35),
(32, 'Měkká vazba', '', 36),
(33, 'Pevná vazba', '', 36),
(34, 'Kroužková vazba', '', 36),
(35, 'Bloky', 'Bloky všeho druhu!', 36),
(36, 'Aktovky a batohy', 'Jakýpak školák by šel do školy bez dobrého batohu?', 37),
(37, 'Penály', '', 37),
(38, 'Kapsáře', '', 37),
(39, 'Desky a obaly na sešity', '', 37),
(40, 'Tempery a vodovky', '', 38),
(41, 'Štětce a štětky', '', 38),
(42, 'Modelovací hmoty', '', 38),
(43, 'Skicáky', '', 38),
(44, 'Plátna', '', 38),
(45, 'Stojany a palety', '', 38);

-- --------------------------------------------------------

--
-- Struktura tabulky `supplier`
--

CREATE TABLE IF NOT EXISTS `supplier` (
  `sup_id` int(11) NOT NULL AUTO_INCREMENT,
  `sup_name` varchar(255) NOT NULL,
  `sup_resupplytime` int(11) NOT NULL,
  `sup_ico` varchar(50) NOT NULL,
  `sup_enabled` set('true','false') NOT NULL,
  `sup_mail` varchar(255) NOT NULL,
  `sup_phone` varchar(50) NOT NULL,
  `sup_address` text NOT NULL,
  PRIMARY KEY (`sup_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=41 ;

--
-- Vypisuji data pro tabulku `supplier`
--

INSERT INTO `supplier` (`sup_id`, `sup_name`, `sup_resupplytime`, `sup_ico`, `sup_enabled`, `sup_mail`, `sup_phone`, `sup_address`) VALUES
(36, 'Pastelky Koh-i-noor', 1, '00000000', 'true', 'pastelky@kohinor.com', '722188199', 'Hradecká 325\r\nBrno 2\r\n637 00'),
(37, 'Kancelářské Potřeby Novák', 3, '00000000', 'true', 'kancl@novak.com', '', 'Barandov\r\nPraha 5\r\n666 01'),
(38, 'Psací potřeby Jarolím', 2, '00000000', 'true', 'jarolim@psaci.potreby.cz', '', 'Pražská \r\nBrno 5\r\n587 01'),
(39, 'Sešity Vojtěch', 10, '00000000', 'true', 'sesity.vojtech@seznam.cz', '', 'Frankfurtská 9\r\nVysoké Mýto\r\n566 01'),
(40, 'Umělecké potřeby Jandera', 3, '00000000', 'true', 'jandera.umelec@gmail.com', '', 'Smetanova 5\r\nLiptákov\r\n211 15');
