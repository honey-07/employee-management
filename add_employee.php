<?php
session_start();
require_once 'config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $mobile = $_POST['mobile'];

    // Handle file upload
    $photo = $_FILES['photo']['name'];
    $photo_tmp = $_FILES['photo']['tmp_name'];
    $photo_ext = strtolower(pathinfo($photo, PATHINFO_EXTENSION));
    $photo_new = uniqid() . '.' . $photo_ext;
    $target = "uploads/" . $photo_new;

    if (move_uploaded_file($photo_tmp, $target)) {
        $stmt = $conn->prepare("INSERT INTO employees (name, email, password, mobile, photo, user_id) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $email, $password, $mobile, $photo_new, $_SESSION['user_id']]);
        header("Location: employees.php?success=Employee added successfully");
        exit();
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Add Employee - Employee Management System</title>
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>
    <div class="container mt-5">
        <h2>Add Employee</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Mobile</label>
                <input type="text" name="mobile" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Photo</label>
                <input type="file" name="photo" class="form-control" accept="image/*" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Employee</button>
            <a href="employees.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>

</html>