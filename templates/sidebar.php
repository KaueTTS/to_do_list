<script src="../assets/scripts/sidebar.js"></script>

<div class="sidebar">
    <button class="toggle-btn" onclick="toggleSidebar()">☰</button>
    <div class="logo">Welcome</div>
    <div class="menu">
        <a href="tasks.php">📋 <span>Tarefas</span></a>
        <a href="../functions/logout.php" id="logout">🚪 <span>Sair</span></a>
    </div>
    <div class="user">👤 <?= htmlspecialchars($user_name) ?></div>
</div>