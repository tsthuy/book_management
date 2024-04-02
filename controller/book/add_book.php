<?php
session_start(); // Khởi động session

require_once 'C:\xampp\New folder\htdocs\project\config\db_connect.php';
require_once 'C:\xampp\New folder\htdocs\project\model\book_management.php';

// Kết nối đến cơ sở dữ liệu
$db = new db_connect();
$connection = $db->connect();

// Khởi tạo đối tượng sách
$book = new Book\sach($connection);

// Thực hiện thêm sách
if (
    isset($_POST['maSach']) && isset($_POST['tenSach'])
    && isset($_POST['maTG']) && isset($_POST['maNXB']) && isset($_POST['maLoai'])
) {
    $book->setMaSach($_POST['maSach']);
    $book->setTenSach($_POST['tenSach']);
    $book->setMaTG($_POST['maTG']);
    $book->setMaNXB($_POST['maNXB']);
    $book->setMaLoai($_POST['maLoai']);

    if ($book->create()) {
        $_SESSION['add_book_result'] = "Sách đã được thêm thành công.";
    } else {
        $_SESSION['add_book_result'] = "Không thể thêm sách. Vui lòng thử lại.";
    }

    // Chuyển hướng về trang index.php sau khi thực hiện thêm sách
    header("Location: book_manage.php");
}
