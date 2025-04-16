<script src="../assets/scripts/sidebar.js"></script>

<div class="sidebar">
    <button class="toggle-btn" onclick="toggleSidebar()">☰</button>
    <div class="logo">Agenda</div>
    <div class="menu">
        <a href="tasks.php">📋 <span>Tarefas</span></a>
        <a href="login.php">🚪 <span>Sair</span></a>
    </div>
    <div class="user">👤 <?= htmlspecialchars($user_name) ?></div>
</div>