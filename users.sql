-- Adminer 4.6.2 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reg_num` bigint(20) NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mid_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `class` int(11) NOT NULL,
  `gender` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dob` date NOT NULL,
  `mobile` bigint(20) NOT NULL,
  `father` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `present_address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_img` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `users` (`id`, `reg_num`, `first_name`, `mid_name`, `last_name`, `class`, `gender`, `dob`, `mobile`, `father`, `present_address`, `user_img`, `created`, `modified`, `password`) VALUES
(1,	1606342450,	'SK',	'Middle-Name',	'Webs',	0,	'Gender',	'2020-01-01',	9999888800,	'Father',	'At - , Post- , Dist- , State- , Pin Code- XXXXXX',	'1606342450-20201126_034410.png',	'2020-11-27 11:58:19',	'2020-11-25 22:14:10',	'$2y$10$zgJt0sOOG2L./7V7FeLtDOIhE0Kvmqxszlk5u..otx65nj/Kw.lWu');

-- 2020-11-27 12:04:33
