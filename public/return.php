<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trả Sách</title>
    <!-- Gắn Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <!-- Header -->


    <!-- header.php -->
    <header class="bg-gray-800 text-white p-4">
        <div class="container mx-auto flex justify-between items-center">
            <a href="home.php" class="text-2xl font-bold">Hệ thống quản lý thư viện</a>
            <!-- Phần tìm kiếm -->
            <form action="return.php" method="GET">
                <input type="text" name="keyword" placeholder="Nhập mã phiếu mượn" class="p-2 border rounded-md text-black">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md">Tìm Kiếm</button>
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
                    <li><a href="home.php" class="hover:text-gray-300">Trang chủ</a></li>
                    <li><a href="home.php" class="hover:text-gray-300">Mượn sách</a></li>
                    <li><a href="lichsumuontra.php" class="hover:text-gray-300">Lịch Sử Mượn Trả</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <?php
    define('TITLE', 'Tìm kiếm Thông Tin Mã Phiếu Mượn');
    if (isset($_GET['keyword']) && !empty($_GET['keyword'])) {
        $keyword = '%' . $_GET['keyword'] . '%';
        require_once 'connect.php';
        $query = 'SELECT * FROM phieumuon WHERE maPhieuMuon LIKE ?';
        try {
            $statement = $pdo->prepare($query);
            $statement->execute([$keyword]);
            $results = $statement->fetchAll();
        } catch (PDOException $e) {
            $error_message = 'Không thể tìm kiếm';
            $reason = $e->getMessage();
            exit; // Dừng chương trình nếu có lỗi
        }
    }

    ?>
    <?php if (!empty($results)) : ?>
        <div class="p-2">
            <table class="min-w-full divide-y divide-black-700 border  border-2 border-indigo-600">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mã Phiếu Mượn</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hạn Trả</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Chọn</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($results as $row) : ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($row['maPhieuMuon']); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($row['hanTra']); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap">

                                <!-- Button để điền thông tin vào form -->
                                <button onclick="fillForm('<?php echo htmlspecialchars($row['maPhieuMuon']); ?>', '<?php echo htmlspecialchars($row['hanTra']); ?>')" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Chọn</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <div class="container mx-auto py-8 p-2">
        <h2 class="text-2xl font-semibold mb-4 text-center">Trả Sách</h2>
        <div class="bg-white p-4 shadow rounded border-2 border-indigo-600 p-2 ">
            <form action="return_save.php" method="post" enctype="multipart/form-data">
                <div class="mb-4">
                    <label for="maPhieuMuon" class="block text-sm font-medium text-gray-700">Mã Phiếu Mượn</label>
                    <input type="text" name="maPhieuMuon" id="maPhieuMuon" class="mt-1 p-2 w-full border border-gray-300 rounded-md">
                </div>
                <div class="mb-4">
                    <label for="hanTra" class="block text-sm font-medium text-gray-700">Hạn Trả</label>
                    <input type="text" name="hanTra" id="hanTra" class="mt-1 p-2 w-full border border-gray-300 rounded-md">
                </div>
                <div class="mb-4">
                    <label for="ngayTra" class="block text-sm font-medium text-gray-700">Ngày Trả</label>
                    <input type="text" name="ngayTra" id="ngayTra" class="mt-1 p-2 w-full border border-gray-300 rounded-md" onchange="calculateLateFee()">
                    <button type="button" onclick="calculateLateFee()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md">Tính Phí Phạt</button>
                </div>
                <div class="mb-4">
                    <label for="phiPhat" class="block text-sm font-medium text-gray-700">Phí Phạt</label>
                    <input type="text" name="phiPhat" id="phiPhat" class="mt-1 p-2 w-full border border-gray-300 rounded-md">
                </div>
                <div class="mb-4">
                    <label for="ttLucTra" class="block text-sm font-medium text-gray-700">Tình Trạng Lúc Trả</label>
                    <input type="text" name="ttLucTra" id="ttLucTra" class="mt-1 p-2 w-full border border-gray-300 rounded-md">
                </div>
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full mx-auto block">Lưu Thông Tin Trả Sách</button>
            </form>
        </div>
    </div>
</body>
<script>
    // Điền thông tin của độc giả vào form
    function fillForm(maPhieuMuon, hanTra) {
        document.getElementById('maPhieuMuon').value = maPhieuMuon;
        document.getElementById('hanTra').value = hanTra;
        console.log(hanTra);
    }
    // Chuyển đổi chuỗi ngày thành đối tượng Date
    function convertToDate(dateString) {
        var parts = dateString.split('-');
        return new Date(parts[0], parts[1] - 1, parts[2]); // Tháng trong JavaScript bắt đầu từ 0 (0 - 11)
    }

    // Kiểm tra và tính phí phạt
    function calculateLateFee() {
        var hanTraString = document.getElementById('hanTra').value;
        var ngayTraString = document.getElementById('ngayTra').value;

        var hanTra = convertToDate(hanTraString);
        var ngayTra = convertToDate(ngayTraString);

        // Chuyển đổi thành số ngày để so sánh
        var timeDiff = ngayTra.getTime() - hanTra.getTime();
        var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24));

        // Kiểm tra nếu ngày trả muộn hơn hạn trả
        if (diffDays > 0) {
            // Hiển thị phí phạt là 10k
            document.getElementById('phiPhat').value = '10000';
        } else {
            // Ngược lại, không có phí phạt
            document.getElementById('phiPhat').value = '0';
        }
    }
</script>

</html>