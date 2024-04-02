<?php
require_once __DIR__ . '/../src/bootstrap.php';
require_once __DIR__ . '/../src/classes/ReaderCard.php';

use QTDL\Project\ReaderCard;

$readerCard = new ReaderCard($PDO);
$SoThe = isset($_REQUEST['id']) ? $_REQUEST['id'] : null;
if (empty($SoThe) || !($readerCard->find($SoThe))) {
    redirect('/');
}
$errors = [];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy ID của thẻ thư viện từ form
    $id = $_POST['id'] ?? null;

    // Kiểm tra xem ID có tồn tại và hợp lệ không
    if ($id === null) {
        // Xử lý lỗi nếu cần
    } else {
        // Tạo một thể hiện mới của lớp ReaderCard
        $updatedReaderCard = new ReaderCard($PDO);

        // Tìm thẻ thư viện dựa trên ID
        $foundReaderCard = $updatedReaderCard->find($id);

        // Kiểm tra xem thẻ thư viện có tồn tại không
        if ($foundReaderCard) {
            // Lấy dữ liệu từ form và cập nhật thẻ thư viện
            $data = [
                'NgayBatDau' => $_POST['NgayBatDau'] ?? '',
                'NgayHetHan' => $_POST['NgayHetHan'] ?? '',
                'GhiChu' => $_POST['GhiChu'] ?? ''
            ];
            $foundReaderCard->fill($data); // Đổ dữ liệu từ form vào thẻ thư viện
            if ($foundReaderCard->update($data)) {
                // Nếu cập nhật thành công, bạn có thể chuyển hướng hoặc hiển thị thông báo thành công
                header("Location: thethuvien.php");
                exit();
            } else {
                // Nếu cập nhật không thành công, bạn có thể chuyển hướng hoặc hiển thị thông báo lỗi
            }
        } else {
            // Nếu không tìm thấy thẻ thư viện, bạn có thể chuyển hướng hoặc hiển thị thông báo lỗi
        }
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh sửa thẻ thư viện</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 50px;
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background-color: #343a40;
            color: white;
            border: none;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }
        .form-control {
            border: 1px solid #ced4da;
            border-radius: 5px;
        }
        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            border-radius: 5px;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="card">
        <div class="card-header">
            <h2 class="mb-0">Chỉnh sửa thẻ thư viện</h2>
        </div>
        <div class="card-body">
            <form method="post" class="col-md-6 offset-md-3" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $readerCard->SoThe ?>">
                <div class="form-group">
                    <label for="NgayBatDau">Ngày bắt đầu</label>
                    <input type="date" name="NgayBatDau" class="form-control<?= isset($errors['NgayBatDau']) ? ' is-invalid' : '' ?>" id="NgayBatDau" value="<?= html_escape($readerCard->NgayBatDau) ?>" />
                    <?php if (isset($errors['NgayBatDau'])) : ?>
                        <span class="invalid-feedback">
                            <strong><?= $errors['NgayBatDau'] ?></strong>
                        </span>
                    <?php endif ?>
                </div>
                <div class="form-group">
                    <label for="NgayHetHan">Ngày hết hạn</label>
                    <input type="date" name="NgayHetHan" class="form-control<?= isset($errors['NgayHetHan']) ? ' is-invalid' : '' ?>" id="NgayHetHan" value="<?= html_escape($readerCard->NgayHetHan) ?>" />
                    <?php if (isset($errors['NgayHetHan'])) : ?>
                        <span class="invalid-feedback">
                            <strong><?= $errors['NgayHetHan'] ?></strong>
                        </span>
                    <?php endif ?>
                </div>
                <div class="form-group">
                    <label for="GhiChu">Ghi chú</label>
                    <textarea name="GhiChu" id="GhiChu" class="form-control<?= isset($errors['GhiChu']) ? ' is-invalid' : '' ?>" placeholder="Nhập ghi chú"><?= html_escape($readerCard->GhiChu) ?></textarea>
                    <?php if (isset($errors['GhiChu'])) : ?>
                        <span class="invalid-feedback">
                            <strong><?= $errors['GhiChu'] ?></strong>
                        </span>
                    <?php endif ?>
                </div>
                <button type="submit" name="submit" class="btn btn-primary">Cập nhật thẻ thư viện</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>
