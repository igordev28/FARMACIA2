<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main.css">
    <title>Login</title>
</head>
<body>

<?php
session_start();
include 'db.php';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];

    $stmt = $pdo->prepare("SELECT * FROM administradores WHERE usuario = ?");
    $stmt->execute([$usuario]);
    $administrador = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($administrador && password_verify($senha, $administrador['senha'])) {
        $_SESSION['admin'] = $administrador['id'];
        header('Location: index.php');
        exit();
    } else {
        $error_message = "Usuário ou senha inválidos! Verifique suas credenciais e tente novamente.";
        // Define o tempo de exibição da mensagem de erro (em segundos)
        $tempo_exibicao = 3;
        header("Refresh: $tempo_exibicao");
    }
}
?>

<div class="page">
    <?php if ($error_message): ?>
        <p class="error-message"><?= $error_message ?></p>
    <?php endif; ?>
    <form method="POST" class="formLogin">
        <h1>Login</h1>
        <p>Digite os seus dados de acesso no campo abaixo.</p>
        <label for="login">Email/UserName</label>
        <input type="text" name="usuario" placeholder="Digite seu Email" autofocus="true" required><br>
        <label for="password">Senha</label>
        <input type="password" name="senha" placeholder="Digite sua senha" required><br>
        <a href="/">Esqueci minha senha</a>
        <input type="submit" value="Acessar" class="btn" />
    </form>
</div>

</body>
</html>