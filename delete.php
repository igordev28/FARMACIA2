<?php
include 'db.php';

$id = $_GET['id'];
$stmt = $pdo->prepare("DELETE FROM medicamentos WHERE id = ?");
$stmt->execute([$id]);

header('Location: index.php');
?>
