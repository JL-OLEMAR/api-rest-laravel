-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 30-09-2020 a las 16:43:48
-- Versión del servidor: 8.0.18
-- Versión de PHP: 7.4.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `api_rest_laravel`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `categories`
--

INSERT INTO `categories` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Ordenadores', '2020-09-23 00:00:00', '2020-09-30 14:35:02'),
(2, 'Móviles y Tablets', '2020-09-23 00:00:00', '2020-09-30 14:26:22'),
(3, 'VideosJuegs', '2020-09-27 14:39:51', '2020-09-27 14:55:57'),
(4, 'Laptops', '2020-09-30 14:24:40', '2020-09-30 14:24:40');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `posts`
--

DROP TABLE IF EXISTS `posts`;
CREATE TABLE IF NOT EXISTS `posts` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `user_id` int(255) NOT NULL,
  `category_id` int(255) NOT NULL,
  `title` varchar(255) COLLATE utf8_spanish2_ci NOT NULL,
  `content` text COLLATE utf8_spanish2_ci NOT NULL,
  `image` varchar(255) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_post_user` (`user_id`),
  KEY `fk_post_category` (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `posts`
--

INSERT INTO `posts` (`id`, `user_id`, `category_id`, `title`, `content`, `image`, `created_at`, `updated_at`) VALUES
(1, 1, 3, 'Galaxy A30', 'Contenido Samsung Galaxy A30', 'Galaxy.jpeg', '2020-09-23 00:00:00', '2020-09-29 13:20:42'),
(2, 1, 3, 'PesFutbol 2020', 'Contenido PesFutbol 2020', NULL, '2020-09-23 00:00:00', '2020-09-23 00:00:00'),
(3, 1, 1, 'Workstation', 'Contenido Workstation', NULL, '2020-09-23 00:00:00', '2020-09-23 00:00:00'),
(5, 4, 2, 'Samsung Galaxy A20s', 'Contenido Samsung Galaxy A20s', 'Samsung Galaxy A20sjpeg', '2020-09-29 04:51:37', '2020-09-30 15:37:17'),
(12, 3, 3, 'Galaxy A30s', 'Contenido Samsung Galaxy A30s', 'Galaxy.jpeg', '2020-09-29 14:05:27', '2020-09-30 07:54:26');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `surname` varchar(100) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `role` varchar(20) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_spanish2_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_spanish2_ci NOT NULL,
  `description` text COLLATE utf8_spanish2_ci,
  `image` varchar(255) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `remember_token` varchar(255) COLLATE utf8_spanish2_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `surname`, `role`, `email`, `password`, `description`, `image`, `created_at`, `updated_at`, `remember_token`) VALUES
(1, 'admin', 'admin', 'ROLE_ADMIN', 'admin@admin.com', 'admin', 'description admin', NULL, '2020-09-23 00:00:00', '2020-09-23 00:00:00', NULL),
(3, 'jose', 'olemar', 'ROLE_USER', 'jose@ole.com', '1ec4ed037766aa181d8840ad04b9fc6e195fd37dedc04c98a5767a67d3758ece', 'Desc. JoseOlemar', '1601468213545f07ae16344a508a90d3b68c6a7688.jpg', '2020-09-25 10:35:28', '2020-09-30 07:32:35', NULL),
(4, 'luis', 'olemar', 'ROLE_USER', 'luis@ole.com', 'c5ff177a86e82441f93e3772da700d5f6838157fa1bfdc0bb689d7f7e55e7aba', NULL, NULL, '2020-09-25 10:53:59', '2020-09-25 10:53:59', NULL),
(5, 'irving', 'garcia', 'ROLE_USER', 'irving@garcia.com', '1ec4ed037766aa181d8840ad04b9fc6e195fd37dedc04c98a5767a67d3758ece', NULL, NULL, '2020-09-25 11:22:53', '2020-09-27 02:49:39', NULL),
(7, 'jos', 'jos', 'ROLE_USER', 'jos@jos.com', 'f854b2aeb789d616b26acff4df9ff7741d09d5f7a63add238201880e5b10bf2b', 'des.jos', '1601472642Galaxy S10.jpg', '2020-09-30 14:02:45', '2020-09-30 14:07:26', NULL);

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `fk_post_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `fk_post_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
