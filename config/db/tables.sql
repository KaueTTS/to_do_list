/* MySql */
CREATE DATABASE to_do_list;
USE to_do_list;

CREATE TABLE `users` (
   `id` int NOT NULL AUTO_INCREMENT,
   `name` varchar(100) NOT NULL,
   `email` varchar(255) NOT NULL,
   `password` varchar(255) NOT NULL,
   `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
   PRIMARY KEY (`id`),
   UNIQUE KEY `email` (`email`)
)

CREATE TABLE `tasks` (
   `id` int NOT NULL AUTO_INCREMENT,
   `user_id` int NOT NULL,
   `title` varchar(100) NOT NULL,
   `description` varchar(255) DEFAULT NULL,
   `is_done` tinyint(1) DEFAULT '0',
   `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
   PRIMARY KEY (`id`),
   KEY `user_id` (`user_id`),
   CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
)