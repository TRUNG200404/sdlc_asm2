<?php
// Kết nối tới cơ sở dữ liệu
$conn = mysqli_connect('localhost', 'root', '', 'your_database_name');

// Kiểm tra kết nối
if (!$conn) {
    die('Không thể kết nối tới cơ sở dữ liệu: ' . mysqli_connect_error());
}

// Xử lý yêu cầu sửa đổi
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['id'];
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

// Lấy ID của học sinh từ tham số trên URL
$user_id = $_GET['id'];

// Truy vấn dữ liệu của học sinh cần sửa
$query = "SELECT * FROM your_table_name WHERE id=$user_id";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sửa thông tin học sinh</title>
    <style>
        /* CSS styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }

        h1 {
            color: #333;
        }

        form {
            margin-bottom: 20px;
        }

        input[type="text"], input[type="password"] {
            padding: 5px;
        }

        input[type="submit"] {
            padding: 5px 10px;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h1>Sửa thông tin học sinh</h1>

    <form method="POST" action="">
        <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
        <input type="text" name="fullname" value="<?php echo $row['fullname']; ?>" placeholder="Họ tên" required>
        <input type="password" name="password" placeholder="Mật khẩu">
        <input type="email" name="email" value="<?php echo $row['email']; ?>" placeholder="Email" required>
        <input type="submit" name="edit" value="Lưu">
    </form>

</body>
</html>