<?php
// update.php - редактирование задачи
require 'db.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    $_SESSION['message-error'] = "Некорректный идентификатор задачи.";
    header('Location: index.php');
    exit;
}

// Получение текущих данных задачи
$stmt = $pdo->prepare("SELECT * FROM tasks WHERE id = ?");
$stmt->execute([$id]);
$task = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$task) {
    $_SESSION['message-error'] = "Задача не найдена.";
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $status = $_POST['status'] ?? 'pending';

    if (empty($title)) {
        $_SESSION['message-error'] = "Поле 'Заголовок' обязательно для заполнения.";
    } else {
        $stmt = $pdo->prepare("UPDATE tasks SET title = ?, description = ?, status = ? WHERE id = ?");
        $stmt->execute([$title, $description, $status, $id]);

        $_SESSION['message-success'] = "Задача успешно обновлена!";
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
    <title>Редактировать задачу</title>
</head>
<body>
    <div class="container">
        <h1>Редактировать задачу</h1>

        <?php if (!empty($_SESSION['message-error'])): ?>
            <div class="message-error"><?= $_SESSION['message-error'] ?></div>
            <?php unset($_SESSION['message-error']); ?>
        <?php endif; ?>

        <form method="post" novalidate>
            <label for="title">Заголовок</label>
            <input type="text" name="title" id="title" value="<?= htmlspecialchars($task['title']) ?>">

            <label for="description">Описание</label>
            <textarea name="description" id="description"><?= htmlspecialchars($task['description']) ?></textarea>

            <label for="status">Статус</label>
            <select name="status" id="status">
                <option value="pending" <?= $task['status'] === 'pending' ? 'selected' : '' ?>>В ожидании</option>
                <option value="completed" <?= $task['status'] === 'completed' ? 'selected' : '' ?>>Завершено</option>
            </select>

            <button type="submit">Сохранить</button>
        </form>

        <a class="back-link" href="index.php">← Назад к списку задач</a>
    </div>
</body>
</html>
