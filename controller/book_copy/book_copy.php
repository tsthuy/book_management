    <?php
    require_once 'C:\xampp\New folder\htdocs\project\config\db_connect.php';
    require_once 'C:\xampp\New folder\htdocs\project\model\book_copy_management.php';
    require_once 'C:\xampp\New folder\htdocs\project\model\book_management.php';

    session_start();

    $db = new db_connect();
    $connection = $db->connect();

    // Khởi tạo đối tượng quản lý sách
    $book = new Book\sach($connection);

    // Khởi tạo đối tượng quản lý bản sao sách
    $bookcopy = new BookCopy\bansaosach($connection);

    // Xử lý tìm kiếm sách

    $searchResultsBooks = [];
    if (isset($_GET['search'])) {
        $searchResultsBooks = $book->search();
    }

    // Xử lý tìm kiếm bản sao của sách
    $searchResultsCopies = [];
    $maSach = isset($_GET['maSach']) ? $_GET['maSach'] : '';
    $maNXB = isset($_GET['maNXB']) ? $_GET['maNXB'] : '';

    if (isset($_GET['maSach'])) {
        $maSach = $_GET['maSach'];
        $searchResultsCopies = $bookcopy->searchCopy($maSach);
    }

    if (isset($_GET['maNXB'])) {
        $maNXB = $_GET['maNXB'];
        $searchResultsCopies = $bookcopy->searchCopy($maNXB);
    }

    $searchResults = [];
    if (isset($_GET['searchcopy'])) {
        $searchResults = $bookcopy->searchCopy();
    }



    ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="./style_copy.css">
        <title>Quản lý bản sao sách</title>
    </head>

    <body>

        <header>
            <div class="narbar">
                <div class="book">
                    <a href="../../index.php">Quản lý sách</a>
                </div>
                <div class="copy-book">
                    <a href="book_copy.php">Quản lý bản sao sách</a>
                </div>
            </div>
        </header>

        <h1>Quản lý bản sao sách</h1>

        <!-- Hiển thị thông báo kết quả -->
        <?php if (isset($_SESSION['add_copy_result'])) : ?>
            <p><?php echo $_SESSION['add_copy_result']; ?></p>
            <?php unset($_SESSION['add_copy_result']); ?>
        <?php endif; ?>

        <!-- Form tìm kiếm sách -->
        <h2>Tìm kiếm sách</h2>
        <form action="book_copy.php" method="GET">
            <input type="text" name="search" placeholder="Tìm kiếm sách">
            <button type="submit">Tìm kiếm</button>
        </form>

        <!-- Danh sách kết quả tìm kiếm sách -->

        <?php if (!empty($searchResultsBooks)) : ?>
            <h2>Kết quả tìm kiếm sách</h2>
            <table>
                <tr>
                    <th>Mã Sách</th>
                    <th>Tên Sách</th>
                    <th>Thao Tác</th>
                </tr>
                <?php foreach ($searchResultsBooks as $book) : ?>
                    <tr>
                        <td><?php echo $book['maSach']; ?></td>
                        <td><?php echo $book['tenSach']; ?></td>
                        <td>
                            <form action="book_copy.php" method="GET" class="book-action">
                                <input type="hidden" name="maSach" value="<?php echo $book['maSach']; ?>">
                                <input type="hidden" name="maNXB" value="<?php echo $book['maNXB']; ?>">
                                <input type="hidden" name="searchcopy" value="<?php echo $book['maSach'] ?>">
                                <button type="submit">Xem bản sao</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>

        <?php if (!empty($maSach)) : ?>
            <?php if (!empty($searchResultsCopies)) : ?>
                <!-- Form tìm kiếm bản sao của sách -->
                <h2>Tìm kiếm bản sao sách</h2>
                <form action="book_copy.php" method="GET">
                    <input type="hidden" name="maSach" value="<?php echo $maSach; ?>">
                    <input type="text" name="searchcopy" placeholder="Tìm kiếm bản sao của sách">
                    <button type="submit">Tìm kiếm</button>
                </form>

                <h2>Danh sách bản sao của sách</h2>
                <table>
                    <tr>
                        <th>Mã Bản Sao</th>
                        <th>Năm Xuất Bản</th>
                        <th>Tình Trạng Mượn</th>
                        <th>Actions</th>
                    </tr>
                    <?php foreach ($searchResultsCopies as $copy) : ?>
                        <tr>
                            <td><?php echo $copy['maBanSao']; ?></td>
                            <td><?php echo $copy['namXB']; ?></td>
                            <td><?php echo $copy['ttMuon']; ?></td>
                            <td>
                                <a href="./edit_book_copy.php?maBanSao=<?php echo $copy['maBanSao']; ?>">Chỉnh sửa</a> |
                                <a href="./delete_copy_book.php?maBanSao=<?php echo $copy['maBanSao']; ?>" onclick="return confirm('Bạn có muốn xóa bản sao?')">Xóa</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($searchResultsCopies)) : ?>
                        <tr>
                            <td colspan="4">Chưa có bản sao.</td>
                        </tr>
                    <?php endif; ?>
                </table>
            <?php endif; ?>

            <!-- Form thêm bản sao mới -->
            <?php if (!empty($maSach) || !empty($searchResultsCopies)) : ?>
                <h2>Thêm bản sao mới</h2>
                <form action="./add_book_copy.php" method="POST">
                    <input type="text" name="maSach" value="<?php echo htmlspecialchars($_GET['maSach'] ?? ''); ?>">
                    <input type="text" name="maBanSao" placeholder="Mã bản sao">
                    <input type="text" name="namXB" placeholder="Năm xuất bản">
                    <input type="text" name="ttMuon" placeholder="Tình trạng mượn">
                    <input type="text" name="maNXB" value="<?php echo htmlspecialchars($_GET['maNXB'] ?? ''); ?>">
                    <button type="submit">
                        <a href="./add_book_copy.php?php echo $maSach; ?>&maNXB=<?php echo $maNXB; ?>">Thêm bản sao</a>
                    </button>
                </form>
            <?php endif; ?>
        <?php endif; ?>

    </body>

    </html>