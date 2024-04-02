<?php

require_once '../../config/db_connect.php';
require_once '../../model/book_copy_management.php';

session_start();

// Kiểm tra xem mã bản sao đã được truyền qua URL hay chưa
if (!isset($_GET['maBanSao'])) {
    $_SESSION['delete_copy_result'] = "Không tìm thấy mã bản sao.";
    header("Location: ./book_copy.php");
    exit();
}

// Lấy mã bản sao từ URL
$maBanSao = $_GET['maBanSao'];

// Khởi tạo kết nối đến cơ sở dữ liệu
$db = new db_connect();
$connection = $db->connect();

// Khởi tạo đối tượng quản lý bản sao sách
$bookcopy = new BookCopy\bansaosach($connection);

// Thiết lập mã bản sao cần xóa
$bookcopy->setmaBanSao($maBanSao);

// Thực hiện xóa bản sao sách
if ($bookcopy->deleteCopy()) {
    $_SESSION['delete_copy_result'] = "Xóa bản sao sách thành công.";
} else {
    $_SESSION['delete_copy_result'] = "Xóa bản sao sách không thành công.";
}

// Chuyển hướng về trang danh sách bản sao sách
header("Location: ./book_copy.php");
exit();
