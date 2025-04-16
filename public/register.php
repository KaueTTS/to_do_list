<?php
session_start();
require_once('../config/conexao.php');

$erro = '';

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['name']);
    $email = trim($_POST['email']);
    $senha = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Verifica se já existe esse e-mail
    $verifica = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $verifica->bind_param("s", $email);
    $verifica->execute();
    $verifica->store_result();

    // Se e-mail já existir, mostra erro
    if ($verifica->num_rows > 0) {
        $erro = "E-mail já cadastrado.";
    // Senão, insere o usuário no banco
    } else {
        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nome, $email, $senha);

        // Se cadastrar com sucesso, redireciona para o login
        if ($stmt->execute()) {
            header("Location: login.php");
            exit();
        } else {
            $erro = "Erro ao cadastrar usuário.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Cadastro</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/index.css">
  <link rel="stylesheet" href="../assets/css/login.css">
</head>

<body>
    <div class="login-container">
        <form class="login-form" method="POST" action="register.php">
            <h2>Criar Conta</h2>

            <?php if ($erro): ?>
                <div class="error-message"><?= $erro ?></div>
            <?php endif; ?>

            <div class="input-group">
                <label for="name">Nome</label>
                <input type="text" name="name" id="name" required>
            </div>

            <div class="input-group">
                <label for="email">E-mail</label>
                <input type="email" name="email" id="email" required>
            </div>

            <div class="input-group">
                <label for="password">Senha</label>
                <input type="password" name="password" id="password" required>
            </div>

            <button type="submit">Cadastrar</button>

            <p class="register-link">
                Já tem uma conta? <a href="login.php">Entrar</a>
            </p>
        </form>

        <?php include('../templates/footer.php'); ?>
    </div>
</body>
</html>