<?php
// index.php - просмотр списка задач
require 'db.php';

$stmt = $pdo->query("
    SELECT * 
    FROM tasks 
    ORDER BY 
        CASE status 
            WHEN 'pending' THEN 1 
            WHEN 'completed' THEN 2 
            ELSE 3 
        END, 
        created_at DESC
");
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

$statusTranslations = [
    'pending' => 'В ожидании',
    'completed' => 'Завершено',
];
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <link rel="stylesheet" href="styles.css">    
    <meta charset="UTF-8">
    <title>Список задач</title> 
</head>
<body>
    <div class="container">
        <h1>Список задач</h1>

        <?php if (!empty($_SESSION['message-success'])): ?>
            <div class="message-success"><?= $_SESSION['message-success'] ?></div>
            <?php unset($_SESSION['message-success']); ?>
        <?php endif; ?>

        <?php if (!empty($_SESSION['message-error'])): ?>
            <div class="message-error"><?= $_SESSION['message-error'] ?></div>
            <?php unset($_SESSION['message-error']); ?>
        <?php endif; ?>

        <a href="create.php" id="add-item" class="button" >Добавить задачу</a>
        <ul>
            <?php foreach ($tasks as $task): ?>
                <li class="task-item">
                    <div class="task-header">
                        <h1><?= htmlspecialchars($task['title']) ?></h1>
                    </div>
                    <p class="task-description"><?= htmlspecialchars($task['description']) ?></p>

                    <a href="update.php?id=<?= $task['id'] ?>" class="button">Редактировать</a>
                    <a href="delete.php?id=<?= $task['id'] ?>" class="button" onclick="return confirm('Вы уверены?')">Удалить</a>

                    <div class="status <?= $task['status'] ?>">
                        <?= $statusTranslations[$task['status']] ?? 'Неизвестный статус' ?>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>
</html>

