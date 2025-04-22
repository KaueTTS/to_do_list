<?php
session_start();
require_once('../config/conexao.php');

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $senha = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, name, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $usuario = $resultado->fetch_assoc();

        if (password_verify($senha, $usuario['password'])) {
            $_SESSION['user_id'] = $usuario['id'];
            $_SESSION['user_name'] = $usuario['name'];

            header("Location: tasks.php");
            exit();
        } else {
            $erro = "Senha incorreta.";
        }
    } else {
        $erro = "Usuário não encontrado.";
    }
}
?>

<?php include('../templates/head.php'); ?>

<body>
    <div class="login-container">
        <form class="login-form" method="POST" action="login.php">
            <h2>Entrar</h2>
            
            <div class="input-group">
                <label for="email">E-mail</label>
                <input type="email" name="email" id="email" required>
                <?php if ($erro): ?>
                    <div class="error-message"><?= $erro ?></div>
                <?php endif; ?>
            </div>

            <div class="input-group">
                <label for="senha">Senha</label>
                <input type="password" name="password" id="password" required>
            </div>

            <button type="submit">Entrar</button>

            <p class="register-link">
                Não tem uma conta? <a href="register.php">Cadastre-se</a>
            </p>
        </form>
    
        <?php include('../templates/footer.php'); ?>
    </div>
</body>
</html>