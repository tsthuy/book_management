<?php
session_start(); // Khởi động session

require_once 'C:\xampp\New folder\htdocs\project\config\db_connect.php';
require_once 'C:\xampp\New folder\htdocs\project\model\book_management.php';

// Kiểm tra xác nhận xóa sách
if (isset($_GET['maSach'])) {
    // Lấy mã sách từ URL
    $maSach = $_GET['maSach'];

    // Kết nối đến cơ sở dữ liệu
    $db = new db_connect();
    $connection = $db->connect();

    // Khởi tạo đối tượng sách
    $book = new Book\sach($connection);

    // Xóa sách
    $book->setMaSach($maSach);

    if ($book->delete()) {
        $_SESSION['delete_book_result'] = "Xóa sách thành công.";
    } else {
        $_SESSION['delete_book_result'] = "Xóa sách không thành công.";
    }

    // Chuyển hướng về trang index.php
    header("Location: book_manage.php");
    exit();
} else {
    // Nếu không có mã sách được cung cấp, chuyển hướng người dùng về trang index.php
    header("Location: book_manage.php");
    exit();
}
