<?php
include 'db.php';
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }
$id = $_POST['id'];
$stmt = $conn->prepare("UPDATE workspaces SET is_done = NOT is_done WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $id, $_SESSION['user_id']);
$stmt->execute();
header("Location: index.php");
?>