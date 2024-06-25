<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sedekah</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
            background: url('pohonuang.jpg') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #333;
        }
        .form-container {
            background-color: rgba(255, 255, 255, 0.9); /* Semi-transparent background */
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            width: 350px;
            text-align: center;
        }
        .form-container h2 {
            margin-bottom: 20px;
            color: #007bff;
        }
        .form-container input {
            display: block;
            margin-bottom: 15px;
            padding: 12px 15px;
            width: 100%;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }
        .form-container button {
            padding: 12px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            margin-bottom: 10px;
            font-size: 16px;
        }
        .form-container button:hover {
            background-color: #0056b3;
        }
        .form-container a {
            display: block;
            margin-top: 20px;
            color: #007bff;
            text-decoration: none;
            font-size: 14px;
        }
        .form-container a:hover {
            text-decoration: underline;
        }
        .form-container form {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Sedekah</h2>
        <form action="sedekah_langsung.php" method="POST">
            <input type="text" name="name" placeholder="Nama" required>
            <input type="number" name="amount" placeholder="Nominal" required>
            <button type="submit">Sedekah Langsung</button>
        </form>
        <form action="sedekah_roll.php" method="POST">
            <input type="text" name="name" placeholder="Nama" required>
            <input type="number" name="amount" placeholder="Nominal" required>
            <button type="submit">Sedekah Roll</button>
        </form>
        <a href="index_no_auth.php">Kembali</a>
    </div>
</body>
</html>
