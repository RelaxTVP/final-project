<?php
session_start();
include 'confs/db.php';
include 'header.php';

// Verificar se carrinho tem produtos
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header("Location: carrinho.php"); // Redireciona para o carrinho se estiver vazio
    exit;
}

// Função para validar a idade
function calcular_idade($data_nascimento)
{
    $data_nascimento = new DateTime($data_nascimento);
    $hoje = new DateTime();
    $idade = $hoje->diff($data_nascimento);
    return $idade->y;
}

// Verificar se o formulário foi submetido
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = htmlspecialchars($_POST['nome']);
    $data_nascimento = $_POST['data_nascimento'];
    $endereco = htmlspecialchars($_POST['endereco']);
    $cidade = htmlspecialchars($_POST['cidade']);
    $codigo_postal = htmlspecialchars($_POST['codigo_postal']);
    $pais = htmlspecialchars($_POST['pais']);
    $cartao = htmlspecialchars($_POST['cartao']);
    $data_expiracao = htmlspecialchars($_POST['data_expiracao']);
    $cvv = htmlspecialchars($_POST['cvv']);

    // Validações
    if (empty($nome) || empty($data_nascimento) || empty($endereco) || empty($cidade) || empty($codigo_postal) || empty($pais) || empty($cartao) || empty($data_expiracao) || empty($cvv)) {
        $erro = "Todos os campos são obrigatórios.";
    } elseif (calcular_idade($data_nascimento) < 18) {
        $erro = "A idade mínima para realizar a compra é 18 anos.";
    } else {
        // Processar a encomenda
        $total_geral = 0;

        foreach ($_SESSION['cart'] as $item) {
            $preco_total = $item['quantity'] * $item['price'];
            $total_geral += $preco_total;

            // Inserir encomenda na base de dados
            $sql = "INSERT INTO encomendas (nome_cliente, data_nascimento, morada, produto_id, quantidade, preco_total) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssiid", $nome, $data_nascimento, $endereco, $item['id'], $item['quantity'], $preco_total);

            if (!$stmt->execute()) {
                echo "Erro ao registrar a encomenda: " . $stmt->error;
                exit; // Parar execução se houver erro
            }
        }

        // Limpar o carrinho após a compra
        unset($_SESSION['cart']);

        // Redirecionar para página de confirmação
        header("Location: confirmacao.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>.Store - Finalizar Compra</title>
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/carrinho.css">
    <link rel="stylesheet" href="styles/pagamento.css">
</head>

<body>
    <div class="tit-carrinho">
        <h2>Finalizar Compra</h2>
    </div>

    <?php if (isset($erro)): ?>
        <div class="mensagem-erro"><?php echo $erro; ?></div>
    <?php endif; ?>

    <div class="carrinho-container">
        <!-- Detalhes do carrinho -->
        <div class="tabela-container">
            <table>
                <tr>
                    <th>Produto</th>
                    <th>Quantidade</th>
                    <th>Preço Unitário</th>
                    <th>Total</th>
                </tr>
                <?php
                $total_geral = 0;
                foreach ($_SESSION['cart'] as $item) {
                    $total_produto = $item['quantity'] * $item['price'];
                    $total_geral += $total_produto;

                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($item['name']) . "</td>";
                    echo "<td>" . htmlspecialchars($item['quantity']) . "</td>";
                    echo "<td>" . number_format($item['price'], 2) . "€</td>";
                    echo "<td>" . number_format($total_produto, 2) . "€</td>";
                    echo "</tr>";
                }
                ?>
                <tr>
                    <td colspan="3"><strong>Total Geral:</strong></td>
                    <td><strong><?php echo number_format($total_geral, 2); ?>€</strong></td>
                </tr>
            </table>
        </div>

        <!-- Formulário de Informações Pessoais e Pagamento -->
        <div class="pagamento-form">
            <h3>Informações Pessoais e de Pagamento</h3>
            <form method="post" action="confirmacao.php">

                <!-- Informações Pessoais -->
                <div class="form-group">
                    <label for="nome">Nome Completo:</label>
                    <input type="text" id="nome" name="nome" required>
                </div>

                <div class="form-group">
                    <label for="data_nascimento">Data de Nascimento:</label>
                    <input type="date" id="data_nascimento" name="data_nascimento" required>
                </div>

                <div class="form-group">
                    <label for="endereco">Endereço:</label>
                    <input type="text" id="endereco" name="endereco" required>
                </div>

                <div class="form-group">
                    <label for="cidade">Cidade:</label>
                    <input type="text" id="cidade" name="cidade" required>
                </div>

                <div class="form-group">
                    <label for="codigo_postal">Código Postal:</label>
                    <input type="text" id="codigo_postal" name="codigo_postal" required>
                </div>

                <div class="form-group">
                    <label for="pais">País:</label>
                    <input type="text" id="pais" name="pais" required>
                </div>

                <!-- Informações de Pagamento -->
                <div class="form-group">
                    <label for="cartao">Número do Cartão:</label>
                    <input type="text" id="cartao" name="cartao" required>
                </div>

                <div class="form-group">
                    <label for="data_expiracao">Data de Expiração (MM/AA):</label>
                    <input type="text" id="data_expiracao" name="data_expiracao" placeholder="MM/AA" required>
                </div>

                <div class="form-group">
                    <label for="cvv">CVV:</label>
                    <input type="text" id="cvv" name="cvv" required>
                </div>

                <div class="finalizar-botao">
                    <button type="submit">Concluir Compra</button>
                </div>
            </form>
        </div>

        <div class="carrinho-acoes">
            <a href="carrinho.php" class="continuar-compras">Voltar ao Carrinho</a>
        </div>
    </div>
</body>

</html>