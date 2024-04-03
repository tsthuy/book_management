<?php
session_start(); // Khởi động session

require_once 'C:\xampp\New folder\htdocs\project\config\db_connect.php';
require_once 'C:\xampp\New folder\htdocs\project\model\book_management.php';

// Kết nối đến cơ sở dữ liệu
$db = new db_connect();
$connection = $db->connect();

// Khởi tạo đối tượng sách
$book = new Book\sach($connection);

// Kiểm tra nếu người dùng nhấn nút xóa sách
if (isset($_GET['delete_id'])) {
    header("Location: ./delete_book.php?maSach=" . $_GET['delete_id']);
    exit();
}



// Kiểm tra nếu người dùng muốn chỉnh sửa sách
if (isset($_GET['update'])) {
    header("Location: ./edit_book.php?maSach=" . $_GET['update']);
    exit();
}



// Kiểm tra nếu người dùng tìm kiếm sách
$searchResults = [];
if (isset($_GET['search'])) {
    $searchResults = $book->search();
} else {
    $searchResults = $book->read();
}
//sql nang cao
require_once __DIR__ . '/../src/bootstrap.php';
$sql = "SELECT total_books_count() AS total_books";
$stmt = $PDO->prepare($sql);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$total_books = $result['total_books'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management</title>
    <!-- Thêm Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="font-sans bg-white">

    <!-- Header -->
    <header class="bg-gray-800 text-white py-4">
        <div class="container mx-auto flex justify-between items-center px-4">
            <a href="home.php" class="text-2xl font-bold">Hệ thống quản lý thư viện</a>
            <form action="home.php" method="GET" class="flex items-center">
                <input type="text" name="keyword" placeholder="Tìm kiếm sách" class="p-2 border rounded-md mr-2 text-black">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md">Tìm kiếm</button>
            </form>
            <div class="relative">
                <select onchange="location = this.value;" class="block appearance-none w-full bg-blue-500 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded-md shadow leading-tight focus:outline-none focus:bg-black focus:border-gray-500">
                    <option selected disabled>Chọn thể loại sách</option>
                    <?php foreach ($categories as $category) : ?>
                        <option value="home.php?category=<?php echo urlencode($category['tenLoai']); ?>"><?php echo $category['tenLoai']; ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path d="M14.293 7.293a1 1 0 0 0-1.414-1.414l-3 3a1 1 0 0 0-.001 1.415l3 3a1 1 0 0 0 1.415-1.415L11.414 11H16a1 1 0 0 0 0-2h-4.586l2.293-2.293z" />
                    </svg>
                </div>
            </div>
            <a href="index.php" class="hover:text-gray-300">Độc giả</a>
            <a href="thethuvien.php" class="hover:text-gray-300">Thẻ thư viện</a>
            <nav>
                <ul class="flex space-x-4">
                    <li><a href="book_manage.php" class="hover:text-gray-300">Quản lý sách</a></li>
                    <li><a href="return.php" class="hover:text-gray-300">Trả sách</a></li>
                    <li><a href="lichsumuontra.php" class="hover:text-gray-300">Lịch sử mượn trả</a></li>
                    <li><a href="login.php" class="hover:text-gray-300">
                            <?php
                            if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
                                echo '<a href="Logout.php">Log out</a>';
                            } else {
                                echo '<a href="login.php">Đăng Nhập</a>';
                            }
                            ?>
                        </a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="container mx-auto px-4 py-8">

        <!-- Form tìm kiếm sách -->
        <form action="book_manage.php" method="GET" class="mb-4">
            <a href="book_copy.php" class="bg-green-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md m-4 ">Quản Lý Sách Copy</a>
            <input type="text" name="search" placeholder="Search" class="p-2 border rounded-md mr-2 text-black">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md">Search</button>
        </form>

        <!-- Danh sách sách -->

        <h2 class="text-2xl font-bold mb-4">Books( <?php echo "Tổng số lượng sách: " . $total_books; ?> )</h2>
        <div class="overflow-x-auto">
            <table class="w-full table-auto">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="px-4 py-2">Mã Sách</th>
                        <th class="px-4 py-2">Tên Sách</th>
                        <th class="px-4 py-2">Mã Tác Giả</th>
                        <th class="px-4 py-2">Mã Nhà Xuất Bản</th>
                        <th class="px-4 py-2">Mã Loại</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($searchResults as $result) : ?>
                        <tr>
                            <td class="border px-4 py-2"><?php echo $result['maSach']; ?></td>
                            <td class="border px-4 py-2"><?php echo $result['tenSach']; ?></td>
                            <td class="border px-4 py-2"><?php echo $result['maTG']; ?></td>
                            <td class="border px-4 py-2"><?php echo $result['maNXB']; ?></td>
                            <td class="border px-4 py-2"><?php echo $result['maLoai']; ?></td>
                            <td class="border px-4 py-2">
                                <a href="book_manage.php?delete_id=<?php echo $result['maSach']; ?>" onclick="return confirm('Are you sure?')" class="text-red-500">Delete</a>
                                <span class="px-2">|</span>
                                <a href="book_manage.php?update=<?php echo $result['maSach']; ?>&tenSach=<?php echo urlencode($result['tenSach']); ?>&maTG=<?php echo $result['maTG']; ?>&maNXB=<?php echo $result['maNXB']; ?>&maLoai=<?php echo $result['maLoai']; ?>" class="text-blue-500">Edit</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($searchResults)) : ?>
                        <tr>
                            <td colspan="6" class="text-center">No books found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if (isset($_SESSION['delete_book_result'])) : ?>
            <p class="text-green-500 font-bold alert-message"><?php echo $_SESSION['delete_book_result']; ?></p>
        <?php endif; ?>

        <?php if (isset($_SESSION['add_book_result'])) : ?>
            <p class="text-green-500 font-bold alert-message"><?php echo $_SESSION['add_book_result']; ?></p>
        <?php endif; ?>

        <?php if (isset($_SESSION['update_book_result'])) : ?>
            <p class="text-green-500 font-bold alert-message"><?php echo $_SESSION['update_book_result']; ?></p>
        <?php endif; ?>

        <!-- Form thêm sách mới -->
        <h2 class="text-2xl font-bold mt-8">Add New Book</h2>
        <form action="./add_book.php" method="POST" class="mt-4">
            <input type="text" name="maSach" placeholder="Mã Sách" class="p-2 border rounded-md mr-2 text-black">
            <input type="text" name="tenSach" placeholder="Tên Sách" class="p-2 border rounded-md mr-2 text-black">
            <input type="text" name="maTG" placeholder="Mã Tác Giả" class="p-2 border rounded-md mr-2 text-black">
            <input type="text" name="maNXB" placeholder="Mã Nhà Xuất Bản" class="p-2 border rounded-md mr-2 text-black">
            <input type="text" name="maLoai" placeholder="Mã Loại" class="p-2 border rounded-md mr-2 text-black">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md">Add Book</button>
        </form>
    </div>

</body>
<script>
    // Hàm ẩn thông báo sau một khoảng thời gian
    function hideMessage() {
        var messages = document.querySelectorAll('.alert-message');
        messages.forEach(function(message) {
            setTimeout(function() {
                message.style.display = 'none';
            }, 5000); // 5 giây
        });
    }

    // Gọi hàm ẩn thông báo khi trang được tải
    window.onload = function() {
        hideMessage();
    };
</script>
<style>
    .alert-message {
        margin-top: 10px;
        padding: 10px;
        border: 1px solid #ccc;
        background-color: #f3f3f3;
    }
</style>

</html>