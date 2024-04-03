<?php
require_once 'connect.php';
$MaDocGia = $_GET['id'];
$query = "SELECT * FROM docgia WHERE maDocGia = ?";
$statement = $pdo->prepare($query);
$statement->execute([$MaDocGia]);
$reader = $statement->fetch(PDO::FETCH_ASSOC);
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $MaDocGia = $_POST['id'];
    $TenDocGia = $_POST['TenDocGia'];
    $DiaChi = $_POST['DiaChi'];
    $SoThe = $_POST['SoThe'];

    $query_update = "UPDATE docgia SET MaDocGia = ?, TenDocGia = ?, DiaChi = ?, SoThe = ? WHERE maDocGia = ?";
    $statement_update = $pdo->prepare($query_update);
    $statement_update->execute([$MaDocGia, $TenDocGia, $DiaChi, $SoThe, $MaDocGia]);
    header("Location: index.php");
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
                    <!-- Sử dụng $MaDocGia thay vì $reader->MaDocGia -->
                    <input type="hidden" name="id" value="<?php echo $_GET['id'] ?>">
                    <div class="form-group">
                        <label for="TenDocGia">Tên độc giả</label>
                        <input type="text" name="TenDocGia" class="form-control" id="TenDocGia" value="<?php echo $reader['TenDocGia'] ?>" />
                        <?php if (isset($errors['TenDocGia'])) : ?>
                            <span class="invalid-feedback">
                                <strong><?= $errors['TenDocGia'] ?></strong>
                            </span>
                        <?php endif ?>
                    </div>
                    <div class="form-group">
                        <label for="DiaChi">Địa chỉ</label>
                        <input type="text" name="DiaChi" class="form-control<?= isset($errors['DiaChi']) ? ' is-invalid' : '' ?>" id="DiaChi" value="<?php echo $reader['DiaChi'] ?>" />
                        <?php if (isset($errors['DiaChi'])) : ?>
                            <span class="invalid-feedback">
                                <strong><?= $errors['DiaChi'] ?></strong>
                            </span>
                        <?php endif ?>
                    </div>
                    <div class="form-group">
                        <label for="SoThe">Số thẻ</label>
                        <input type="text" name="SoThe" class="form-control<?= isset($errors['SoThe']) ? ' is-invalid' : '' ?>" id="SoThe" value="<?php echo $reader['SoThe'] ?>" />
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