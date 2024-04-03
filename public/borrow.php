<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mượn Sách</title>
    <!-- Gắn Tailwind CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>
<?php
define('TITLE', 'Tìm kiếm Thông Tin Độc Giả');
if (isset($_GET['keyword']) && !empty($_GET['keyword'])) {
    $keyword = '%' . $_GET['keyword'] . '%';
    require_once 'connect.php';
    $query = 'SELECT * FROM docgia WHERE MaDocGia LIKE ? OR TenDocGia LIKE ?';
    try {
        $statement = $pdo->prepare($query);
        $statement->execute([$keyword, $keyword]);
        $results = $statement->fetchAll();
    } catch (PDOException $e) {
        $error_message = 'Không thể tìm kiếm';
        $reason = $e->getMessage();
        exit; // Dừng chương trình nếu có lỗi
    }
}
?>


<header class="bg-gray-800 text-white p-4">
    <div class="container mx-auto flex justify-between items-center">
        <a href="home.php" class="text-2xl font-bold">Hệ thống quản lý thư viện</a>
        <!-- Phần tìm kiếm -->
        <form action="borrow.php" method="GET" class="flex items-center">
            <input type="text" id="keyword" name="keyword" placeholder="Tìm kiếm độc giả" class="p-2 border rounded-md mr-2 text-black">
            <input type="hidden" name="maSach" value="<?php echo htmlspecialchars($_GET['maSach']); ?>">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md">Tìm kiếm</button>
        </form>
        <!-- Kết thúc phần tìm kiếm -->

        <!-- Dropdown danh sách thể loại sách -->
        <div class="relative">

            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <path d="M14.293 7.293a1 1 0 0 0-1.414-1.414l-3 3a1 1 0 0 0-.001 1.415l3 3a1 1 0 0 0 1.415-1.415L11.414 11H16a1 1 0 0 0 0-2h-4.586l2.293-2.293z" />
                </svg>
            </div>
        </div>
        <!-- Kết thúc dropdown danh sách thể loại sách -->

        <nav>
            <ul class="flex space-x-4">
                <li><a href="index.php" class="hover:text-gray-300">Trang chủ</a></li>
                <li><a href="borrow.php" class="hover:text-gray-300">Mượn sách</a></li>
                <li><a href="return.php" class="hover:text-gray-300">Trả sách</a></li>
            </ul>
        </nav>
    </div>
</header>

<body class="bg-gray-100">
    <div style="padding: 10px;" class="container mx-auto py-8">

        <?php if (!empty($results)) : ?>
            <table style="border: 1px solid black;" class="min-w-full divide-y divide-black-700 border border-black-600">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mã Độc Giả</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên Độc Giả</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Địa Chỉ</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Số Thẻ Thư Viện</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Chọn</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($results as $row) : ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($row['MaDocGia']); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($row['TenDocGia']); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($row['DiaChi']); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($row['SoThe']); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <!-- Button để điền thông tin vào form -->
                                <button onclick="fillForm('<?php echo htmlspecialchars($row['MaDocGia']); ?>', '<?php echo htmlspecialchars($row['TenDocGia']); ?>', '<?php echo htmlspecialchars($row['DiaChi']); ?>', '<?php echo htmlspecialchars($row['SoThe']); ?>', )" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Chọn</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <h2 class="text-2xl font-semibold mb-4">Mượn Sách</h2>
        <!-- Danh sách độc giả tìm được -->








        <div class="bg-white p-4 shadow rounded">
            <form action="borrow_infor.php" method="post">
                <!-- Thông tin sách -->
                <?php
                if (isset($_GET['maSach'])) {
                    $maSach = $_GET['maSach'];
                    require_once 'connect.php';
                    $query = "SELECT * FROM sach WHERE maSach = ?";
                    try {
                        $sth = $pdo->prepare($query);
                        $sth->execute([$_GET['maSach']]);
                        $row = $sth->fetch(PDO::FETCH_ASSOC);
                    } catch (PDOException $e) {
                        $pdo_error = $e->getMessage();
                    }
                }
                ?>
                <h3 class="text-lg font-semibold mb-2">Thông tin sách</h3>
                <div class="bg-white p-4 shadow-inner rounded border-2 border-indigo-600">
                    <h3 class="text-lg font-semibold mb-2"><?php echo htmlspecialchars($row['tenSach']); ?></h3>
                    <p class="text-gray-600 mb-4">Mã Sách: <?php echo htmlspecialchars($row['maSach']); ?></p>
                    <p class="text-gray-600 mb-4">Tác giả: <?php echo htmlspecialchars($row['maTG']); ?></p>
                    <p class="text-gray-600 mb-4">Nhà xuất bản: <?php echo htmlspecialchars($row['maNXB']); ?></p>
                </div>
                <!-- Thông tin người mượn -->
                <h3 class="text-lg font-semibold mb-2 mt-4">Thông tin người mượn</h3>
                <div class="mb-4">
                    <label for="MaDocGia" class="block text-sm font-medium text-gray-700">Mã độc giả</label>
                    <input type="text" name="MaDocGia" id="MaDocGia" class="mt-1 p-2 w-full border border-gray-300 rounded-md">
                </div>
                <div class="mb-4">
                    <label for="tenDocGia" class="block text-sm font-medium text-gray-700">Tên độc giả</label>
                    <input type="text" name="tenDocGia" id="tenDocGia" class="mt-1 p-2 w-full border border-gray-300 rounded-md">
                </div>
                <div class="mb-4">
                    <label for="diaChi" class="block text-sm font-medium text-gray-700">Địa chỉ</label>
                    <input type="text" name="diaChi" id="diaChi" class="mt-1 p-2 w-full border border-gray-300 rounded-md">
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Số Thẻ Thư Viện</label>
                    <input type="text" class="form-control" id="SoThe" name="SoThe">
                </div>
                <input type="text" name="maSach" value="<?php echo htmlspecialchars($row['maSach']); ?>" hidden>
                <div id="theThuVienResult"></div> <!-- Hiển thị kết quả tìm kiếm -->
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full">Tạo Phiếu Mượn</button>
            </form>

        </div>
    </div>
    <script>
        // Điền thông tin của độc giả vào form
        function fillForm(maDocGia, tenDocGia, diaChi, SoThe) {
            document.getElementById('MaDocGia').value = maDocGia;
            document.getElementById('tenDocGia').value = tenDocGia;
            document.getElementById('diaChi').value = diaChi;
            document.getElementById('SoThe').value = SoThe;
        }
        // document.addEventListener('DOMContentLoaded', function() {
        //     var addReaderBtn = document.getElementById('add-reader-btn');
        //     var addReaderForm = document.getElementById('add-reader-form');

        //     addReaderBtn.addEventListener('click', function() {
        //         addReaderForm.style.display = 'block'; // Hiển thị form khi nhấp vào nút "Thêm độc giả"
        //     });
        // });

        // function searchTheThuVien(soThe) {
        //     $.ajax({
        //         url: '/timkiemthethuvien.php', // Đường dẫn đến file PHP xử lý tìm kiếm
        //         method: 'GET',
        //         data: {
        //             soThe: soThe
        //         }, // Dữ liệu gửi đi
        //         success: function(response) {
        //             $('#theThuVienResult').html(response); // Hiển thị kết quả tìm kiếm trong div theThuVienResult
        //         }
        //     });
        // }


        // function selectThe(selectedValue) {
        //     // Gán giá trị của ô chọn mã thẻ vào trường "Số thẻ" trong form thêm độc giả
        //     document.getElementById('SoThe').value = selectedValue;
        // }
    </script>
</body>



</html>