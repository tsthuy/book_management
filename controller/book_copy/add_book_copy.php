<?php
require_once '../../config/db_connect.php';
require_once '../../model/book_copy_management.php';

session_start();

$db = new db_connect();
$connection = $db->connect();

// Khởi tạo đối tượng quản lý bản sao sách
$bookcopy = new BookCopy\bansaosach($connection);

// Kiểm tra xem maSach đã được gửi qua biểu mẫu hay chưa
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['maSach']) && isset($_POST['maNXB'])) {
    $maSach = $_POST['maSach'];
    $maNXB = $_POST['maNXB'];
    // Tiếp tục xử lý biến maSach ở đây
    if (
        isset($_POST['maSach']) && isset($_POST['maBanSao'])
        && isset($_POST['namXB']) && isset($_POST['ttMuon']) && isset($_POST['maNXB'])
    ) {
        // Lấy thông tin từ biểu mẫu
        // $maSach = $_POST['maSach'];
        $maBanSao = $_POST['maBanSao'];
        $namXB = $_POST['namXB'];
        $ttMuon = $_POST['ttMuon'];
        // $maNXB = $_POST['maNXB'];

        // Thiết lập thông tin cho đối tượng bản sao sách
        $bookcopy->setmaSach($maSach);
        $bookcopy->setmaBanSao($maBanSao);
        $bookcopy->setNamXB($namXB);
        $bookcopy->setTtMuon($ttMuon);
        $bookcopy->setMaNXB($maNXB);

        // Thực hiện thêm bản sao sách
        if ($bookcopy->addCopy()) {
            $_SESSION['add_copy_result'] = "Thêm bản sao sách thành công.";
        } else {
            $_SESSION['add_copy_result'] = "Thêm bản sao sách thất bại.";
        }

        // Chuyển hướng người dùng về trang book_copy.php để hiển thị thông báo
        header("Location: ./book_copy.php");
    }
}
