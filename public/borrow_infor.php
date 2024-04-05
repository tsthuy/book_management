<?php

// Kiểm tra xem nút "Tạo Phiếu Mượn và Lưu Thông Tin Đọc Giả" đã được nhấn chưa
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy thông tin từ form

    $maDocGia = $_POST['MaDocGia'];
    $SoThe = $_POST['SoThe'];
    $tenDocGia = $_POST['tenDocGia']; // Thêm dòng này để lấy tên độc giả từ form

    require_once 'connect.php';
    // Kiểm tra xem độc giả đã tồn tại trong cơ sở dữ liệu hay không
    $query_check = "SELECT * FROM docgia WHERE MaDocGia = ?";
    $statement_check = $pdo->prepare($query_check);
    $statement_check->execute([$maDocGia]);
    $existing_docgia = $statement_check->fetch(PDO::FETCH_ASSOC);
    // Nếu độc giả chưa tồn tại, thêm mới vào cơ sở dữ liệu
    if (!$existing_docgia) {
        $query_insert = "INSERT INTO docgia (MaDocGia, TenDocGia, DiaChi, SoThe) VALUES (?, ?, ?, ?)";
        $statement_insert = $pdo->prepare($query_insert);
        $statement_insert->execute([$maDocGia, $tenDocGia, $diaChi, $SoThe]);
        echo "new user already created";
    }
}

function generateMaPhieuMuon()
{
    // Thực hiện các bước tạo mã tùy thuộc vào nhu cầu của bạn
    // Ví dụ: kết hợp ngày tháng và một số ngẫu nhiên
    $maPhieuMuon = 'MPM' . date('Ymd') . mt_rand(100, 999);
    return $maPhieuMuon;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nhập Thông Tin Phiếu Mượn</title>
    <!-- Gắn Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<?php
require 'header.php';
?>

<body class="bg-gray-100">
    <div class="container p-2 ">
        <h2 class="text-2xl font-semibold mb-4 text-center">Nhập Thông Tin Phiếu Mượn</h2>
        <div class="bg-white p-4 shadow-2 border-2  border-indigo-600 rounded">
            <form action="process_borrow.php" method="post" enctype="multipart/form-data">
                <div class="mb-4">
                    <label for="maPhieuMuon" class="block text-sm font-medium text-gray-700">Mã Phiếu Mượn</label>
                    <input type="text" name="maPhieuMuon" id="maPhieuMuon" class="mt-1 p-2 w-full border border-gray-300 rounded-md" value="<?php echo generateMaPhieuMuon(); ?>" readonly>
                </div>
                <div class="mb-4">
                    <label for="maPhieuMuon" class="block text-sm font-medium text-gray-700">Mã Sách</label>
                    <input type="text" name="maSach" id="maSach" class="mt-1 p-2 w-full border border-gray-300 rounded-md" value=<?php echo $_POST['maSach'] ?>>
                </div>
                <div class="mb-4">
                    <label for="ngayMuon" class="block text-sm font-medium text-gray-700">Ngày Mượn(YYYY-MM-DD)</label>
                    <input type="text" data-date="YYYY-MM-DD" data-date-format="YYYY-MM-DD" name="ngayMuon" id="ngayMuon" class="mt-1 p-2 w-full border border-gray-300 rounded-md">
                </div>
                <div class="mb-4">
                    <label for="hanTra" class="block text-sm font-medium text-gray-700">Hạn Trả(YYYY-MM-DD)</label>
                    <input type="text" name="hanTra" id="hanTra" class="mt-1 p-2 w-full border border-gray-300 rounded-md">
                </div>
                <div class="mb-4">
                    <label for="maDocGia" class="block text-sm font-medium text-gray-700">Mã Độc Giả</label>
                    <input value=<?php echo $_POST['MaDocGia'] ?> type="text" name="maDocGia" id="maDocGia" class="mt-1 p-2 w-full border border-gray-300 rounded-md">
                </div>
                <div class="mb-4">
                    <label for="maBanSao" class="block text-sm font-medium text-gray-700">Mã Bản Sao</label>
                    <input type="text" name="maBanSao" id="maBanSao" class="mt-1 p-2 w-full border border-gray-300 rounded-md">
                </div>
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full mx-auto block">Lưu Phiếu Mượn</button>

            </form>
        </div>
    </div>
</body>

</html>