<?php
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $amount = $_POST['amount'];

    $stmt = $conn->prepare("INSERT INTO leaderboard (name, amount) VALUES (?, ?)");
    $stmt->bind_param("ss", $name, $amount);

    if ($stmt->execute()) {
        echo "<script>alert('Sedekah berhasil!'); window.location.href = 'index.php';</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan.'); window.location.href = 'index.php';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
