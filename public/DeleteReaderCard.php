<?php
require_once __DIR__ . '/../src/bootstrap.php';
use QTDL\Project\ReaderCard;

// Kiểm tra xem có yêu cầu POST được gửi đi không và tồn tại tham số 'id'
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = $_POST['id'];
    
    // Tạo một thể hiện của lớp ReaderCard và tìm thẻ thư viện cần xoá
    $readerCard = new ReaderCard($PDO);
    $foundReaderCard = $readerCard->find($id);
    
    // Nếu thẻ thư viện được tìm thấy, thực hiện xoá
    if ($foundReaderCard) {
        if ($foundReaderCard->delete()) {
            // Nếu xoá thành công, chuyển hướng về trang danh sách thẻ thư viện
            header("Location: thethuvien.php");
            exit();
        } else {
            // Nếu có lỗi trong quá trình xoá, hiển thị thông báo lỗi
            echo "Đã xảy ra lỗi khi xoá thẻ thư viện.";
        }
    } else {
        // Nếu không tìm thấy thẻ thư viện, hiển thị thông báo lỗi
        echo "Không tìm thấy thẻ thư viện cần xoá.";
    }
} else {
    // Nếu không có yêu cầu POST hoặc không tồn tại tham số 'id', hiển thị thông báo lỗi
    echo "Yêu cầu không hợp lệ.";
}
