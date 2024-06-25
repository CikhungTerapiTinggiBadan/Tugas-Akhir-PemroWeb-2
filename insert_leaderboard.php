<?php
session_start();
include 'connect.php';

$name = $_GET['name'];
$amount = $_GET['amount'];

// Insert the data into the leaderboard table
$sql = "INSERT INTO leaderboard (name, amount) VALUES ('$name', '$amount')";
if ($conn->query($sql) === TRUE) {
    // Redirect to index_no_auth.php after successful insertion
    header("Location: index.php");
    exit();
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
