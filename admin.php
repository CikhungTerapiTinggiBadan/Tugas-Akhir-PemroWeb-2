<?php
session_start();
include 'connect.php';

// Fetch registration data
$users_sql = "SELECT id, first_name, last_name, username FROM users";
$users_result = $conn->query($users_sql);

// Fetch leaderboard data
$leaderboard_sql = "SELECT id, name, amount FROM leaderboard ORDER BY amount DESC";
$leaderboard_result = $conn->query($leaderboard_sql);

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Add new registration
    if (isset($_POST['add_user'])) {
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $username = $_POST['username'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, username, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $first_name, $last_name, $username, $password);
        $stmt->execute();
        $stmt->close();
        header("Location: admin.php");
        exit();
    }

    // Update registration
    if (isset($_POST['update_user'])) {
        $user_id = $_POST['user_id'];
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $username = $_POST['username'];

        $stmt = $conn->prepare("UPDATE users SET first_name = ?, last_name = ?, username = ? WHERE id = ?");
        $stmt->bind_param("sssi", $first_name, $last_name, $username, $user_id);
        $stmt->execute();
        $stmt->close();
        header("Location: admin.php");
        exit();
    }

    // Delete registration
    if (isset($_POST['delete_user'])) {
        $user_id = $_POST['user_id'];

        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->close();
        header("Location: admin.php");
        exit();
    }

    // Add new leaderboard entry
    if (isset($_POST['add_leaderboard'])) {
        $name = $_POST['name'];
        $amount = $_POST['amount'];

        $stmt = $conn->prepare("INSERT INTO leaderboard (name, amount) VALUES (?, ?)");
        $stmt->bind_param("si", $name, $amount);
        $stmt->execute();
        $stmt->close();
        header("Location: admin.php");
        exit();
    }

    // Update leaderboard entry
    if (isset($_POST['update_leaderboard'])) {
        $leaderboard_id = $_POST['leaderboard_id'];
        $name = $_POST['name'];
        $amount = $_POST['amount'];

        $stmt = $conn->prepare("UPDATE leaderboard SET name = ?, amount = ? WHERE id = ?");
        $stmt->bind_param("sii", $name, $amount, $leaderboard_id);
        $stmt->execute();
        $stmt->close();
        header("Location: admin.php");
        exit();
    }

    // Delete leaderboard entry
    if (isset($_POST['delete_leaderboard'])) {
        $leaderboard_id = $_POST['leaderboard_id'];

        $stmt = $conn->prepare("DELETE FROM leaderboard WHERE id = ?");
        $stmt->bind_param("i", $leaderboard_id);
        $stmt->execute();
        $stmt->close();
        header("Location: admin.php");
        exit();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            padding: 20px;
        }
        .container {
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            width: 80%;
        }
        h2 {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f4f4f9;
        }
        form {
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 10px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input, .form-group button {
            padding: 10px;
            width: 100%;
            box-sizing: border-box;
        }
        .form-group button {
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        .form-group button:hover {
            background-color: #218838;
        }
        .delete-button {
            background-color: #dc3545;
        }
        .delete-button:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Manage Registrations</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Username</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($users_result->num_rows > 0): ?>
                    <?php while($user = $users_result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $user['id'] ?></td>
                            <td><?= $user['first_name'] ?></td>
                            <td><?= $user['last_name'] ?></td>
                            <td><?= $user['username'] ?></td>
                            <td>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                    <button type="submit" name="delete_user" class="delete-button">Delete</button>
                                </form>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                    <input type="text" name="first_name" value="<?= $user['first_name'] ?>" required>
                                    <input type="text" name="last_name" value="<?= $user['last_name'] ?>" required>
                                    <input type="text" name="username" value="<?= $user['username'] ?>" required>
                                    <button type="submit" name="update_user">Update</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">No records found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <h2>Add New Registration</h2>
        <form method="POST">
            <div class="form-group">
                <label for="first_name">First Name</label>
                <input type="text" id="first_name" name="first_name" required>
            </div>
            <div class="form-group">
                <label for="last_name">Last Name</label>
                <input type="text" id="last_name" name="last_name" required>
            </div>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <button type="submit" name="add_user">Add User</button>
            </div>
        </form>

        <h2>Manage Leaderboard</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Amount</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($leaderboard_result->num_rows > 0): ?>
                    <?php while($entry = $leaderboard_result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $entry['id'] ?></td>
                            <td><?= $entry['name'] ?></td>
                            <td><?= $entry['amount'] ?></td>
                            <td>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="leaderboard_id" value="<?= $entry['id'] ?>">
                                    <button type="submit" name="delete_leaderboard" class="delete-button">Delete</button>
                                </form>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="leaderboard_id" value="<?= $entry['id'] ?>">
                                    <input type="text" name="name" value="<?= $entry['name'] ?>" required>
                                    <input type="number" name="amount" value="<?= $entry['amount'] ?>" required>
                                    <button type="submit" name="update_leaderboard">Update</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">No records found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <h2>Add New Leaderboard Entry</h2>
        <form method="POST">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="amount">Amount</label>
                <input type="number" id="amount" name="amount" required>
            </div>
            <div class="form-group">
                <button type="submit" name="add_leaderboard">Add Entry</button>
            </div>
        </form>
    </div>
</body>
</html>
