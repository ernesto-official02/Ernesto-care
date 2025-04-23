-- Create admins table if it doesn't exist
CREATE TABLE IF NOT EXISTS `admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_login` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default admin user if not exists
INSERT INTO `admins` (`username`, `password`, `email`, `full_name`) 
SELECT 'admin', '$2y$10$8KzQ8IzAF9tXBiPqVXqYQOQZQZQZQZQZQZQZQZQZQZQZQZQZQZQ', 'admin@ernesto-health.com', 'System Administrator'
WHERE NOT EXISTS (SELECT 1 FROM `admins` WHERE `username` = 'admin'); 