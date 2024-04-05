<?php
require_once __DIR__ . '/../src/bootstrap.php';

use QTDL\Project\ReaderCard;

$readerCard = new ReaderCard($PDO);
$readerCards = $readerCard->getReaderCards();

// Kiểm tra xem có yêu cầu POST được gửi đi không
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Xử lý dữ liệu khi form được gửi đi
    $data = [
        'NgayBatDau' => $_POST['NgayBatDau'] ?? '',
        'NgayHetHan' => $_POST['NgayHetHan'] ?? '',
        'GhiChu' => $_POST['GhiChu'] ?? ''
    ];

    // Gán dữ liệu từ form và thêm thẻ thư viện vào cơ sở dữ liệu
    $readerCard->fill($data);
    if ($readerCard->save()) {
        header("Location: thethuvien.php"); // Chuyển hướng sau khi thêm thành công
        exit();
    } else {
        echo "Đã xảy ra lỗi khi thêm thẻ thư viện.";
    }
}

// Kiểm tra xem có dữ liệu tìm kiếm được gửi đi không
if (isset($_GET['keyword'])) {
    // Lấy từ khóa tìm kiếm từ dữ liệu gửi đi
    $keyword = $_GET['keyword'];
    // Thực hiện truy vấn cơ sở dữ liệu với điều kiện WHERE để lọc kết quả
    $readerCards = $readerCard->searchReaderCards($keyword);
} else {
    // Nếu không có dữ liệu tìm kiếm, hiển thị tất cả các thẻ thư viện
    $readerCards = $readerCard->getReaderCards();
}

// Mặc định sắp xếp theo Ngày bắt đầu nếu không có dữ liệu sắp xếp được gửi đi
$sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'NgayBatDau';

// Kiểm tra xem có dữ liệu tìm kiếm được gửi đi không
if (isset($_GET['keyword'])) {
    // Lấy từ khóa tìm kiếm từ dữ liệu gửi đi
    $keyword = $_GET['keyword'];
    // Thực hiện truy vấn cơ sở dữ liệu với điều kiện WHERE để lọc kết quả
    $readerCards = $readerCard->searchReaderCards($keyword);
} else {
    // Nếu không có dữ liệu tìm kiếm, hiển thị tất cả các thẻ thư viện và sắp xếp theo điều kiện được chọn
    $readerCards = $readerCard->getReaderCardsSorted($sort_by);
}

// sql nang cao
require_once __DIR__ . '/../src/bootstrap.php';
$sql = "SELECT total_library_cards_count() AS total_library_cards";
$stmt = $PDO->prepare($sql);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$total_library_cards = $result['total_library_cards'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách thẻ thư viện</title>
    <!-- Link CSS của Bootstrap -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <link href="/public/css/style.css" rel="stylesheet">
</head>

<body>
    <?php
    require_once 'header.php';
    ?>
    <div class="container mt-5">

        <h1 class="text-center mb-4 uppercase font-bold">Danh sách thẻ thư viện( <?php echo "Tổng số lượng thẻ thư viện: " . $total_library_cards; ?>)</h1>

        <div class="flex-container d-flex">
            <div class="search-input flex-grow-1 mr-3">
                <form action="/thethuvien.php" method="get" class="d-flex">
                    <input type="text" class="form-control" placeholder="Tìm kiếm..." name="keyword">
                    <button type="submit" class="btn btn-primary">Tìm</button>
                </form>
            </div>
            <div class="sort-input flex-grow-1">
                <form action="/thethuvien.php" method="get" class="d-flex">
                    <select class="form-control mr-3" name="sort_by">
                        <option value="NgayBatDau" <?php if ($sort_by == 'NgayBatDau') echo 'selected'; ?>>Sắp xếp theo ngày bắt đầu</option>
                        <option value="NgayHetHan" <?php if ($sort_by == 'NgayHetHan') echo 'selected'; ?>>Sắp xếp theo ngày hết hạn</option>
                    </select>
                    <button type="submit" class="btn btn-primary">Chọn</button>
                </form>
            </div>
            <div>
                <button id="add-reader-card-btn" class="btn btn-primary btn-custom">Thêm thẻ thư viện</button>
            </div>
        </div>


        <div class="mt-4">
            <form id="add-reader-card-form" style="display: none;" action="/thethuvien.php" method="post">
                <div class="form-group">
                    <label for="NgayBatDau">Ngày bắt đầu:</label>
                    <input type="date" class="form-control" id="NgayBatDau" name="NgayBatDau" required>
                </div>
                <div class="form-group">
                    <label for="NgayHetHan">Ngày hết hạn:</label>
                    <input type="date" class="form-control" id="NgayHetHan" name="NgayHetHan" required>
                </div>
                <div class="form-group">
                    <label for="GhiChu">Ghi chú:</label>
                    <input type="text" class="form-control" id="GhiChu" name="GhiChu">
                </div>
                <button type="submit" class="btn btn-success">Thêm thẻ thư viện</button>
            </form>
        </div>
        <!-- Table Starts Here -->
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">Số thẻ</th>
                        <th scope="col">Ngày bắt đầu</th>
                        <th scope="col">Ngày hết hạn</th>
                        <th scope="col">Ghi chú</th>
                        <th scope="col">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($readerCards as $readerCard) : ?>
                        <tr>
                            <td><?= htmlspecialchars($readerCard->SoThe) ?></td>
                            <td><?= htmlspecialchars($readerCard->NgayBatDau) ?></td>
                            <td><?= htmlspecialchars($readerCard->NgayHetHan) ?></td>
                            <td><?= htmlspecialchars($readerCard->GhiChu) ?></td>
                            <td class="d-flex justify-content-center">
                                <form class="form-group">
                                    <a href="<?= '/EditReaderCard.php?id=' . $readerCard->getId() ?>" class="btn btn-xs btn-warning">
                                        <i alt="Edit" class="fa fa-pencil"></i> Sửa</a>
                                </form>
                                <form class="form-group" action="/DeleteReaderCard.php" method="POST">
                                    <input type="hidden" name="id" value="<?= $readerCard->getId() ?>">
                                    <button type="submit" class="btn btn-xs btn-danger" name="delete-readercard"> <i alt="Delete" class="fa fa-trash"></i> Xóa</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
        <!-- Table Ends Here -->

        <!-- Form to add new reader card -->
        <div class="mt-4">
            <form id="add-reader-card-form" style="display: none;" action="/thethuvien.php" method="post">
                <div class="form-group">
                    <label for="NgayBatDau">Ngày bắt đầu:</label>
                    <input type="date" class="form-control" id="NgayBatDau" name="NgayBatDau" required>
                </div>
                <div class="form-group">
                    <label for="NgayHetHan">Ngày hết hạn:</label>
                    <input type="date" class="form-control" id="NgayHetHan" name="NgayHetHan" required>
                </div>
                <div class="form-group">
                    <label for="GhiChu">Ghi chú:</label>
                    <input type="text" class="form-control" id="GhiChu" name="GhiChu">
                </div>
                <button type="submit" class="btn btn-success">Thêm thẻ thư viện</button>
            </form>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var addReaderCardBtn = document.getElementById('add-reader-card-btn');
                var addReaderCardForm = document.getElementById('add-reader-card-form');

                addReaderCardBtn.addEventListener('click', function() {
                    addReaderCardForm.style.display = 'block'; // Hiển thị form khi nhấp vào nút "Thêm thẻ thư viện"
                });
            });
        </script>

</body>

</html>