<?php
session_start();
$name = $_POST['name'] ?? '';
$amount = $_POST['amount'] ?? 0;

if (!empty($name) && $amount > 0) {
    header("Location: slot_machine.php?name=$name&amount=$amount");
    exit;
} else {
    echo "Name and amount are required!";
    echo "<a href='sedekah.php'>Back</a>";
}
?>
