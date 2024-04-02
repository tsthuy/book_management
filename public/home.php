<?php
require_once 'connect.php';

// Lấy danh sách các thể loại sách từ cơ sở dữ liệu
$query_categories = "SELECT * FROM theloai";
try {
    $sth_categories = $pdo->prepare($query_categories);
    $sth_categories->execute();
    $categories = $sth_categories->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $pdo_error = $e->getMessage();
}

// Mã HTML để hiển thị các sách thuộc thể loại đã chọn hoặc tất cả các sách nếu không có thể loại được chọn
$html_books = '';

// Kiểm tra xem biến $_GET['keyword'] có tồn tại không
$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';

if (isset($_GET['category'])) {
    $selected_category = $_GET['category'];

    // Lấy danh sách các sách thuộc thể loại đã chọn
    $query_books = "SELECT * FROM sach WHERE maLoai IN (SELECT maLoai FROM theloai WHERE tenLoai = ?) AND tenSach LIKE ?";
    try {
        $sth_books = $pdo->prepare($query_books);
        $sth_books->execute([$selected_category, '%' . $keyword . '%']); // Thêm dấu % ở đầu và cuối chuỗi keyword để tìm kiếm theo từ khóa tương đối
        $books = $sth_books->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $pdo_error = $e->getMessage();
    }
} else {
    // Nếu không có thể loại được chọn, hiển thị tất cả các sách
    $query_books = "SELECT * FROM sach WHERE tenSach LIKE ?";
    try {
        $sth_books = $pdo->prepare($query_books);
        $sth_books->execute(['%' . $keyword . '%']); // Thêm dấu % ở đầu và cuối chuỗi keyword để tìm kiếm theo từ khóa tương đối
        $books = $sth_books->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $pdo_error = $e->getMessage();
    }
}

foreach ($books as $book) {
    $html_books .= '<div class="bg-white p-4 shadow rounded text-center">';
    $html_books .= '<h3 class="text-lg font-semibold mb-2">' . htmlspecialchars($book['tenSach']) . '</h3>';
    $html_books .= '<p class="text-gray-600 mb-4">Tác giả: ' . htmlspecialchars($book['maTG']) . '</p>';
    $html_books .= '<p class="text-gray-600 mb-4">Nhà xuất bản: ' . htmlspecialchars($book['maNXB']) . '</p>';
    $html_books .= '<a href="borrow.php?maSach=' . $book['maSach'] . '" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full">Mượn sách</a>';
    $html_books .= '<a href="book.php?maSach=' . $book['maSach'] . '" class="bg-purple-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-full ml-2">Quản lý sách</a>';
    $html_books .= '</div>';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang chủ - Hệ thống quản lý thư viện</title>
    <!-- Gắn Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <!-- Phần Header -->
    <?php
    require_once 'header.php';
    ?>

    <!-- Phần Content -->
    <div class="container mx-auto py-8">
        <h2 class="text-2xl font-semibold mb-4">Danh sách các loại sách</h2>

        <!-- Hiển thị các loại sách -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <?php echo $html_books; ?>
        </div>
    </div>

    <!-- Phần Footer -->
    <footer class="bg-gray-800 text-white p-4 mt-8">
        <div class="container mx-auto">
            <p class="text-center">&copy; 2024 Hệ thống quản lý thư viện. All rights reserved.</p>
        </div>
    </footer>
</body>

</html>