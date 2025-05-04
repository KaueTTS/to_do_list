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
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
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
}

// Excluir tarefa
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM tasks WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $id, $user_id);
    $stmt->execute();
    $stmt->close();
}

// Editar tarefa
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit') {
    $id = (int)$_POST['task_id'];
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $stmt = $conn->prepare("UPDATE tasks SET title = ?, description = ? WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ssii", $title, $description, $id, $user_id);
    $stmt->execute();
    $stmt->close();
}

// Buscar tarefas
$search = '';
if (isset($_GET['search'])) {
    $search = trim($_GET['search']);
    $query = "SELECT * FROM tasks WHERE user_id = ? AND title LIKE ? ORDER BY created_at DESC";
    $stmt = $conn->prepare($query);
    $likeSearch = "%$search%";
    $stmt->bind_param("is", $user_id, $likeSearch);
} else {
    $stmt = $conn->prepare("SELECT * FROM tasks WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->bind_param("i", $user_id);
}
$stmt->execute();
$result = $stmt->get_result();
$tarefas = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>To Do List</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/index.css">
    <link rel="stylesheet" href="../assets/css/tasks.css">
</head>
<body>
    <?php include('../templates/sidebar.php'); ?>

    <div class="container">
        <div class="todo-panel">
            <h2 class="welcome">To do List</h2>

            <form method="GET" class="search-form">
                <div class="search-container">
                    <input type="text" name="search" placeholder="Buscar tarefas..." value="<?= htmlspecialchars($search) ?>">
                    <span class="search-icon">🔍</span>
                </div>
                <div class="divisor"></div>
            </form>

            <form method="POST" class="task-form">
                <input type="hidden" name="action" value="add">
                <input type="text" name="title" placeholder="Título da tarefa" required>
                <textarea name="description" placeholder="Descrição (opcional)"></textarea>
                <button type="submit">Adicionar Tarefa</button>
            </form>

            <div class="task-list">
                <?php if (empty($tarefas)): ?>
                    <p>Nenhuma tarefa encontrada.</p>
                <?php else: ?>
                    <?php foreach ($tarefas as $tarefa): ?>
                        <div class="task-card <?= $tarefa['is_done'] ? 'done' : '' ?>">
                            <h4><?= htmlspecialchars($tarefa['title']) ?></h4>
                            <p><?= nl2br(htmlspecialchars($tarefa['description'])) ?></p>
                            <div class="task-actions">
                                <?php if (!$tarefa['is_done']): ?>
                                    <a href="?done=<?= $tarefa['id'] ?>" class="btn done-btn">✅ Feita</a>
                                <?php endif; ?>
                                <button onclick="openEditModal(<?= $tarefa['id'] ?>, '<?= htmlspecialchars(addslashes($tarefa['title'])) ?>', '<?= htmlspecialchars(addslashes($tarefa['description'])) ?>')" class="btn edit-btn">✏️ Editar</button>
                                <a href="?delete=<?= $tarefa['id'] ?>" class="btn delete-btn">🗑️ Excluir</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Modal de edição -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <h3>Editar Tarefa</h3>
            <form method="POST" class="edit-form">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="task_id" id="editTaskId">
                <input type="text" name="title" id="editTitle" required>
                <textarea name="description" id="editDescription"></textarea>
                <button type="submit">Salvar Alterações</button>
                <button type="button" onclick="closeEditModal()">Cancelar</button>
            </form>
        </div>
    </div>

    <script src="../assets/scripts/tasks.js"></script>
</body>
</html>
