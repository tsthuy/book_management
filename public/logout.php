<?php
// Xử lý logout
session_start(); // Bắt đầu session
if (isset($_SESSION['loggin'])) {
    unset($_SESSION['loggin']); // Xóa session 'loggin'
}
session_destroy(); // Hủy session

// Chuyển hướng đến trang chủ sau khi logout
header("Location: home.php"); // Thay 'index.php' bằng đường dẫn mong muốn
exit;
