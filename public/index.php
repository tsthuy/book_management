<?php
require_once __DIR__ . '/../src/bootstrap.php';

use QTDL\Project\Reader;

$reader = new Reader($PDO);
$readers = $reader->getReaders(); // Sử dụng phương thức getReaders() thay vì all()

// Kiểm tra xem có yêu cầu POST được gửi đi không
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Xử lý dữ liệu khi form được gửi đi
    $data = [
        'TenDocGia' => $_POST['TenDocGia'] ?? '',
        'DiaChi' => $_POST['DiaChi'] ?? '',
        'SoThe' => $_POST['SoThe'] ?? '' // Sửa 'SoDienThoai' thành 'SoThe'
    ];

    // Gán dữ liệu từ form và thêm độc giả vào cơ sở dữ liệu
    $reader->fill($data);
    if ($reader->validate() && $reader->save()) {
        header("Location: index.php"); // Chuyển hướng sau khi thêm thành công
        exit();
    } else {
        echo "Đã xảy ra lỗi khi thêm đọc giả.";
    }
}

// Kiểm tra xem có dữ liệu tìm kiếm được gửi đi không
if (isset($_GET['keyword'])) {
    // Lấy từ khóa tìm kiếm từ dữ liệu gửi đi
    $keyword = $_GET['keyword'];
    // Thực hiện truy vấn cơ sở dữ liệu với điều kiện WHERE để lọc kết quả
    $readers = $reader->searchReaders($keyword);
} else {
    // Nếu không có dữ liệu tìm kiếm, hiển thị tất cả các độc giả
    $readers = $reader->getReaders();
}

// Mặc định sắp xếp theo tên nếu không có dữ liệu sắp xếp được gửi đi
$sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'TenDocGia';

// Kiểm tra xem có dữ liệu sắp xếp được gửi đi không
if (isset($_GET['sort_by'])) {
    // Lấy điều kiện sắp xếp từ dữ liệu gửi đi
    $sort_by = $_GET['sort_by'];
    // Thực hiện truy vấn cơ sở dữ liệu với điều kiện ORDER BY để sắp xếp kết quả
    $readers = $reader->getReadersSorted($sort_by);
}
///////
require_once __DIR__ . '/../src/bootstrap.php';
// Gọi hàm SQL để đếm số lượng đọc giả
$sql = "SELECT COUNT(*) AS total FROM docgia";
$stmt = $PDO->prepare($sql);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

// Trả về kết quả
if ($result) {
    // echo $result['total']; // Trả về số lượng đọc giả
} else {
    echo "0"; // Trả về 0 nếu không có độc giả nào
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách đọc giả</title>
    <!-- Link CSS của Bootstrap -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <?php
    require_once 'header.php';
    ?>
    <div class="container mt-5">
        <h1 class="text-center mb-4 uppercase font-bold">Danh sách đọc giả (Tổng số lượng độc giả: <?php echo $result['total']; ?>)</h1>

        <div class="row">
            <div class="col-md-6">
                <form class="form-inline mb-3" action="/index.php" method="get">
                    <input class="form-control mr-sm-2" type="search" placeholder="Tìm kiếm" aria-label="Search" name="keyword">
                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Tìm kiếm</button>
                </form>
            </div>
            <div class="col-md-3">
                <select class="form-control" onchange="location = this.value;">
                    <option value="/index.php?sort_by=TenDocGia" <?php if ($sort_by == 'TenDocGia') echo 'selected'; ?>>Sắp xếp theo Tên</option>
                    <option value="/index.php?sort_by=DiaChi" <?php if ($sort_by == 'DiaChi') echo 'selected'; ?>>Sắp xếp theo Địa chỉ</option>
                    <option value="/index.php?sort_by=SoThe" <?php if ($sort_by == 'SoThe') echo 'selected'; ?>>Sắp xếp theo Số thẻ</option>
                </select>
            </div>
            <div class="col-md-3">
                <button id="add-reader-btn" class="btn btn-primary btn-block">Thêm đọc giả</button>
            </div>
        </div>

        <div class="mt-4">
            <form id="add-reader-form" style="display: none;" action="/index.php" method="post">
                <div class="form-group">
                    <label for="TenDocGia">Tên đọc giả:</label>
                    <input type="text" class="form-control" id="TenDocGia" name="TenDocGia" required>
                </div>
                <div class="form-group">
                    <label for="DiaChi">Địa chỉ:</label>
                    <input type="text" class="form-control" id="DiaChi" name="DiaChi">
                </div>
                <div class="form-group">
                    <label for="SoThe">Số thẻ:</label>
                    <!-- Ô tìm kiếm hoặc ô chọn mã thẻ -->
                    <input type="text" class="form-control" id="SoThe" name="SoThe" onkeyup="searchTheThuVien(this.value)">
                </div>
                <div id="theThuVienResult"></div> <!-- Hiển thị kết quả tìm kiếm -->

                <button type="submit" class="btn btn-success btn-block">Thêm đọc giả</button>
            </form>
        </div>
        <!-- Table Starts Here -->
        <div class="table-responsive mt-4">
            <table class="table table-striped table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">Mã đọc giả</th>
                        <th scope="col">Tên đọc giả</th>
                        <th scope="col">Địa chỉ</th>
                        <th scope="col">Số Thẻ</th>
                        <th scope="col">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($readers as $reader) : ?>
                        <tr>
                            <td><?= htmlspecialchars($reader->MaDocGia) ?></td>
                            <td><?= htmlspecialchars($reader->TenDocGia) ?></td>
                            <td><?= htmlspecialchars($reader->DiaChi) ?></td>
                            <td><?= htmlspecialchars($reader->SoThe) ?></td>
                            <td>
                                <a href="/EditReader.php?id=<?= $reader->getId() ?>" class="btn btn-xs btn-warning">
                                    <i class="fa fa-pencil"></i> Sửa
                                </a>
                                <form action="DeleteReader.php" method="POST" style="display: inline;">
                                    <input type="hidden" name="id" value="<?= $reader->getId() ?>">
                                    <button type="submit" class="btn btn-xs btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xoá đọc giả này không?');">
                                        <i class="fa fa-trash"></i> Xoá
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
        <!-- Table Ends Here -->
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var addReaderBtn = document.getElementById('add-reader-btn');
            var addReaderForm = document.getElementById('add-reader-form');

            addReaderBtn.addEventListener('click', function() {
                addReaderForm.style.display = 'block'; // Hiển thị form khi nhấp vào nút "Thêm độc giả"
            });
        });

        function searchTheThuVien(soThe) {
            $.ajax({
                url: '/timkiemthethuvien.php', // Đường dẫn đến file PHP xử lý tìm kiếm
                method: 'GET',
                data: {
                    soThe: soThe
                }, // Dữ liệu gửi đi
                success: function(response) {
                    $('#theThuVienResult').html(response); // Hiển thị kết quả tìm kiếm trong div theThuVienResult
                }
            });
        }


        function selectThe(selectedValue) {
            // Gán giá trị của ô chọn mã thẻ vào trường "Số thẻ" trong form thêm độc giả
            document.getElementById('SoThe').value = selectedValue;
        }
    </script>


</body>

</html>