-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Počítač: localhost
-- Vygenerováno: Pát 06. lis 2015, 19:39
-- Verze serveru: 5.5.46-0ubuntu0.14.04.2
-- Verze PHP: 5.5.9-1ubuntu4.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Databáze: `iisproject`
--
CREATE DATABASE IF NOT EXISTS `iisproject` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `iisproject`;

-- --------------------------------------------------------

--
-- Struktura tabulky `customer`
--

DROP TABLE IF EXISTS `customer`;
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

DROP TABLE IF EXISTS `customer_hash`;
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

DROP TABLE IF EXISTS `employee`;
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

DROP TABLE IF EXISTS `employee_hash`;
CREATE TABLE IF NOT EXISTS `employee_hash` (
  `ehash_id` int(11) NOT NULL AUTO_INCREMENT,
  `ehash_value` varchar(255) NOT NULL,
  `ehash_time` datetime NOT NULL,
  `ehash_employee` int(11) NOT NULL,
  PRIMARY KEY (`ehash_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=19 ;

--
-- Vypisuji data pro tabulku `employee_hash`
--

INSERT INTO `employee_hash` (`ehash_id`, `ehash_value`, `ehash_time`, `ehash_employee`) VALUES
(10, '6c5aa9d9da692f3c9f60a31a7004750d', '2015-10-28 22:09:33', 1),
(4, '2b5c45c58a76b6db66b56791d80a3a58', '2015-10-28 19:22:06', 1),
(3, '1e379ee7b8324e1224c8f8b66889a7db', '2015-10-28 17:17:12', 1),
(14, '1c3a7066f7befff2f6f0ec36771e422b', '2015-10-30 14:41:56', 1),
(18, 'c820f33bae689da70ac8876bb77a8d61', '2015-11-06 19:25:26', 1);

-- --------------------------------------------------------

--
-- Struktura tabulky `employee_role`
--

DROP TABLE IF EXISTS `employee_role`;
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

-- --------------------------------------------------------

--
-- Struktura tabulky `product_maincategory`
--

DROP TABLE IF EXISTS `product_maincategory`;
CREATE TABLE IF NOT EXISTS `product_maincategory` (
  `pmc_id` int(11) NOT NULL AUTO_INCREMENT,
  `pmc_name` varchar(255) NOT NULL,
  `pmc_description` text NOT NULL,
  PRIMARY KEY (`pmc_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Vypisuji data pro tabulku `product_maincategory`
--

INSERT INTO `product_maincategory` (`pmc_id`, `pmc_name`, `pmc_description`) VALUES
(1, 'Kancelářské potřeby', 'Potřeby pro kancelář najdete přesně v této kategorii!'),
(2, 'Psací potřeby', 'Vše co potřebujete pro Vaše klidné psaní...'),
(3, 'Sešity, bloky', 'Máte-li už čím psát, můžete zvážit ještě výběr něčeho, DO čeho budete psát!'),
(4, 'Školní vybavení', 'Chystáte se Vy, nebo Vaše ratolesti do školy? Vybavte se s námi!'),
(5, 'Umělecké vybavení', 'Nebo jste spíše umělecky zaměření? I tak u nás najdete co hledáte!');

-- --------------------------------------------------------

--
-- Struktura tabulky `product_sidecategory`
--

DROP TABLE IF EXISTS `product_sidecategory`;
CREATE TABLE IF NOT EXISTS `product_sidecategory` (
  `psc_id` int(11) NOT NULL AUTO_INCREMENT,
  `psc_name` varchar(255) NOT NULL,
  `psc_description` text NOT NULL,
  `psc_maincategory` int(11) NOT NULL,
  PRIMARY KEY (`psc_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=28 ;

--
-- Vypisuji data pro tabulku `product_sidecategory`
--

INSERT INTO `product_sidecategory` (`psc_id`, `psc_name`, `psc_description`, `psc_maincategory`) VALUES
(1, 'Pořadače a šanony', 'Pořadače, eurofolie a šanony v žádné kanceláři nesmí chybět!', 1),
(2, 'Sešívačky a děrovačky', 'Pro efektivní práci i kratochvilné momenty se může sešívačka či děrovačka hodit!', 1),
(3, 'Nálepky a etikety', 'Snadnější a efektivnější rozdělení šanonů, nebo jen dekorace? Nálepky pomůžou!', 1),
(4, 'Složky', 'Pracovní složky, papírové obaly apod.', 1),
(5, 'Tužky', 'Tužky na psaní, rýsování i kreslení.', 2),
(6, 'Propisky', 'Psát nemusíte nutně jen perem.', 2),
(7, 'Fixy a popisovače', 'Potřebujete popsat CD, nebo zabavit dítě? Zkuste fixy!', 2),
(8, 'Psací sady', 'Nikdo neříká, že si musíte oblíbit pouze jednu propisku.', 2),
(9, 'Gumy', 'Ne, není to to, co si myslíte.', 2),
(10, 'Ořezávátka', 'I ta sebeostřejší tužka se jednou ztupí', 2),
(11, 'Pravítka', 'Rýsovat od ruky už dnes není v kurzu', 2),
(12, 'Kružíka', 'Natož potom vytvářet kruhy od ruky.', 2),
(13, 'Mikrotužky', 'Protože na velikosti záleží!', 2),
(14, 'Měkká vazba', 'Měkce vázané sešity', 3),
(15, 'Pevná vazba', 'Pevně vázané sešity', 3),
(16, 'Kroužková vazba', 'Poslední dobou kroužky prý také frčí! Najdete je i u nás!', 3),
(17, 'Bloky', 'Bloky všeho druhu', 3),
(18, 'Aktovky a batohy', 'Jakýpak školák by šel do školy bez dobrého batohu?', 4),
(19, 'Penály', 'Dokud nedojde Vaše ratolest na VŠ, určitě penál ocení.', 4),
(20, 'Kapsáře', 'Prvňáčci toto budou potřebovat nejvíce', 4),
(21, 'Desky a obaly na sešity', 'Pečlivě sledujte své dítě, je-li to pečlivák a podstivec, obaly na sešity mu určitě udělají radost.', 4),
(22, 'Tempery a vodovky', 'Vše pro kresbu na papír či plátno', 5),
(23, 'Štětce a štětky', 'Protože mít tempery nestačí!', 5),
(24, 'Modelovací hmoty', 'Modelína rozvíjí dětskou tvořivost a modurit ji zvěčňuje.', 5),
(25, 'Skicáky', 'Bloky na skicy', 5),
(26, 'Plátna', 'Když svou práci chcete zvěčnit na plátno', 5),
(27, 'Stojany a palety', 'Co by to bylo za malíře bez své palety a stojanu na plátno? Různé velikosti.', 5);
