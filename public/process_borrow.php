<?php
$maBanSao = $_POST['maBanSao'];
$maSach = $_POST['maSach'];
require_once 'connect.php';
$query_check_bansao_exist = "SELECT *
          FROM bansaosach WHERE maSach = ? and maBanSao = ?";
$statement_check_bansao = $pdo->prepare($query_check_bansao_exist);
$statement_check_bansao->execute([$maSach, $maBanSao]);
$banSaoExists_1 = $statement_check_bansao->fetch(PDO::FETCH_ASSOC);
if ($banSaoExists_1) {
    require_once 'connect.php';
    $query_check_bansao = "SELECT *
          FROM phieumuon pm
          INNER JOIN chitietphieumuon ctp ON pm.maPhieuMuon = ctp.maPhieuMuon
          WHERE pm.maSach = ? AND ctp.maBanSao =? AND ttLucTra is NULL";
    $statement_check_bansao = $pdo->prepare($query_check_bansao);
    $statement_check_bansao->execute([$maSach, $maBanSao]);
    $banSaoExists = $statement_check_bansao->fetch(PDO::FETCH_ASSOC);

    if ($banSaoExists) {
        echo "Bản sao sách này đã được mượn và chưa được trả. Không thể mượn lại.";
    } else {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Lấy thông tin từ form
            $maSach = $_POST['maSach'];
            $maPhieuMuon = $_POST['maPhieuMuon'];
            $ngayMuon = $_POST['ngayMuon'];
            $hanTra = $_POST['hanTra'];
            $maDocGia = $_POST['maDocGia'];
            $maBanSao = $_POST['maBanSao'];

            // Kết nối đến cơ sở dữ liệu
            require_once 'connect.php';

            // Thực hiện truy vấn để chèn dữ liệu vào bảng phieumuon
            $query_phieumuon = "INSERT INTO phieumuon (maPhieuMuon, ngayMuon, hanTra, maDocGia, maSach) VALUES (?, ?, ?, ?,?)";
            $statement_phieumuon = $pdo->prepare($query_phieumuon);
            $statement_phieumuon->execute([$maPhieuMuon, $ngayMuon, $hanTra, $maDocGia, $maSach]);

            // Kiểm tra xem dữ liệu đã được chèn thành công vào bảng phieumuon hay không
            if ($statement_phieumuon->rowCount() > 0) {
                echo "Thông tin phiếu mượn đã được lưu thành công.";
            } else {
                echo "Có lỗi xảy ra khi lưu thông tin phiếu mượn.";
            }

            // Thực hiện truy vấn để chèn dữ liệu vào bảng chitietphieumuon
            $query_chitietphieumuon = "INSERT INTO chitietphieumuon (maPhieuMuon, maBanSao) VALUES (?, ?)";
            $statement_chitietphieumuon = $pdo->prepare($query_chitietphieumuon);
            $statement_chitietphieumuon->execute([$maPhieuMuon, $maBanSao]);

            // Kiểm tra xem dữ liệu đã được chèn thành công vào bảng chitietphieumuon hay không
            if ($statement_chitietphieumuon->rowCount() > 0) {
                echo "Thông tin chi tiết phiếu mượn đã được lưu thành công.";
            } else {
                echo "Có lỗi xảy ra khi lưu thông tin chi tiết phiếu mượn.";
            }
            header("Location: lichsumuontra.php");
        }
    }
} else {
    echo "Bản sao không tồn tại";
}
