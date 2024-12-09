<?php
// delete.php - удаление задачи
require 'db.php';

$id = $_GET['id'];

$stmt = $pdo->prepare("DELETE FROM tasks WHERE id = ?");
$stmt->execute([$id]);

$_SESSION['message-success'] = "Задача успешно удалена!";
header('Location: index.php');
exit;
?>
