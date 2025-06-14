<?php
include 'db.php';
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }
$id = $_GET['id'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $stmt = $conn->prepare("UPDATE workspaces SET name=?, description=? WHERE id=? AND user_id=?");
    $stmt->bind_param("ssii", $name, $description, $id, $_SESSION['user_id']);
    $stmt->execute();
    header("Location: index.php");
}
$stmt = $conn->prepare("SELECT * FROM workspaces WHERE id=? AND user_id=?");
$stmt->bind_param("ii", $id, $_SESSION['user_id']);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();
?>
<div class="container">
<div class="navbar">
    <a href="index.php">Kembali</a>
    <a href="logout.php">Logout</a>
</div>
<h2>Edit Workspace</h2>
<form method="POST">
    <input name="name" value="<?= htmlspecialchars($data['name']) ?>" required>
    <textarea name="description"><?= htmlspecialchars($data['description']) ?></textarea>
    <button>Update</button>
</form>
</div>