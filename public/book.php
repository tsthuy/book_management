<?php
// Lấy maSach từ query string
if (isset($_GET['maSach'])) {
    $maSach = $_GET['maSach'];

    // Kết nối đến cơ sở dữ liệu và truy vấn để lấy thông tin sách
    require_once 'connect.php';
    $query_sach = "SELECT * FROM sach WHERE maSach = ?";
    $statement_sach = $pdo->prepare($query_sach);
    $statement_sach->execute([$maSach]);
    $sach = $statement_sach->fetch(PDO::FETCH_ASSOC);

    // Kiểm tra xem sách có tồn tại không
    if ($sach) {
        $tenSach = $sach['tenSach'];
        $maTG = $sach['maTG'];
        $maNXB = $sach['maNXB'];
        $maLoai = $sach['maLoai'];
    } else {
        echo "Sách không tồn tại.";
        exit;
    }
} else {
    echo "Mã sách không được cung cấp.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông tin sách</title>
    <!-- Gắn Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <div class="container mx-auto py-8">

        <div class="grid grid-cols-2 gap-4">
            <!-- Form thông tin sách -->
            <div class="col-span-1 bg-white p-4 shadow rounded">
                <h1 class="text-2xl font-semibold mb-4">Thông tin sách</h1>

                <div class="mb-4">
                    <label for="tenSach" class="block text-gray-700 font-bold mb-2">Tên sách:</label>
                    <input type="text" id="tenSach" name="tenSach" value="<?php echo htmlspecialchars($tenSach); ?>" class="border-gray-300 border-2  shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md w-full">
                </div>
                <div class="mb-4">
                    <label for="maTG" class="block text-gray-700 font-bold mb-2">Mã tác giả:</label>
                    <input type="text" id="maTG" name="maTG" value="<?php echo htmlspecialchars($maTG); ?>" class="border-gray-300 border-2  shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md w-full">
                </div>
                <div class="mb-4">
                    <label for="maNXB" class="block text-gray-700 font-bold mb-2">Mã NXB:</label>
                    <input type="text" id="maNXB" name="maNXB" value="<?php echo htmlspecialchars($maNXB); ?>" class="border-gray-300 border-2  shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md w-full">
                </div>
                <div class="mb-4">
                    <label for="maLoai" class="block text-gray-700 font-bold mb-2">Mã loại:</label>
                    <input type="text" id="maLoai" name="maLoai" value="<?php echo htmlspecialchars($maLoai); ?>" class="border-gray-300 border-2  shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md w-full">
                </div>
                <!-- Các nút chỉnh sửa, xóa, thêm bản sao -->
                <div class="flex items-center">
                    <a href="#" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md">Chỉnh sửa</a>
                    <a href="#" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 m-2  rounded-md">Xóa</a>
                    <a href="#" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-md">Thêm bản sao</a>
                </div>
            </div>
            <!-- Form thông tin bản sao -->
            <div class="col-span-1 bg-white p-4 shadow rounded">
                <h1 class="text-2xl font-semibold mb-4">Thông tin bản sao sách</h1>

                <!-- Hiển thị danh sách bản sao -->
                <div id="listBanSao" class="text-gray-700"></div>
            </div>
        </div>
    </div>
</body>

</html>