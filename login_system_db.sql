--
-- Table structure for table `access_levels`
--

DROP TABLE IF EXISTS `access_levels`;
CREATE TABLE `access_levels` (
  `access_level_id` int NOT NULL AUTO_INCREMENT,
  `access_level_name` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`access_level_id`)
);

--
-- Table structure for table `task_status`
--

DROP TABLE IF EXISTS `task_status`;
CREATE TABLE `task_status` (
  `task_status_id` int NOT NULL AUTO_INCREMENT,
  `task_status_name` varchar(255) NOT NULL,
  PRIMARY KEY (`task_status_id`)
);

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `user_username` varchar(45) DEFAULT NULL,
  `user_firstname` varchar(45) DEFAULT NULL,
  `user_lastname` varchar(45) DEFAULT NULL,
  `user_fullname` varchar(100) DEFAULT NULL,
  `user_email` varchar(45) DEFAULT NULL,
  `user_password_hash` varchar(500) DEFAULT NULL,
  `user_access_level_id` int DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  CONSTRAINT `fk_user_access_level` FOREIGN KEY (`user_access_level_id`) REFERENCES `access_levels` (`access_level_id`)
);

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `sessions_id` int NOT NULL AUTO_INCREMENT,
  `sessions_token` varchar(45) DEFAULT NULL,
  `sessions_serial` varchar(45) DEFAULT NULL,
  `sessions_datetime` varchar(40) DEFAULT NULL,
  `sessions_userid` int DEFAULT NULL,
  PRIMARY KEY (`sessions_id`),
  CONSTRAINT `fk_sessions_user` FOREIGN KEY (`sessions_userid`) REFERENCES `users` (`user_id`)
);

--
-- Table structure for table `tasks`
--

DROP TABLE IF EXISTS `tasks`;
CREATE TABLE `tasks` (
  `task_id` int NOT NULL AUTO_INCREMENT,
  `task_name` varchar(500) DEFAULT NULL,
  `task_description` varchar(500) DEFAULT NULL,
  `task_creation_date` varchar(19) DEFAULT NULL,
  `task_init_date` varchar(19) DEFAULT NULL,
  `task_conclusion_date` varchar(19) DEFAULT NULL,
  `task_status_id` int DEFAULT NULL,
  `task_user_id` int DEFAULT NULL,
  PRIMARY KEY (`task_id`),
  KEY `task_status_id` (`task_status_id`),
  KEY `fk_task_user` (`task_user_id`),
  CONSTRAINT `fk_task_user` FOREIGN KEY (`task_user_id`) REFERENCES `users` (`user_id`),
  CONSTRAINT `fk_task_status` FOREIGN KEY (`task_status_id`) REFERENCES `task_status` (`task_status_id`)
);
