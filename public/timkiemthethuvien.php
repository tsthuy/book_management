<?php
// Kết nối đến cơ sở dữ liệu
require_once __DIR__ . '/../src/bootstrap.php';

// Kiểm tra xem có yêu cầu POST được gửi đi không
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['soThe'])) {
    // Lấy số thẻ từ dữ liệu gửi đi
    $soThe = $_GET['soThe'];

    // Chuẩn bị câu lệnh truy vấn SQL để tìm kiếm thông tin thẻ thư viện
    $sql = "CALL HienThiDanhSachTheThuVienTheoMa(:soThe)";
    
    // Chuẩn bị và thực thi statement
    $stmt = $PDO->prepare($sql);
    $stmt->bindParam(':soThe', $soThe);
    $stmt->execute();
    
    // Lấy kết quả trả về từ procedure
    $theThuVien = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Hiển thị thông tin thẻ thư viện dưới dạng bảng
    if ($theThuVien) {
        echo '<table class="table table-bordered">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>Chọn</th>'; // Ô cho phép lựa chọn mã thẻ
        echo '<th>Mã thẻ</th>';
        echo '<th>Ngày bắt đầu</th>';
        echo '<th>Ngày hết hạn</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        foreach ($theThuVien as $the) {
            echo '<tr>';
            echo '<td><input type="radio" name="selectedThe" value="' . $the['SoThe'] . '" onclick="selectThe(\'' . $the['SoThe'] . '\')"></td>'; // Ô radio cho phép lựa chọn mã thẻ
            echo '<td>' . $the['SoThe'] . '</td>';
            echo '<td>' . $the['NgayBatDau'] . '</td>';
            echo '<td>' . $the['NgayHetHan'] . '</td>';
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
    } else {
        echo "<p>Không tìm thấy thông tin về thẻ thư viện.</p>";
    }
} else {
    // Nếu không có yêu cầu hoặc dữ liệu không hợp lệ, hiển thị thông báo lỗi
    echo "<p>Yêu cầu không hợp lệ.</p>";
}
?>
