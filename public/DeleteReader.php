<?php
require_once __DIR__ . '/../src/bootstrap.php';

use QTDL\Project\Reader;

// Kiểm tra xem có yêu cầu POST được gửi đi không và có tồn tại ID của độc giả không
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    // Khởi tạo đối tượng Reader với PDO
    $reader = new Reader($PDO);

    // Lấy ID của độc giả từ yêu cầu POST
    $id = $_POST['id'];

    // Tìm độc giả trong cơ sở dữ liệu
    $existingReader = $reader->find($id);

    // Kiểm tra xem độc giả có tồn tại không
    if ($existingReader) {
        // Thực hiện xóa độc giả
        if ($existingReader->delete()) {
            // Nếu xóa thành công, chuyển hướng trở lại trang danh sách độc giả
            header("Location: index.php");
            exit();
        } else {
            // Nếu xóa không thành công, hiển thị thông báo lỗi
            echo "Đã xảy ra lỗi khi xóa độc giả.";
        }
    } else {
        // Nếu không tìm thấy độc giả, hiển thị thông báo lỗi
        echo "Không tìm thấy độc giả để xóa.";
    }
} else {
    // Nếu không có yêu cầu POST hoặc không có ID, chuyển hướng trở lại trang danh sách độc giả
    header("Location: index.php");
    exit();
}
