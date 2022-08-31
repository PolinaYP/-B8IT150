DROP DATABASE IF EXISTS Polina_Proj;

CREATE DATABASE Polina_Proj;

USE Polina_Proj;

CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `favourites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL,
  `movie_id` int(11) NOT NULL,
  `title` text NOT NULL,
  `poster` text,
  PRIMARY KEY (`id`),
  KEY `user` (`user`),
  CONSTRAINT `favourites_ibfk_1` FOREIGN KEY (`user`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

