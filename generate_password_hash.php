<?php
// Utility script to generate password hash for prem user
$password = "prem1924";
$hash = password_hash($password, PASSWORD_DEFAULT);
echo "Password: " . $password . "\n";
echo "Hash: " . $hash . "\n";

// Also generate hash for admin password
$admin_password = "Cravio2025";
$admin_hash = password_hash($admin_password, PASSWORD_DEFAULT);
echo "\nAdmin Password: " . $admin_password . "\n";
echo "Admin Hash: " . $admin_hash . "\n";

// Generate hash for employee password
$emp_password = "Employee2025";
$emp_hash = password_hash($emp_password, PASSWORD_DEFAULT);
echo "\nEmployee Password: " . $emp_password . "\n";
echo "Employee Hash: " . $emp_hash . "\n";
?> 