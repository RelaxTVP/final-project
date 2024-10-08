<?php
session_start();
include 'confs/db.php';
include 'header.php';

// Verificar se o admin está logado
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin.php"); // Redireciona para a página de login se não estiver logado
    exit;
}

// Verificar se o formulário de atualização foi submetido
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_product'])) {
    $produto_id = htmlspecialchars($_POST['produto_id']);
    $stock = htmlspecialchars($_POST['stock']);
    $preco = htmlspecialchars($_POST['preco']);

    // Atualizar produto na base de dados
    $stmt = $conn->prepare("UPDATE produtos SET stock = ?, preco = ? WHERE id = ?");
    $stmt->bind_param("idi", $stock, $preco, $produto_id);

    if ($stmt->execute()) {
        echo "<div class='mensagem-sucesso'>Produto atualizado com sucesso!</div>";
    } else {
        echo "<div class='mensagem-erro'>Erro ao atualizar produto: " . $stmt->error . "</div>";
    }

    $stmt->close();
}

// Verificar se o formulário de exclusão de encomenda foi submetido
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_order'])) {
    $encomenda_id = htmlspecialchars($_POST['encomenda_id']);

    // Apagar encomenda da base de dados
    $stmt = $conn->prepare("DELETE FROM encomendas WHERE id = ?");
    $stmt->bind_param("i", $encomenda_id);

    if ($stmt->execute()) {
        echo "<div class='mensagem-sucesso'>Encomenda apagada com sucesso!</div>";
    } else {
        echo "<div class='mensagem-erro'>Erro ao apagar encomenda: " . $stmt->error . "</div>";
    }

    $stmt->close();
}


// Obter todas as encomendas da base de dados
$sql = "SELECT * FROM encomendas";
$result = $conn->query($sql);

// Obter todos os produtos da base de dados
$sql_produtos = "SELECT * FROM produtos";
$result_produtos = $conn->query($sql_produtos);
?>

<!DOCTYPE html>
<html lang="pt-pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Administração</title>
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/admin-dashboard.css">
</head>

<body>

    <h2>Painel de Administração</h2>
    <div class="admin-container">

        <h3>Encomendas</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>Nome do Cliente</th>
                <th>Data de Nascimento</th>
                <th>Morada</th>
                <th>ID do Produto</th>
                <th>Quantidade</th>
                <th>Preço Total</th>
                <th>Cancelar Encomenda</th>
            </tr>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['nome_cliente']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['data_nascimento']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['morada']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['produto_id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['quantidade']) . "</td>";
                    echo "<td>" . number_format($row['preco_total'], 2) . "€</td>";
                    // Botão para apagar a encomenda
                    echo "<td>";
                    echo "<form method='POST' action=''>";
                    echo "<input type='hidden' name='encomenda_id' value='" . htmlspecialchars($row['id']) . "'>";
                    echo "<button type='submit' name='delete_order' onclick=\"return confirm('Tens a certeza que queres apagar esta encomenda?');\">Apagar</button>";
                    echo "</form>";
                    echo "</td>";

                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7'>Nenhuma encomenda encontrada.</td></tr>";
            }


            ?>
        </table>
    </div>

    <div class="admin-container">
        <h3>Produtos</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Stock</th>
                <th>Preço</th>
                <th>Imagem</th>
                <th>Ações</th>
            </tr>
            <?php
            if ($result_produtos->num_rows > 0) {
                while ($row = $result_produtos->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['nome']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['stock']) . "</td>";
                    echo "<td>" . number_format($row['preco'], 2) . "€</td>";
                    echo "<td><img src='" . htmlspecialchars($row['imagem']) . "' alt='" . htmlspecialchars($row['nome']) . "' style='width: 50px;'></td>";
                    echo "<td>";
                    echo "<form method='post' action=''>";
                    echo "<input type='hidden' name='produto_id' value='" . htmlspecialchars($row['id']) . "'>";
                    echo "<input type='number' name='stock' value='" . htmlspecialchars($row['stock']) . "' required>";
                    echo "<input type='text' name='preco' value='" . htmlspecialchars($row['preco']) . "' required>";
                    echo "<button type='submit' name='update_product'>Atualizar</button>";
                    echo "</form>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>Nenhum produto encontrado.</td></tr>";
            }
            ?>
        </table>
    </div>

    <div class="admin-container">
        <h3>Adicionar Novo Produto</h3>
        <form method="post" action="">
            <div class="form-group">
                <label for="nome">Nome do Produto:</label>
                <input type="text" id="nome" name="nome" required>
            </div>
            <div class="form-group">
                <label for="stock">Quantidade em Stock:</label>
                <input type="number" id="stock" name="stock" required>
            </div>
            <div class="form-group">
                <label for="preco">Preço:</label>
                <input type="text" id="preco" name="preco" required>
            </div>
            <div class="form-group">
                <label for="imagem">URL da Imagem:</label>
                <input type="text" id="imagem" name="imagem" required>
            </div>
            <button type="submit">Adicionar Produto</button>
        </form>
    </div>

    <div class="admin-acoes">
        <a href="logout.php">Sair</a>
    </div>

</body>

</html>

<?php
$conn->close(); // Fechar a conexão 
?>