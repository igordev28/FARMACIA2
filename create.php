<?php
include 'db.php';
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $medicamento = $_POST['medicamento'];  
    $preco = $_POST['preco']; 
    $quantidade = $_POST['quantidade']; 
    $categoria = $_POST['categoria'];
    $data_validade = $_POST['data_validade'];

    
    $stmt = $pdo->prepare("INSERT INTO medicamentos (medicamento, preco, quantidade, categoria, data_validade) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$medicamento, $preco, $quantidade, $categoria, $data_validade]);

   
    header('Location: index.php');
    exit(); 
}
?>
