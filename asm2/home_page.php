<!DOCTYPE html>
<html>
<head>
    <title>Home Page</title>
    <style>
        /* CSS styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        h1 {
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 20px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .logout {
            float: right;
            color: #4CAF50;
            text-decoration: none;
            margin-bottom: 20px;
        }

        .add-form, .search-form {
            margin-bottom: 20px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            padding: 20px;
        }

        input[type="text"], input[type="password"], input[type="email"], input[type="submit"] {
            padding: 10px;
            margin: 5px;
            border: none;
            border-radius: 4px;
            width: calc(100% - 22px);
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: #fff;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .edit-btn, .delete-btn {
            padding: 6px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .edit-btn {
            background-color: #3498db;
            color: #fff;
            margin-right: 5px;
        }

        .delete-btn {
            background-color: #e74c3c;
            color: #fff;
        }

        .edit-btn:hover, .delete-btn:hover {
            background-color: #2980b9;
        }

        /* Hide edit form initially */
        .edit-form {
            display: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Thông tin học sinh</h1>
        <a class="logout" href="signin_page.php">Đăng xuất</a>

        <form method="POST" action="" class="add-form">
            <input type="text" name="fullname" placeholder="Họ tên" required>
            <input type="password" name="password" placeholder="Mật khẩu" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="submit" name="add" value="Thêm">
        </form>

        <form method="GET" action="" class="search-form">
            <input type="text" name="search_keyword" placeholder="Tìm kiếm..." value="<?php echo isset($_GET['search_keyword']) ? htmlspecialchars($_GET['search_keyword']) : ''; ?>">
            <input type="submit" name="search" value="Tìm">
        </form>

        <table>
            <tr>
                <th>ID</th>
                <th>Họ tên</th>
                <th>Email</th>
                <th>Thao tác</th>
            </tr>
            <?php
            // Kết nối tới cơ sở dữ liệu
            $conn = mysqli_connect('localhost', 'root', '', 'your_database_name');

            // Kiểm tra kết nối
            if (!$conn) {
                die('Không thể kết nối tới cơ sở dữ liệu: ' . mysqli_connect_error());
            }

            // Xử lý thao tác thêm
            if (isset($_POST['add'])) {
                $fullname = $_POST['fullname'];
                $password = $_POST['password'];
                $email = $_POST['email'];

                // Mã hóa mật khẩu
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                $query = "INSERT INTO your_table_name (fullname, password, email) VALUES (?, ?, ?)";
                $stmt = mysqli_prepare($conn, $query);
                mysqli_stmt_bind_param($stmt, "sss", $fullname, $hashedPassword, $email);

                if (mysqli_stmt_execute($stmt)) {
                    echo "<meta http-equiv='refresh' content='0'>"; // Tải lại trang sau khi thêm
                } else {
                    echo "Lỗi: " . mysqli_error($conn);
                }
                mysqli_stmt_close($stmt);
            }

            // Truy vấn dữ liệu từ bảng 'users'
            if(isset($_GET['search'])) {
                $search_keyword = '%' . $_GET['search_keyword'] . '%';
                $query = "SELECT * FROM your_table_name WHERE fullname LIKE ? OR email LIKE ?";
                $stmt = mysqli_prepare($conn, $query);
                mysqli_stmt_bind_param($stmt, "ss", $search_keyword, $search_keyword);
                mysqli_stmt_execute($stmt);
            } else {
                $query = "SELECT * FROM your_table_name";
                $stmt = mysqli_prepare($conn, $query);
                mysqli_stmt_execute($stmt);
            }

            // Hiển thị dữ liệu
            $result = mysqli_stmt_get_result($stmt);
            while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['fullname']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td>
                        <button class="edit-btn" onclick="showEditForm(<?php echo $row['id']; ?>)">Sửa</button>
                        <a class="delete-btn" href="delete.php?id=<?php echo $row['id']; ?>">Xóa</a>
                    </td>
                </tr>
                <!-- Edit form for each row -->
                <tr class="edit-form" id="edit-form-<?php echo $row['id']; ?>">
                    <td colspan="4">
                        <form method="POST" action="">
                            <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                            <input type="text" name="fullname" placeholder="Họ tên" value="<?php echo htmlspecialchars($row['fullname']); ?>" required>
                            <input type="password" name="password" placeholder="Mật khẩu">
                            <input type="email" name="email" placeholder="Email" value="<?php echo htmlspecialchars($row['email']); ?>" required>
                            <input type="submit" name="edit" value="Lưu">
                        </form>
                    </td>
                </tr>
            <?php }
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
            ?>
        </table>
    </div>

    <script>
        // Function to show edit form for a specific row
        function showEditForm(id) {
            var editForm = document.getElementById('edit-form-' + id);
            if (editForm.style.display === "none") {
                editForm.style.display = "table-row";
            } else {
                editForm.style.display = "none";
            }
        }
    </script>
</body>
</html>
