-- phpMyAdmin SQL Dump
-- version 4.0.10
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1:3306
-- Время создания: Ноя 05 2014 г., 12:17
-- Версия сервера: 5.5.38-log
-- Версия PHP: 5.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- База данных: `plans`
--

-- --------------------------------------------------------

--
-- Структура таблицы `metausers`
--

DROP TABLE IF EXISTS `metausers`;
CREATE TABLE IF NOT EXISTS `metausers` (
  `CodeOfUser` int(11) unsigned NOT NULL,
  `login` varchar(20) DEFAULT '0',
  `pass` varchar(20) DEFAULT '0',
  `status` char(3) DEFAULT NULL,
  `statusCode` int(11) DEFAULT NULL,
  PRIMARY KEY (`CodeOfUser`),
  UNIQUE KEY `CodeOfUser` (`CodeOfUser`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

--
-- Дамп данных таблицы `metausers`
--

INSERT INTO `metausers` (`CodeOfUser`, `login`, `pass`, `status`, `statusCode`) VALUES
(1, 'vsbk', 'wwBJbxkVSkl1o', 'uc', NULL),
(2, 'nvn', '22SeiY1TsoJZU', 'uc', 1),
(3, 'anya', '22E22dvU5nQk6', 'uc', 1),
(4, 'edudep', '23uxmIGQV6keU', 'uc', 5),
(5, 'islog', '22le4kOB5sUss', 'kaf', 2),
(6, 'ivklog', '22C3mVACsiQ.k', 'kaf', 27),
(7, 'user', '22QsW1gRCcd2I', 'dec', 1),
(8, 'korolek', '23zFSjPVwduE2', 'uc', 2),
(9, 'ann', '22QsW1gRCcd2I', 'uc', NULL);