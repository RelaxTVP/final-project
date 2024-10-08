<?php
session_start();
include 'confs/db.php';
include 'header.php';

// Verificar se a transação foi concluída (simulando)
$transacao_concluida = true; // Aqui você pode colocar a lógica real de verificação da transação

if ($transacao_concluida) {
    // Obter os dados do cliente e do carrinho
    $nome_cliente = htmlspecialchars($_POST['nome']);
    $data_nascimento = htmlspecialchars($_POST['data_nascimento']);
    $endereco = htmlspecialchars($_POST['endereco']);
    $cidade = htmlspecialchars($_POST['cidade']);
    $codigo_postal = htmlspecialchars($_POST['codigo_postal']);
    $pais = htmlspecialchars($_POST['pais']);
    $total_geral = 0;

    // Verificar se o carrinho não está vazio
    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        // Inserir cada item do carrinho na tabela "encomendas"
        foreach ($_SESSION['cart'] as $item) {
            $produto_id = $item['id'];
            $quantidade = $item['quantity']; // Verifique se a chave é 'quantity'
            $preco_total = $quantidade * $item['price']; // Corrigi para usar a variável 'quantidade'
            $total_geral += $preco_total;

            // Prepare a consulta SQL
            $sql = "INSERT INTO encomendas (nome_cliente, data_nascimento, morada, produto_id, quantidade, preco_total) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            if ($stmt === false) {
                die("Erro na preparação da consulta: " . $conn->error);
            }
            $stmt->bind_param("sssiii", $nome_cliente, $data_nascimento, $endereco, $produto_id, $quantidade, $preco_total);
            if (!$stmt->execute()) {
                echo "Erro ao inserir dados: " . $stmt->error; // Mensagem de erro
            }
        }
    } else {
        echo "O carrinho está vazio."; // Mensagem de erro
    }
}
?>

<!DOCTYPE html>
<html lang="pt-pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>.Store - Confirmação de Compra</title>
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/carrinho.css">
    <link rel="stylesheet" href="styles/pagamento.css">
</head>

<body>
    <div class="tit-carrinho">
        <h2>Confirmação de Compra</h2>
    </div>

    <div class="carrinho-container">
        <div class="tabela-container">
            <?php if ($transacao_concluida): ?>
                <div class="mensagem-sucesso">
                    <h3>Obrigado pela tua compra!</h3>
                    <p>A tua transação foi concluída com sucesso.</p>
                    <p>Em breve receberás um email com os detalhes do pedido e as instruções para o envio.</p>
                </div>

                <table>
                    <tr>
                        <th>Produto</th>
                        <th>Quantidade</th>
                        <th>Preço Unitário</th>
                        <th>Total</th>
                    </tr>
                    <?php
                    // Exibir os detalhes dos produtos comprados
                    foreach ($_SESSION['cart'] as $item) {
                        $total_produto = $item['quantity'] * $item['price'];
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($item['name']) . "</td>";
                        echo "<td>" . htmlspecialchars($item['quantity']) . "</td>";
                        echo "<td>" . number_format($item['price'], 2) . "€</td>";
                        echo "<td>" . number_format($total_produto, 2) . "€</td>";
                        echo "</tr>";
                    }

                    // Exibir o total geral da compra
                    echo "<tr>";
                    echo "<td colspan='3'><strong>Total Geral:</strong></td>";
                    echo "<td><strong>" . number_format($total_geral, 2) . "€</strong></td>";
                    echo "</tr>";
                    ?>
                </table>
            <?php else: ?>
                <div class="mensagem-erro">
                    <h3>Ocorreu um problema com a tua compra.</h3>
                    <p>Por favor, tenta novamente ou entra em contacto com o nosso suporte.</p>
                </div>
            <?php endif; ?>
        </div>

        <div class="carrinho-acoes">
            <a href="index.php" class="continuar-compras">Voltar à Página Inicial</a>
        </div>
    </div>

</body>

</html>