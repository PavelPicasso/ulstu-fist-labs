-- phpMyAdmin SQL Dump
-- version 4.0.10
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1:3306
-- Время создания: Ноя 05 2014 г., 13:21
-- Версия сервера: 5.5.38-log
-- Версия PHP: 5.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `plans`
--

-- --------------------------------------------------------

--
-- Структура таблицы `teachers`
--

DROP TABLE IF EXISTS `teachers`;
CREATE TABLE IF NOT EXISTS `teachers` (
  `CodeOfTeacher` int(11) NOT NULL,
  `TeacherName` varchar(80) NOT NULL,
  `TeacherSignature` varchar(80) DEFAULT NULL,
  `CodeOfDepart` int(11) NOT NULL,
  `Mail` varchar(60) DEFAULT NULL,
  `CodeOfUser` int(11) DEFAULT NULL,
  UNIQUE KEY `CodeOfTeacher` (`CodeOfTeacher`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

--
-- Дамп данных таблицы `teachers`
--

INSERT INTO `teachers` (`CodeOfTeacher`, `TeacherName`, `TeacherSignature`, `CodeOfDepart`, `Mail`, `CodeOfUser`) VALUES
(1, 'Негода Виктор Николаевич', 'В. Н. Негода', 1, '', NULL),
(2, 'Соснин Петр Иванович', 'П. И. Соснин', 1, '', NULL),
(5, 'Мартынов Антон Иванович', 'А. И. Мартынов', 1, '', NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
