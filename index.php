<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: employees.php");
    exit();
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Employee Management System</title>
    <link rel="stylesheet" href="/employee-management/assets/style.css">
</head>

<body>
    <div class="container mt-5">
        <div class="jumbotron">
            <h1>Employee Management System</h1>
            <p>Welcome to the Employee Management System</p>
            <br>
            <a href="login.php" class="btn btn-primary">Login</a>
            <a href="register.php" class="btn btn-primary">Register</a>
        </div>
    </div>
</body>

</html>