<?php
session_start();
require_once 'config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'] ?? null;
if ($id) {
    $stmt = $conn->prepare("SELECT photo FROM employees WHERE id = ? AND user_id = ?");
    $stmt->execute([$id, $_SESSION['user_id']]);
    $employee = $stmt->fetch();

    if ($employee) {
        if (file_exists("uploads/" . $employee['photo'])) {
            unlink("uploads/" . $employee['photo']);
        }

        $stmt = $conn->prepare("DELETE FROM employees WHERE id = ? AND user_id = ?");
        $stmt->execute([$id, $_SESSION['user_id']]);
    }
}

header("Location: employees.php?success=Employee deleted successfully");
exit();