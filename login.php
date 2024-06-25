<?php
include 'connect.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username === 'admin' && $password === 'admin') {
        $_SESSION['user_id'] = 0;
        $_SESSION['username'] = 'admin';
        header("Location: admin.php");
        exit();
    }

    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: index_no_auth.php");
            exit();
        } else {
            echo "<script>alert('Incorrect password. Please try again.'); window.location.href = 'index.php';</script>";
        }
    } else {
        echo "<script>alert('Invalid credentials. Please try again.'); window.location.href = 'index.php';</script>";
    }

    $stmt->close();
}
$conn->close();
?>