<?php
include 'db.php';
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $current = $_POST['current_password'];
    $new = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];

    // Ambil data user
    $stmt = $conn->prepare("SELECT password FROM users WHERE id=?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user || !password_verify($current, $user['password'])) {
        $error = "Password lama salah.";
    } elseif ($new !== $confirm) {
        $error = "Konfirmasi password tidak cocok.";
    } else {
        $hashed = password_hash($new, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET password=? WHERE id=?");
        $stmt->bind_param("si", $hashed, $user_id);
        $stmt->execute();
        $success = "Password berhasil diubah.";
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
        max-width: 600px;
        margin: 60px auto;
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
        margin: 0 10px;
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
        background: #222;
        color: #fff;
        box-sizing: border-box;
    }
    input::placeholder {
        color: #aaa;
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
    .message {
        text-align: center;
        font-weight: bold;
    }
    .error {
        color: #ff4c4c;
    }
    .success {
        color: #5bd75b;
    }
</style>

<div class="container">
    <div class="navbar">
        <a href="index.php">⬅ Kembali</a>
        <a href="logout.php">Logout</a>
    </div>
    <h2>Ubah Password</h2>
    <?php if (isset($error)) echo "<p class='message error'>$error</p>"; ?>
    <?php if (isset($success)) echo "<p class='message success'>$success</p>"; ?>
    <form method="POST">
        <input type="password" name="current_password" required placeholder="Password Lama">
        <input type="password" name="new_password" required placeholder="Password Baru">
        <input type="password" name="confirm_password" required placeholder="Konfirmasi Password Baru">
        <button>Ubah Password</button>
    </form>
</div>
