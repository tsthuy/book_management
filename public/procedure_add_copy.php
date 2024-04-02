<?php
require_once '../../config/db_connect.php';
require_once '../../model/book_copy_management.php';

$db = new db_connect();
$connection = $db->connect();

$bookcopy = new BookCopy\bansaosach($connection);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['maSach'])) {
    $maSach = $_POST['maSach'];
    $maBanSao = $_POST['maBanSao']; // Thêm các biến còn thiếu
    $namXB = $_POST['namXB'];
    $ttMuon = $_POST['ttMuon'];
    $maNXB = $_POST['maNXB']; // Thêm biến maNXB

    $sql = "CALL thembansaosach(:maSach, :maBanSao, :namXB, :ttMuon, :maNXB)"; // Sử dụng dấu hai chấm trước tên tham số

    $statement = $connection->prepare($sql); // Sử dụng biến connection thay vì $this->connect

    // Bind các tham số vào câu lệnh
    $statement->bindParam(':maSach', $maSach);
    $statement->bindParam(':maBanSao', $maBanSao);
    $statement->bindParam(':namXB', $namXB);
    $statement->bindParam(':ttMuon', $ttMuon);
    $statement->bindParam(':maNXB', $maNXB);

    $statement->execute();
}
