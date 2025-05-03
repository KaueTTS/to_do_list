<?php
require_once('../config/conexao.php');

$erros = [];
$nome = '';
$email = '';

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['name']);
    $email = trim($_POST['email']);
    $senha = $_POST['password'];

    if (empty($nome)) {
        $erros['name'] = "O nome é obrigatório.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erros['email'] = "Formato de e-mail inválido.";
    }

    if (strlen($senha) < 8 ) {
        $erros['password'] = "A senha deve ter pelo menos 8 caracteres.";
    } elseif (!preg_match('/[A-Z]/', $senha)) {
        $erros['password'] = "A senha deve conter pelo menos uma letra maiúscula.";
    } elseif (!preg_match('/[a-z]/', $senha)) {
        $erros['password'] = "A senha deve conter pelo menos uma letra minúscula.";
    }

    if (empty($erros)) {
        $verifica = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $verifica->bind_param("s", $email);
        $verifica->execute();
        $verifica->store_result();

        if ($verifica->num_rows > 0) {
            $erros['email'] = "E-mail já cadastrado.";
        } else {
            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $nome, $email, $senhaHash);

            if ($stmt->execute()) {
                header("Location: login.php");
                exit();
            } else {
                $erros = "Erro ao cadastrar usuário.";
            }
        }
    }
}
?>