<?php
// Caminho até o .env
$env = parse_ini_file(__DIR__ . '/../.env');

$host = $env['DB_HOST'];
$dbname = $env['DB_NAME'];
$user = $env['DB_USER'];
$pass = $env['DB_PASS'];

// Conectar com MySQL usando mysqli
$conn = new mysqli($host, $user, $pass, $dbname);

// Verificar erro
if ($conn->connect_error) {
    die("Erro na conexão com o banco de dados: " . $conn->connect_error);
}

// Usar charset UTF-8 (boa prática)
$conn->set_charset("utf8");
?>
