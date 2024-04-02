<?php
session_start(); // Khởi động session

require_once 'C:\xampp\New folder\htdocs\project\config\db_connect.php';
require_once 'C:\xampp\New folder\htdocs\project\model\book_management.php';

$db = new db_connect();
$connection = $db->connect();

// Khởi tạo đối tượng sách
$book = new \Book\sach($connection);

// Kiểm tra xác nhận chỉnh sửa sách
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy thông tin từ form chỉnh sửa sách
    $book->setMaSach($_POST['maSach']);
    $book->setTenSach($_POST['tenSach']);
    $book->setMaTG($_POST['maTG']);
    $book->setMaNXB($_POST['maNXB']);
    $book->setMaLoai($_POST['maLoai']);

    // Thực hiện cập nhật thông tin sách
    if ($book->update()) {
        // Nếu cập nhật thành công, đặt thông báo vào session
        $_SESSION['update_book_result'] = "Chỉnh sửa sách thành công.";
    } else {
        // Nếu cập nhật không thành công, đặt thông báo vào session
        $_SESSION['update_book_result'] = "Chỉnh sửa sách không thành công.";
    }

    // Chuyển hướng về trang index.php
    header("Location: book_manage.php");
    exit();
}

// Lấy thông tin sách từ cơ sở dữ liệu dựa trên mã sách
if (isset($_GET['maSach'])) {
    $maSach = $_GET['maSach'];
    $bookInfo = $book->getBookByMaSach($maSach);

    // Kiểm tra xem sách có tồn tại hay không
    if (!$bookInfo) {
        echo "Không tìm thấy sách.";
        exit();
    }
} else {
    // Nếu không có mã sách, chuyển hướng về trang index.php
    header("Location: book_manage.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style_book.css">
    <title>Edit Book</title>
</head>

<body>

    <h1>Edit Book</h1>

    <?php
    if (isset($_SESSION['update_book_result'])) {
        echo "<p>{$_SESSION['update_book_result']}</p>";
        unset($_SESSION['update_book_result']);
    }
    ?>

    <!-- Form chỉnh sửa thông tin sách -->
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        <input type="hidden" name="maSach" value="<?php echo $bookInfo['maSach']; ?>">
        <table>
            <tr>
                <th>Thông Tin</th>
                <th>Giá Trị Hiện Tại</th>
                <th>Chỉnh Sửa</th>
            </tr>
            <tr>
                <td>Tên Sách:</td>
                <td><?php echo $bookInfo['tenSach']; ?></td>
                <td><input type="text" name="tenSach" value="<?php echo $bookInfo['tenSach']; ?>"></td>
            </tr>
            <tr>
                <td>Mã Tác Giả:</td>
                <td><?php echo $bookInfo['maTG']; ?></td>
                <td><input type="text" name="maTG" value="<?php echo $bookInfo['maTG']; ?>"></td>
            </tr>
            <tr>
                <td>Mã Nhà Xuất Bản:</td>
                <td><?php echo $bookInfo['maNXB']; ?></td>
                <td><input type="text" name="maNXB" value="<?php echo $bookInfo['maNXB']; ?>"></td>
            </tr>
            <tr>
                <td>Mã Loại:</td>
                <td><?php echo $bookInfo['maLoai']; ?></td>
                <td><input type="text" name="maLoai" value="<?php echo $bookInfo['maLoai']; ?>"></td>
            </tr>
        </table>
        <button type="submit" name="update">Update</button>
    </form>
</body>

</html>