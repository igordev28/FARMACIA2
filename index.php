<?php 
include 'db.php'; 

$usuario = 'igor';
$senha = password_hash('jones', PASSWORD_DEFAULT);

$stmt = $pdo->prepare("INSERT INTO administradores (usuario, senha) VALUES (?, ?)");
$stmt->execute([$usuario, $senha]);

echo "Administrador criado com sucesso!";

session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

// Verifica se há uma busca ou uma ordenação
$search = isset($_GET['search']) ? $_GET['search'] : '';
$order_by = isset($_GET['order_by']) ? $_GET['order_by'] : 'medicamento';

// Modifica a query com base na busca e ordenação
$query_str = "SELECT * FROM medicamentos WHERE medicamento LIKE :search ORDER BY " . $order_by;
$query = $pdo->prepare($query_str);
$query->execute(['search' => '%' . $search . '%']);
$medicamentos = $query->fetchAll(PDO::FETCH_ASSOC);

// Verifica se o formulário de venda foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $medicamento_id = $_POST['medicamento_id'];
    $quantidade_vendida = $_POST['quantidade_vendida'];

    // Busca a quantidade atual em estoque do medicamento
    $query = $pdo->prepare("SELECT quantidade FROM medicamentos WHERE id = :id");
    $query->execute(['id' => $medicamento_id]);
    $medicamento = $query->fetch(PDO::FETCH_ASSOC);

    if ($medicamento) {
        $quantidade_estoque = $medicamento['quantidade'];

        // Verifica se a quantidade em estoque é suficiente
        if ($quantidade_estoque >= $quantidade_vendida) {
            // Subtrai a quantidade vendida do estoque
            $nova_quantidade = $quantidade_estoque - $quantidade_vendida;
            $update = $pdo->prepare("UPDATE medicamentos SET quantidade = :quantidade WHERE id = :id");
            $update->execute(['quantidade' => $nova_quantidade, 'id' => $medicamento_id]);

            echo "Venda realizada com sucesso! Quantidade atual em estoque: " . $nova_quantidade;
        } else {
            echo "Erro: Quantidade insuficiente em estoque. Disponível: " . $quantidade_estoque;
        }
    } else {
        echo "Erro: Medicamento não encontrado.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Cadastro de Medicamentos</title>
</head>
<body>
    <h1>FARMÁCIA VIDA SAUDÁVEL </h1>

    <div class="container">
        <form action="create.php" method="POST">
            <h2>Cadastrar Medicamento</h2>
            <input type="text" name="medicamento" placeholder="Nome do Medicamento" required><br>
            <input type="number" name="quantidade" placeholder="Quantidade" required><br>
            <input type="text" name="preco" placeholder="Preço" required><br>
            <select name="categoria" required>
                <option value="Analgésico">Analgésico</option>
                <option value="Antibiótico">Antibiótico</option>
                <option value="Anti-inflamatório">Anti-inflamatório</option>
                <option value="Outro">Outro</option>
            </select><br>
            <input type="date" name="data_validade" required><br>
            <button type="submit">Cadastrar Medicamento</button>
        </form> 

        <form action="" method="GET">
            <h2>Pesquisar Medicamento</h2>
            <input type="text" name="search" placeholder="Buscar Medicamento" value="<?= htmlspecialchars($search) ?>"><br>
            <select name="order_by">
                <option value="medicamento" <?= $order_by === 'medicamento' ? 'selected' : '' ?>>Medicamento</option>
                <option value="categoria" <?= $order_by === 'categoria' ? 'selected' : '' ?>>Categoria</option>
                <option value="preco" <?= $order_by === 'preco' ? 'selected' : '' ?>>Preço</option>
            </select>
            <button type="submit">Buscar</button>
        </form>

        <form action="" method="POST">
            <h2>Venda de Medicamento</h2>
            <label for="medicamento_id">ID do Medicamento:</label>
            <input type="number" name="medicamento_id" id="medicamento_id" required><br>
            <label for="quantidade_vendida">Quantidade Vendida:</label>
            <input type="number" name="quantidade_vendida" id="quantidade_vendida" required><br>
            <button type="submit">Realizar Venda</button>
        </form>
    </div>

    <h2>Medicamentos em Estoque</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Quantidade</th>
            <th>Preço</th>
            <th>Data de Validade</th>
            <th>Categoria</th>
            <th class="actions">Ações</th>
        </tr>
        <?php foreach ($medicamentos as $medicamento): ?>
        <tr>
            <td><?= $medicamento['id'] ?></td>
            <td><?= $medicamento['medicamento'] ?></td>
            <td><?= $medicamento['quantidade'] ?></td>
            <td><?= $medicamento['preco'] ?></td>
            <td><?= $medicamento['data_validade'] ?></td>
            <td><?= $medicamento['categoria'] ?></td>
            <td class="actions">
                <a href="edit.php?id=<?= $medicamento['id'] ?>">Editar</a>
                <a href="delete.php?id=<?= $medicamento['id'] ?>" onclick="return confirm('Tem certeza que deseja excluir?');">Excluir</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
