<?php
session_start();
require_once('../config/conexao.php');

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'] ?? '';

// Adiciona nova tarefa
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title'], $_POST['description'])) {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);

    if (!empty($title)) {
        $stmt = $conn->prepare("INSERT INTO tasks (user_id, title, description, is_done, created_at) VALUES (?, ?, ?, 0, NOW())");
        $stmt->bind_param("iss", $user_id, $title, $description);
        $stmt->execute();
        $stmt->close();
    }
}

// Marcar como feita
if (isset($_GET['done'])) {
    $id = (int)$_GET['done'];
    $stmt = $conn->prepare("UPDATE tasks SET is_done = 1 WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $id, $user_id);
    $stmt->execute();
    $stmt->close();
    header('Location: tasks.php');
    exit;
}

// Excluir tarefa
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM tasks WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $id, $user_id);
    $stmt->execute();
    $stmt->close();
    header('Location: tasks.php');
    exit;
}

// Buscar tarefas
$stmt = $conn->prepare("SELECT * FROM tasks WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$tarefas = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To Do List</title>

    <link rel="stylesheet" href="../assets/css/index.css">
    <link rel="stylesheet" href="../assets/css/tasks.css">
</head>
<body>
<?php include('../templates/sidebar.php'); ?>

<div class="container">
    <h2 class="welcome">Bem-vindo, <?= htmlspecialchars($user_name) ?> 👋</h2>

    <form method="POST" class="task-form">
        <input type="text" name="title" placeholder="Título da tarefa" required>
        <textarea name="description" placeholder="Descrição (opcional)" rows="3"></textarea>
        <button type="submit">Adicionar Tarefa</button>
    </form>

    <h3 class="task-title">Suas Tarefas</h3>

    <div class="task-list">
        <?php if (empty($tarefas)): ?>
            <p class="no-tasks">Nenhuma tarefa cadastrada ainda.</p>
        <?php else: ?>
            <?php foreach ($tarefas as $tarefa): ?>
                <div class="task-card <?= $tarefa['is_done'] ? 'done' : '' ?>">
                    <h4><?= htmlspecialchars($tarefa['title']) ?></h4>
                    <p><?= nl2br(htmlspecialchars($tarefa['description'])) ?></p>
                    <div class="task-actions">
                        <?php if (!$tarefa['is_done']): ?>
                            <a href="?done=<?= $tarefa['id'] ?>" class="btn done-btn">✅ Marcar como feita</a>
                        <?php else: ?>
                            <span class="done-label">✔️ Concluída</span>
                        <?php endif; ?>
                        <a href="?delete=<?= $tarefa['id'] ?>" class="btn delete-btn">🗑️ Excluir</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <?php include('../templates/footer.php'); ?>
</div>
</body>
</html>


<!-- <?php
session_start();
require_once('../config/conexao.php');

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'] ?? '';

// Adiciona nova tarefa
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title'], $_POST['description'])) {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);

    if (!empty($title)) {
        $stmt = $conn->prepare("INSERT INTO tasks (user_id, title, description, is_done, created_at) VALUES (?, ?, ?, 0, NOW())");
        $stmt->bind_param("iss", $user_id, $title, $description);
        $stmt->execute();
        $stmt->close();
    }
}

// Marcar tarefa como concluída
if (isset($_GET['done'])) {
    $id = (int)$_GET['done'];
    $stmt = $conn->prepare("UPDATE tasks SET is_done = 1 WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $id, $user_id);
    $stmt->execute();
    $stmt->close();
    header('Location: tasks.php');
    exit;
}

// Excluir tarefa
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM tasks WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $id, $user_id);
    $stmt->execute();
    $stmt->close();
    header('Location: tasks.php');
    exit;
}

// Buscar tarefas do usuário
$stmt = $conn->prepare("SELECT * FROM tasks WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$tarefas = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<? include('navbar.php'); ?>

<h2>Bem-vindo, <?= htmlspecialchars($user_name) ?> 👋</h2>

<form method="POST" style="margin-top: 20px;">
    <input type="text" name="title" placeholder="Título da tarefa" required>
    <br><br>
    <textarea name="description" placeholder="Descrição (opcional)" rows="3"></textarea>
    <br><br>
    <button type="submit">Adicionar Tarefa</button>
</form>

<h3 style="margin-top: 30px;">Suas Tarefas:</h3>

<ul style="list-style: none; padding: 0; margin-top: 15px;">
    <?php if (empty($tarefas)): ?>
        <p>Nenhuma tarefa cadastrada ainda.</p>
    <?php else: ?>
        <?php foreach ($tarefas as $tarefa): ?>
            <li style="margin-bottom: 15px;">
                <strong><?= htmlspecialchars($tarefa['title']) ?></strong><br>
                <small><?= nl2br(htmlspecialchars($tarefa['description'])) ?></small><br>

                <?php if (!$tarefa['is_done']): ?>
                    <a href="?done=<?= $tarefa['id'] ?>">✅ Marcar como feita</a>
                <?php else: ?>
                    <span style="color: green;">✔️ Concluída</span>
                <?php endif; ?>

                <a href="?delete=<?= $tarefa['id'] ?>" style="color: red; margin-left: 10px;">🗑️ Excluir</a>
            </li>
        <?php endforeach; ?>
    <?php endif; ?>
</ul>

<a href="login.php" style="display: inline-block; margin-top: 20px;">Sair</a> -->
