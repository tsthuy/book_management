<?php
require_once __DIR__ . '/../src/bootstrap.php';
require_once __DIR__ . '/../src/classes/Reader.php';

use QTDL\Project\Reader;

$reader = new Reader($PDO);
$MaDocGia = isset($_REQUEST['id']) ? $_REQUEST['id'] : null;

// Kiểm tra xem MaDocGia có tồn tại và hợp lệ không
if (empty($MaDocGia) || !($reader->find($MaDocGia))) {
    redirect('/');
}

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy ID của độc giả từ form
    $id = $_POST['id'] ?? null;

    // Kiểm tra xem ID có tồn tại và hợp lệ không
    if ($id === null) {
        // Xử lý lỗi nếu cần
    } else {
        // Tạo một thể hiện mới của lớp Reader
        $updatedReader = new Reader($PDO);

        // Tìm độc giả dựa trên ID
        $foundReader = $updatedReader->find($id);

        // Kiểm tra xem độc giả có tồn tại không
        if ($foundReader) {
            // Lấy dữ liệu từ form và cập nhật độc giả
            $data = [
                'TenDocGia' => $_POST['TenDocGia'] ?? '',
                'DiaChi' => $_POST['DiaChi'] ?? '',
                'SoThe' => $_POST['SoThe'] ?? ''
            ];
            $foundReader->fill($data); // Đổ dữ liệu từ form vào độc giả
            if ($foundReader->update($data)) {
                // Nếu cập nhật thành công, bạn có thể chuyển hướng hoặc hiển thị thông báo thành công
                header("Location: index.php");
                exit();
            } else {
                // Nếu cập nhật không thành công, bạn có thể chuyển hướng hoặc hiển thị thông báo lỗi
            }
        } else {
            // Nếu không tìm thấy độc giả, bạn có thể chuyển hướng hoặc hiển thị thông báo lỗi
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh sửa thông tin độc giả</title>
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
                <h2 class="mb-0">Chỉnh sửa thông tin độc giả</h2>
            </div>
            <div class="card-body">
                <form method="post" class="col-md-6 offset-md-3" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?= $reader->MaDocGia ?>">
                    <div class="form-group">
                        <label for="TenDocGia">Tên độc giả</label>
                        <input type="text" name="TenDocGia" class="form-control<?= isset($errors['TenDocGia']) ? ' is-invalid' : '' ?>" id="TenDocGia" value="<?= html_escape($reader->TenDocGia) ?>" />
                        <?php if (isset($errors['TenDocGia'])) : ?>
                            <span class="invalid-feedback">
                                <strong><?= $errors['TenDocGia'] ?></strong>
                            </span>
                        <?php endif ?>
                    </div>
                    <div class="form-group">
                        <label for="DiaChi">Địa chỉ</label>
                        <input type="text" name="DiaChi" class="form-control<?= isset($errors['DiaChi']) ? ' is-invalid' : '' ?>" id="DiaChi" value="<?= html_escape($reader->DiaChi) ?>" />
                        <?php if (isset($errors['DiaChi'])) : ?>
                            <span class="invalid-feedback">
                                <strong><?= $errors['DiaChi'] ?></strong>
                            </span>
                        <?php endif ?>
                    </div>
                    <div class="form-group">
                        <label for="SoThe">Số thẻ</label>
                        <input type="text" name="SoThe" class="form-control<?= isset($errors['SoThe']) ? ' is-invalid' : '' ?>" id="SoThe" value="<?= html_escape($reader->SoThe) ?>" />
                        <?php if (isset($errors['SoThe'])) : ?>
                            <span class="invalid-feedback">
                                <strong><?= $errors['SoThe'] ?></strong>
                            </span>
                        <?php endif ?>
                    </div>
                    <button type="submit" name="submit" class="btn btn-primary">Cập nhật thông tin độc giả</button>
                </form>
            </div>
        </div>
    </div>

</body>

</html>