<?php
session_start();
require_once 'config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$stmt = $conn->prepare("SELECT * FROM employees WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$employees = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>

<head>
    <title>Employees - Employee Management System</title>
    <link rel="stylesheet" href="/employee-management/assets/style.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">EMS</a>
            <div class="navbar-nav ml-auto">
                <span class="navbar-text mr-3">Welcome, <?php echo $_SESSION['user_name']; ?></span>
                <a href="logout.php" class="btn btn-outline-light">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Employees</h2>
            <a href="add_employee.php" class="btn btn-primary">Add Employee</a>
        </div>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success"><?php echo $_GET['success']; ?></div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Photo</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($employees as $employee): ?>
                        <tr>
                            <td><img src="uploads/<?php echo $employee['photo']; ?>" width="50" class="rounded-circle"></td>
                            <td><?php echo htmlspecialchars($employee['name']); ?></td>
                            <td><?php echo htmlspecialchars($employee['email']); ?></td>
                            <td><?php echo htmlspecialchars($employee['mobile']); ?></td>
                            <td>
                                <a href="edit_employee.php?id=<?php echo $employee['id']; ?>"
                                    class="btn btn-sm btn-info">Edit</a>
                                <a href="delete_employee.php?id=<?php echo $employee['id']; ?>"
                                    class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($employees)): ?>
                        <tr>
                            <td colspan="5" class="text-center">No employees found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>