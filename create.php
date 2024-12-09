<?php
// create.php - создание новой задачи
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);

    if (empty($title)) {
        $_SESSION['message-error'] = "Поле 'Заголовок' обязательно для заполнения.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO tasks (title, description, status, created_at) VALUES (?, ?, 'pending', NOW())");
        $stmt->execute([$title, $description]);

        $_SESSION['message-success'] = "Задача успешно создана!";
        header('Location: index.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <link rel="stylesheet" href="styles.css">
    <meta charset="UTF-8">
    <title>Добавить задачу</title>
</head>
<body>
    <div class="container">
        <h1>Добавить задачу</h1>

        <?php if (!empty($_SESSION['message-error'])): ?>
            <div class="message-error"><?= $_SESSION['message-error'] ?></div>
            <?php unset($_SESSION['message-error']); ?>
        <?php endif; ?>

        <form method="post" novalidate>
            <label for="title">Заголовок</label>
            <input type="text" name="title" id="title">

            <label for="description">Описание</label>
            <textarea name="description" id="description"></textarea>

            <button type="submit">Создать</button>
        </form>

        <a class="back-link" href="index.php">← Назад к списку задач</a>
    </div>
</body>
</html>
