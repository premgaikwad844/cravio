-- Create employees table for employee login system
USE `login_db12`;

-- Create employees table
CREATE TABLE IF NOT EXISTS `employees` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL UNIQUE,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `role` enum('admin','employee') NOT NULL DEFAULT 'employee',
  `department` varchar(50) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default admin user (password: Cravio2025)
INSERT INTO `employees` (`username`, `password`, `full_name`, `email`, `role`, `department`) VALUES
('Cravio', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin User', 'admin@example.com', 'admin', 'Administration');

-- Insert employee "prem" with password "prem1924"
INSERT INTO `employees` (`username`, `password`, `full_name`, `email`, `role`, `department`) VALUES
('prem', '$2y$10$YourHashedPasswordHere', 'Prem Employee', 'prem@example.com', 'employee', 'Food Management');

-- Insert sample employee users (password: Employee2025)
INSERT INTO `employees` (`username`, `password`, `full_name`, `email`, `role`, `department`) VALUES
('john_doe', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'John Doe', 'john.doe@example.com', 'employee', 'Food Management'),
('jane_smith', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Jane Smith', 'jane.smith@example.com', 'employee', 'Content Management'),
('mike_wilson', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Mike Wilson', 'mike.wilson@example.com', 'employee', 'Data Entry');

-- Note: The password hash above is for demonstration. In production, use password_hash() function
-- Default passwords for testing:
-- Admin: Cravio2025
-- Employees: Employee2025
-- Prem: prem1924 