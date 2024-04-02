<header class="bg-gray-800 text-white p-4">
    <div class="container mx-auto flex justify-between items-center">
        <a href="home.php" class="text-2xl font-bold">Hệ thống quản lý thư viện</a>
        <!-- Phần tìm kiếm -->
        <form action="home.php" method="GET" class="flex items-center">
            <input type="text" name="keyword" placeholder="Tìm kiếm sách" class="p-2 border rounded-md mr-2 text-black">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md">Tìm kiếm</button>
        </form>
        <!-- Kết thúc phần tìm kiếm -->

        <!-- Dropdown danh sách thể loại sách -->
        <div class="relative">
            <select onchange="location = this.value;" class="block appearance-none w-full bg-blue-500 border 
                border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 
                rounded-md shadow leading-tight
                 focus:outline-none focus:bg-black focus:border-gray-500">
                <option selected disabled>Chọn thể loại sách</option>
                <?php foreach ($categories as $category) : ?>
                    <option value="home.php?category=<?php echo urlencode($category['tenLoai']); ?>"><?php echo $category['tenLoai']; ?></option>
                <?php endforeach; ?>
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <path d="M14.293 7.293a1 1 0 0 0-1.414-1.414l-3 3a1 1 0 0 0-.001 1.415l3 3a1 1 0 0 0 1.415-1.415L11.414 11H16a1 1 0 0 0 0-2h-4.586l2.293-2.293z" />
                </svg>
            </div>
        </div>
        <!-- Kết thúc dropdown danh sách thể loại sách -->

        <!-- Thêm phần Độc giả -->
        <a href="index.php" class="hover:text-gray-300">Độc giả</a>

        <!-- Thêm phần Thẻ thư viện -->
        <a href="thethuvien.php" class="hover:text-gray-300">Thẻ thư viện</a>
        <nav>
            <ul class="flex space-x-4">
                <li><a href="book_manage.php" class="hover:text-gray-300">Quản lý sách</a></li>
                <li><a href="return.php" class="hover:text-gray-300">Trả sách</a></li>
                <li><a href="lichsumuontra.php" class="hover:text-gray-300">Lịch sử mượn trả</a></li>

                <li><a href="login.php" class="hover:text-gray-300">
                        <?php
                        // Kiểm tra trạng thái đăng nhập
                        session_start();
                        if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
                            // Hiển thị nút Logout nếu người dùng đã đăng nhập
                            echo '<a href="Logout.php">Log out</a>';
                        } else {
                            // Hiển thị nút Đăng Nhập nếu người dùng chưa đăng nhập
                            echo '<a href="login.php">Đăng Nhập</a>';
                        }

                        ?>
                    </a></li>
            </ul>
        </nav>
    </div>
</header>