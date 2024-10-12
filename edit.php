<?php
include 'db.php';
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM medicamentos WHERE id = ?");
$stmt->execute([$id]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $medicamento = $_POST['medicamento'];  
    $preco = $_POST['preco']; 
    $quantidade = $_POST['quantidade']; 
    $categoria = $_POST['categoria'];
    $data_validade = $_POST['data_validade'];

    
    $stmt = $pdo->prepare("UPDATE medicamentos SET medicamento = ?, preco = ?, quantidade = ?, categoria = ?, data_validade = ? WHERE id = ?");
    $stmt->execute([$medicamento, $preco, $quantidade, $categoria, $data_validade, $id]);

    header('Location: index.php');
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Medicamentos</title>
</head>
<body>
    <h1>Editar Medicamentos</h1>
    <form action="" method="POST">
        <input type="text" name="medicamento" value="<?= $usuario['medicamento'] ?>" required><br>
        <input type="number" name="quantidade" value="<?= $usuario['quantidade'] ?>" required><br>
        <input type="text" name="preco" value="<?= $usuario['preco'] ?>" required><br>
        <input type="date" name="data_validade" value="<?= $usuario['data_validade'] ?>" required><br>
        <select name="categoria" required>
            <option value="Analgésico" <?= $usuario['categoria'] == 'Analgésico' ? 'selected' : '' ?>>Analgésico</option>
            <option value="Antibiótico" <?= $usuario['categoria'] == 'Antibiótico' ? 'selected' : '' ?>>Antibiótico</option>
            <option value="Anti-inflamatório" <?= $usuario['categoria'] == 'Anti-inflamatório' ? 'selected' : '' ?>>Anti-inflamatório</option>
            <option value="Outro" <?= $usuario['categoria'] == 'Outro' ? 'selected' : '' ?>>Outro</option>
        </select><br>
        <button type="submit">Atualizar</button>
    </form>
</body>
</html>
