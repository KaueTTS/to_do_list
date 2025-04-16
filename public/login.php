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

        // Verifica a senha
        if (password_verify($senha, $usuario['password'])) {
            // Login válido, salva dados na sessão
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

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Login</title>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/index.css">
  <link rel="stylesheet" href="../assets/css/login.css">
</head>

<body>
    <div class="login-container">
        <form class="login-form" method="POST" action="login.php">
            <h2>To Do List</h2>

            <?php if ($erro): ?>
                <div class="error-message"><?= $erro ?></div>
            <?php endif; ?>
            
            <div class="input-group">
                <label for="email">E-mail</label>
                <input type="email" name="email" id="email" required>
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