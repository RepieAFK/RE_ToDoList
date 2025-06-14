<?php
include 'db.php';
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $password);
    if ($stmt->execute()) {
        header("Location: login.php");
        exit;
    } else {
        $error = "Username sudah digunakan.";
    }
}
?>
<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        margin: 0;
        background-color: #111;
        color: #fff;
    }
    .container {
        max-width: 400px;
        margin: 80px auto;
        padding: 30px;
        background: #1c1c1c;
        border-radius: 10px;
        box-shadow: 0 0 10px #000;
    }
    .navbar {
        text-align: center;
        margin-bottom: 20px;
    }
    .navbar a {
        color: #f5c518;
        font-weight: bold;
        text-decoration: none;
    }
    .navbar a:hover {
        text-decoration: underline;
    }
    h2 {
        text-align: center;
        color: #f5c518;
    }
    input, button {
        width: 100%;
        padding: 10px;
        margin: 10px 0;
        border-radius: 5px;
        border: 1px solid #333;
        font-size: 16px;
        box-sizing: border-box;
    }
    input {
        background: #222;
        color: #fff;
    }
    input::placeholder {
        color: #888;
    }
    button {
        background-color: #f5c518;
        color: #000;
        font-weight: bold;
        border: none;
        cursor: pointer;
    }
    button:hover {
        background-color: #e0b000;
    }
    .error {
        color: red;
        text-align: center;
        margin-bottom: 10px;
    }
</style>

<div class="container">
    <div class="navbar"><a href="login.php">Sudah punya akun? Login</a></div>
    <h2>Register</h2>
    <?php if (!empty($error)): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="POST">
        <input name="username" required placeholder="Username">
        <input type="password" name="password" required placeholder="Password">
        <button>Register</button>
    </form>
</div>
