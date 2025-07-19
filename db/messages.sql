CREATE TABLE `messages` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `sender` varchar(50) NOT NULL,
  `recipient` varchar(50) NOT NULL,
  `text` varchar(255) NOT NULL,
  `score` int(2) DEFAULT NULL,
  PRIMARY KEY (`id`)
);
