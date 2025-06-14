<?php
include 'db.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $due_date = $_POST['due_date'];
    $stmt = $conn->prepare("INSERT INTO workspaces (user_id, name, description, due_date) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $_SESSION['user_id'], $name, $description, $due_date);
    $stmt->execute();
    header("Location: index.php");
    exit;
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
    input, textarea, button {
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
    input::placeholder, textarea::placeholder {
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
</style>

<div class="container">
    <div class="navbar">
        <a href="index.php">⬅ Kembali</a>
        <a href="logout.php">Logout</a>
    </div>
    <h2>Tambah Workspace</h2>
    <form method="POST">
        <input name="name" required placeholder="Nama Workspace" value="<?= isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '' ?>">
        <textarea name="description" placeholder="Deskripsi"><?= isset($_POST['description']) ? htmlspecialchars($_POST['description']) : '' ?></textarea>
        <input type="date" name="due_date" required value="<?= isset($_POST['due_date']) ? $_POST['due_date'] : '' ?>">
        <button>Simpan</button>
    </form>
</div>
