<?php
define('HOST', 'localhost');
define('USERNAME', 'root');
define('PASSWORD', '');
define('DATABASE', 'shop_quanao');
?>
<?php
// Database Configuration
$host = 'localhost';      // MySQL host
$username = 'root';       // MySQL username 
$password = '';          // MySQL password
$database = 'shop_quanao';  // Tên database của bạn

// Optional: Cấu hình timezone
date_default_timezone_set('Asia/Ho_Chi_Minh');

// Optional: Cấu hình session
ini_set('session.cookie_lifetime', 60 * 60 * 24 * 30); // 30 days
ini_set('session.gc_maxlifetime', 60 * 60 * 24 * 30); // 30 days

// Error reporting (chỉ dùng khi development)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Các hằng số khác nếu cần
define('BASE_URL', 'http://localhost/Web');
?>