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

        h1 {
            color: #333;
            margin-bottom: 20px;
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

        a.logout {
            float: right;
            color: #4CAF50;
            text-decoration: none;
            margin-bottom: 20px;
        }

        form.add-form {
            margin-bottom: 20px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            padding: 20px;
        }

        form.search-form {
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
    <h1>Thông tin học sinh</h1>
    <a class="logout" href="signin_page.php">Đăng xuất</a>

    <form method="POST" action="" class="add-form">
        <input type="text" name="fullname" placeholder="Họ tên" required>
        <input type="password" name="password" placeholder="Mật khẩu" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="submit" name="add" value="Thêm">
    </form>
<form method="GET" action="" class="search-form">
        <input type="text" name="search_keyword" placeholder="Tìm kiếm..." value="<?php echo isset($_GET['search_keyword']) ? $_GET['search_keyword'] : ''; ?>">
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

            $query = "INSERT INTO your_table_name (fullname, password, email) VALUES ('$fullname', '$hashedPassword', '$email')";
            if (mysqli_query($conn, $query)) {
                echo "Học sinh đã được thêm thành công.";
            } else {
                echo "Lỗi: " . mysqli_error($conn);
            }
        }

        // Xử lý thao tác sửa
        if (isset($_POST['edit'])) {
            $user_id = $_POST['user_id'];
            $fullname = $_POST['fullname'];
            $password = $_POST['password'];
            $email = $_POST['email'];

            // Kiểm tra mật khẩu mới
            if (!empty($password)) {
                // Mã hóa mật khẩu mới
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $query = "UPDATE your_table_name SET fullname='$fullname', password='$hashedPassword', email='$email' WHERE id=$user_id";
            } else {
                // Không thay đổi mật khẩu
                $query = "UPDATE your_table_name SET fullname='$fullname', email='$email' WHERE id=$user_id";
            }

            if (mysqli_query($conn, $query)) {
                echo "Thông tin học sinh đã được cập nhật thành công.";
            } else {
                echo "Lỗi: " . mysqli_error($conn);
            }
        }

        // Xử lý thao tác xóa
        if (isset($_GET['delete'])) {
            $user_id = $_GET['delete'];

            $query = "DELETE FROM your_table_name WHERE id=$user_id";
            if (mysqli_query($conn, $query)) {
                echo "Học sinh đã được xóa thành công.";
            } else {
                echo "Lỗi: " . mysqli_error($conn);
            }
        }

        // Truy vấn dữ liệu từ bảng 'users'
        if(isset($_GET['search'])) {
            $search_keyword = $_GET['search_keyword'];
            $query = "SELECT * FROM your_table_name WHERE fullname LIKE '%$search_keyword%' OR email LIKE '%$search_keyword%'";
} else {
            $query = "SELECT * FROM your_table_name";
        }
        
        $result = mysqli_query($conn, $query);
        while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['fullname']; ?></td>
                <td><?php echo $row['email']; ?></td>
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
                        <input type="text" name="fullname" placeholder="Họ tên" value="<?php echo $row['fullname']; ?>" required>
                        <input type="password" name="password" placeholder="Mật khẩu">
                        <input type="email" name="email" placeholder="Email" value="<?php echo $row['email']; ?>" required>
                        <input type="submit" name="edit" value="Lưu">
                    </form>
                </td>
            </tr>
        <?php } ?>
    </table>

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