<?php
include 'db.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$user_id = $_SESSION['user_id'];
$username = isset($_SESSION['username']) ? $_SESSION['username'] : '';
$search = isset($_GET['search']) ? $_GET['search'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'due_asc';

switch ($sort) {
    case 'name_asc':
        $order = 'name ASC';
        break;
    case 'name_desc':
        $order = 'name DESC';
        break;
    case 'due_desc':
        $order = 'due_date DESC';
        break;
    case 'due_asc':
    default:
        $order = 'due_date ASC';
        break;
}

$stmt = $conn->prepare("SELECT * FROM workspaces WHERE user_id = ? AND name LIKE ? ORDER BY $order");
$like = "%$search%";
$stmt->bind_param("is", $user_id, $like);
$stmt->execute();
$result = $stmt->get_result();
?>
<style>
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    margin: 0;
    background:rgb(0, 0, 0);
    color: #222;
}
.container {
    max-width: 800px;
    margin: 40px auto;
    padding: 20px;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}
input, textarea, button, select {
    width: 100%;
    padding: 10px;
    margin: 8px 0;
    border-radius: 5px;
    border: 1px solid #ccc;
    box-sizing: border-box;
    font-size: 16px;
}
button {
    background-color: #f5c518;
    color: #000;
    border: none;
    cursor: pointer;
    font-weight: bold;
}
button:hover {
    background-color: #e0b000;
}
a {
    text-decoration: none;
    color: #f5c518;
    font-weight: bold;
}
a:hover {
    text-decoration: underline;
}
.navbar {
    background: #111;
    padding: 10px;
    color: #fff;
    text-align: center;
    border-bottom: 4px solid #f5c518;
}
.navbar a, .navbar span {
    color: white;
    margin: 0 10px;
    display: inline-block;
}
.completed {
    text-decoration: line-through;
    color: gray;
}
form.inline {
    display: inline;
}
hr {
    border: none;
    border-top: 1px solid #ddd;
    margin: 10px 0;
}
ul {
    list-style: none;
    padding: 0;
}
</style>
<div class="container">
<div class="navbar">
    <a href="index.php">Home</a>
    <a href="create.php">Tambah Workspace</a>
    <a href="ubah_password.php">Ubah Password</a>
    <span>👤 <?= $username ? htmlspecialchars($username) : 'Guest' ?></span>
    <a href="logout.php">Logout</a>
</div>
<h2>Workspace Saya</h2>
<form method="GET">
    <input name="search" placeholder="Cari workspace..." value="<?= htmlspecialchars($search) ?>">
    <select name="sort">
        <option value="due_asc" <?= $sort === 'due_asc' ? 'selected' : '' ?>>Deadline Terdekat</option>
        <option value="due_desc" <?= $sort === 'due_desc' ? 'selected' : '' ?>>Deadline Terlama</option>
        <option value="name_asc" <?= $sort === 'name_asc' ? 'selected' : '' ?>>Nama A-Z</option>
        <option value="name_desc" <?= $sort === 'name_desc' ? 'selected' : '' ?>>Nama Z-A</option>
    </select>
    <button>Cari</button>
</form>
<ul>
    <?php while ($row = $result->fetch_assoc()): ?>
        <li class="<?= $row['is_done'] ? 'completed' : '' ?>">
            <strong><?= htmlspecialchars($row['name']) ?></strong><br>
            <?= htmlspecialchars($row['description']) ?><br>
            <small>Deadline: <?= $row['due_date'] ?></small><br>
            <form class="inline" method="POST" action="toggle_done.php">
                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                <button><?= $row['is_done'] ? 'Batalkan Selesai' : 'Tandai Selesai' ?></button>
            </form>
            <a href="edit.php?id=<?= $row['id'] ?>">Edit</a> |
            <a href="delete.php?id=<?= $row['id'] ?>" onclick="return confirm('Hapus workspace ini?')">Hapus</a>
        </li><hr>
    <?php endwhile; ?>
</ul>
</div>
