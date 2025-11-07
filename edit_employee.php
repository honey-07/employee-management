<?php
session_start();
require_once 'config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: employees.php");
    exit();
}

$stmt = $conn->prepare("SELECT * FROM employees WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $_SESSION['user_id']]);
$employee = $stmt->fetch();

if (!$employee) {
    header("Location: employees.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];

    if (!empty($_FILES['photo']['name'])) {
        $photo = $_FILES['photo']['name'];
        $photo_tmp = $_FILES['photo']['tmp_name'];
        $photo_ext = strtolower(pathinfo($photo, PATHINFO_EXTENSION));
        $photo_new = uniqid() . '.' . $photo_ext;
        $target = "uploads/" . $photo_new;

        if (move_uploaded_file($photo_tmp, $target)) {
            if (file_exists("uploads/" . $employee['photo'])) {
                unlink("uploads/" . $employee['photo']);
            }

            $stmt = $conn->prepare("UPDATE employees SET name = ?, email = ?, mobile = ?, photo = ? WHERE id = ? AND user_id = ?");
            $stmt->execute([$name, $email, $mobile, $photo_new, $id, $_SESSION['user_id']]);
        }
    } else {
        $stmt = $conn->prepare("UPDATE employees SET name = ?, email = ?, mobile = ? WHERE id = ? AND user_id = ?");
        $stmt->execute([$name, $email, $mobile, $id, $_SESSION['user_id']]);
    }

    header("Location: employees.php?success=Employee updated successfully");
    exit();
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Edit Employee - Employee Management System</title>
    <link rel="stylesheet" href="/employee-management/assets/style.css">
</head>

<body>
    <div class="container mt-5">
        <h2>Edit Employee</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" class="form-control"
                    value="<?php echo htmlspecialchars($employee['name']); ?>" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control"
                    value="<?php echo htmlspecialchars($employee['email']); ?>" required>
            </div>
            <div class="form-group">
                <label>Mobile</label>
                <input type="text" name="mobile" class="form-control"
                    value="<?php echo htmlspecialchars($employee['mobile']); ?>" required>
            </div>
            <div class="form-group">
                <label>Current Photo</label><br>
                <img src="uploads/<?php echo $employee['photo']; ?>" width="100" class="mb-2"><br>
                <label>New Photo (optional)</label>
                <input type="file" name="photo" class="form-control" accept="image/*">
            </div>
            <button type="submit" class="btn btn-primary">Update Employee</button>
            <a href="employees.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>

</html>