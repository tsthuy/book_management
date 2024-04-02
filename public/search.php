<?php
define('TITLE', 'Tìm kiếm Trích dẫn');
// include_once __DIR__ . '/../partials/header.php';

// Xử lý tìm kiếm nếu có từ khóa được gửi từ form
if (isset($_GET['keyword']) && !empty($_GET['keyword'])) {
    $keyword = '%' . $_GET['keyword'] . '%';
    require_once 'connect.php';
    $query = 'SELECT * FROM docgia WHERE MaDocGia LIKE ? OR TenDocGia LIKE ?';
    try {
        $statement = $pdo->prepare($query);
        $statement->execute([$keyword, $keyword]);
        $results = $statement->fetchAll();
    } catch (PDOException $e) {
        $error_message = 'Không thể tìm kiếm trích dẫn';
        $reason = $e->getMessage();
        // include_once __DIR__ . '/../partials/show_error.php';
        exit; // Dừng chương trình nếu có lỗi
    }

    // Hiển thị kết quả tìm kiếm
    echo '<h2>Kết quả tìm kiếm cho: ' . htmlspecialchars($_GET['keyword']) . '</h2>';
    if (empty($results)) {
        echo '<p>Không tìm thấy kết quả phù hợp.</p>';
    } else {
        foreach ($results as $row) {
            echo '<div>';
            echo '<blockquote>' . htmlspecialchars($row['MaDocGia']) . '</blockquote>';
            echo ' - ' . htmlspecialchars($row['TenDocGia']);
            echo '</div><br>';
        }
    }
}

// Hiển thị form tìm kiếm
?>
<h2>Tìm kiếm Trích dẫn</h2>
<form action="search.php" method="GET">
    <label for="keyword">Nhập từ khóa:</label>
    <input type="text" id="keyword" name="keyword" required>
    <button type="submit">Tìm kiếm</button>
</form>