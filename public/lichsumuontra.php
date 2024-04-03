<?php

require_once 'connect.php'; // Import file kết nối đến cơ sở dữ liệu

// Truy vấn dữ liệu từ bảng phieumuon và chitietphieumuon
$query = "SELECT pm.maPhieuMuon, pm.ngayMuon, pm.hanTra, pm.ngayTra, pm.phiPhat, pm.maDocGia, pm.maSach, ctp.maBanSao, ctp.ttLucTra
          FROM phieumuon pm
          INNER JOIN chitietphieumuon ctp ON pm.maPhieuMuon = ctp.maPhieuMuon";

try {
    $statement = $pdo->prepare($query);
    $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
} catch (\Throwable $th) {
    $pdo_error = $th->getMessage(); // Sửa $e thành $th
}
// Số lượng phiếu mượn đã được trả
$daTraCount = 0;

// Số lượng phiếu mượn đang còn mượn
$dangMuonCount = 0;

foreach ($result as $row) {
    // Nếu ngày trả không có giá trị, tức là phiếu mượn vẫn đang còn mượn
    if (empty($row['ngayTra'])) {
        $dangMuonCount++;
    } else {
        $daTraCount++;
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lịch sử mượn trả</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body>
    <?php require_once 'header.php'; ?>
    <div class="container mx-auto">
        <h2 class="text-2xl font-bold mb-4">Lịch sử mượn trả</h2>
        <div class="overflow-x-auto">
            <table class="w-full table-auto text-center">
                <thead>
                    <tr class="bg-blue-300">
                        <th class="px-4 py-2">Mã Phiếu Mượn</th>
                        <th class="px-4 py-2">Mã Độc Giả</th>
                        <th class="px-4 py-2">Mã Sach</th>
                        <th class="px-4 py-2">Mã Bản Sao</th>
                        <th class="px-4 py-2">Ngày Mượn</th>
                        <th class="px-4 py-2">Hạn Trả</th>
                        <th class="px-4 py-2">Ngày Trả</th>
                        <th class="px-4 py-2">Phí Phạt</th>
                        <th class="px-4 py-2">Tình Trạng Lúc Trả</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($result as $row) : ?> <!-- Sửa từ while thành foreach -->
                        <tr>
                            <td class="border px-4 py-2"><?php echo $row['maPhieuMuon']; ?></td>
                            <td class="border px-4 py-2"><?php echo $row['maDocGia']; ?></td>
                            <td class="border px-4 py-2"><?php echo $row['maSach']; ?></td>
                            <td class="border px-4 py-2"><?php echo $row['maBanSao']; ?></td>
                            <td class="border px-4 py-2"><?php echo $row['ngayMuon']; ?></td>
                            <td class="border px-4 py-2"><?php echo $row['hanTra']; ?></td>
                            <td class="border px-4 py-2"><?php echo $row['ngayTra']; ?></td>
                            <td class="border px-4 py-2"><?php echo $row['phiPhat']; ?></td>
                            <td class="border px-4 py-2"><?php echo $row['ttLucTra']; ?></td>
                        </tr>
                    <?php endforeach; ?> <!-- Sửa từ endwhile thành endforeach -->
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            <button onclick="toggleStatistics()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Thống kê
            </button>
            <div id="statistics" class="hidden mt-4">
                <h3 class="font-bold">Thống kê:</h3>
                <p>Số lượng phiếu mượn đã được trả: <?php echo $daTraCount; ?></p>
                <p>Số lượng phiếu mượn đang còn mượn: <?php echo $dangMuonCount; ?></p>
            </div>
        </div>
    </div>

</body>
<script>
    function toggleStatistics() {
        var statisticsDiv = document.getElementById("statistics");
        if (statisticsDiv.style.display === "none") {
            statisticsDiv.style.display = "block";
        } else {
            statisticsDiv.style.display = "none";
        }
    }
</script>

</html>