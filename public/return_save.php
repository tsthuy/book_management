<?php
// Kiểm tra xem nút "Lưu Phiếu Mượn" đã được nhấn chưa
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy thông tin từ form
    $maPhieuMuon = $_POST['maPhieuMuon'];
    $ngayTra = $_POST['ngayTra'];
    $phiPhat = $_POST['phiPhat'];
    $ttLucTra = $_POST['ttLucTra'];

    // Kết nối đến cơ sở dữ liệu
    require_once 'connect.php';

    // Cập nhật thông tin trả sách vào bảng phieumuon
    $query_update = "UPDATE phieumuon SET ngayTra = ?, phiPhat = ? WHERE maPhieuMuon = ?";
    $statement_update = $pdo->prepare($query_update);
    $statement_update->execute([$ngayTra, $phiPhat, $maPhieuMuon]);
    // set chitietphietmuon
    $query_ctpm = "UPDATE chitietphieumuon SET ttLucTra=? WHERE maPhieuMuon = ?";
    $statement_ctpm = $pdo->prepare($query_ctpm);
    $statement_ctpm->execute([$ttLucTra, $maPhieuMuon]);

    // Kiểm tra xem dữ liệu đã được cập nhật thành công hay không
    // Kiểm tra số dòng được ảnh hưởng bởi câu lệnh UPDATE trong bảng phieumuon
    $update_rows = $statement_update->rowCount();

    // Kiểm tra số dòng được ảnh hưởng bởi câu lệnh UPDATE trong bảng chitietphieumuon
    $ctpm_rows = $statement_ctpm->rowCount();

    // Kiểm tra xem số dòng được ảnh hưởng trong mỗi bảng và hiển thị thông báo tương ứng
    if ($update_rows > 0 || $ctpm_rows > 0) {
        echo "Thông tin trả sách đã được cập nhật thành công.";
    } else {
        echo "Có lỗi xảy ra khi cập nhật thông tin trả sách.";
    }
    header("Location: lichsumuontra.php");
}
