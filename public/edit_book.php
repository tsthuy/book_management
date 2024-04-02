<?php
session_start(); // Khởi động session
$session_duration = 5; // 5 giây
session_set_cookie_params($session_duration);
require_once 'C:\xampp\New folder\htdocs\project\config\db_connect.php';
require_once 'C:\xampp\New folder\htdocs\project\model\book_management.php'; // Sử dụng tên lớp và namespace đúng

$db = new db_connect();
$connection = $db->connect();

// Khởi tạo đối tượng sách
$book = new \Book\sach($connection); // Sử dụng tên lớp và namespace đúng

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
    header("Location: ./book_manage.php");

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
    header("Location: ./book_manage.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Book</title>
    <!-- Thêm Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">

    <div class="container mx-auto py-8">

        <h1 class="text-3xl font-semibold text-center mb-8">Edit Book</h1>

        <?php
        if (isset($_SESSION['update_book_result'])) {
            echo "<p class='text-green-500 font-bold text-center mb-4'>{$_SESSION['update_book_result']}</p>";
            unset($_SESSION['update_book_result']);
        }
        ?>

        <!-- Form chỉnh sửa thông tin sách -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" class="max-w-lg mx-auto bg-white p-8 rounded-lg shadow-md">
            <input type="hidden" name="maSach" value="<?php echo $bookInfo['maSach']; ?>">
            <div class="mb-4">
                <label for="tenSach" class="block text-sm font-medium text-gray-700">Tên Sách:</label>
                <input type="text" name="tenSach" id="tenSach" value="<?php echo $bookInfo['tenSach']; ?>" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
            </div>
            <div class="mb-4">
                <label for="maTG" class="block text-sm font-medium text-gray-700">Mã Tác Giả:</label>
                <input type="text" name="maTG" id="maTG" value="<?php echo $bookInfo['maTG']; ?>" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
            </div>
            <div class="mb-4">
                <label for="maNXB" class="block text-sm font-medium text-gray-700">Mã Nhà Xuất Bản:</label>
                <input type="text" name="maNXB" id="maNXB" value="<?php echo $bookInfo['maNXB']; ?>" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
            </div>
            <div class="mb-4">
                <label for="maLoai" class="block text-sm font-medium text-gray-700">Mã Loại:</label>
                <input type="text" name="maLoai" id="maLoai" value="<?php echo $bookInfo['maLoai']; ?>" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
            </div>
            <div class="flex justify-center">
                <button type="submit" name="update" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-500 hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Update
                </button>
            </div>
        </form>
    </div>

</body>

</html>