<?php
session_start();
include 'confs/db.php';
include 'header.php';



// Verificar se o formulário foi submetido
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $quantity = $_POST['quantity'];

    // Verificar o stock atual do produto
    $sql = "SELECT stock FROM produtos WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $stmt->bind_result($stock_disponivel);
    $stmt->fetch();
    $stmt->close();

    // Verificar se há stock disponível
    if ($quantity > $stock_disponivel) {
        $_SESSION['mensagem_erro'] = "A quantidade solicitada excede o stock disponível para o produto \"$product_name\". Stock disponível: $stock_disponivel.";
        header("Location: carrinho.php");
        exit;
    }

    // Preparar o item a ser adicionado ao carrinho
    $cart_item = array(
        'id' => $product_id,
        'name' => $product_name,
        'price' => $product_price,
        'quantity' => $quantity
    );

    // Verificar se o carrinho já existe na sessão
    if (isset($_SESSION['cart'])) {
        $found = false;
        foreach ($_SESSION['cart'] as $key => $item) {
            if ($item['id'] == $product_id) {
                // Se o produto já estiver no carrinho, atualizar a quantidade
                $_SESSION['cart'][$key]['quantity'] += $quantity;
                $found = true;
                break;
            }
        }
        if (!$found) {
            // Adicionar novo produto ao carrinho
            $_SESSION['cart'][] = $cart_item;
        }
    } else {
        // Se o carrinho não existir, criar o primeiro item
        $_SESSION['cart'] = array($cart_item);
    }

    // Atualizar o stock
    $sql = "UPDATE produtos SET stock = stock - ? WHERE id = ? AND stock >= ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $quantity, $product_id, $quantity);
    $stmt->execute();
    $stmt->close();

    // Redireciona de volta para a página do carrinho
    header("Location: carrinho.php");
    exit;
}

// Função para remover item do carrinho e restaurar o stock
if (isset($_GET['action']) && $_GET['action'] == 'remove' && isset($_GET['id'])) {
    $product_id = $_GET['id'];

    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['id'] == $product_id) {
            $quantity_to_restore = $item['quantity'];

            // Restaurar o stock no banco de dados
            $sql = "UPDATE produtos SET stock = stock + ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $quantity_to_restore, $product_id);
            $stmt->execute();
            $stmt->close();

            // Remover o item do carrinho
            unset($_SESSION['cart'][$key]);
            break;
        }
    }

    // Reorganiza o array após a remoção
    $_SESSION['cart'] = array_values($_SESSION['cart']);

    header("Location: carrinho.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>.Store - Carrinho</title>
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/carrinho.css">
</head>

<body>

    <div class="tit-carrinho">
        <h2>O teu carrinho</h2>
    </div>
    <?php
    // Verificar se existe mensagem de erro na sessão
    if (isset($_SESSION['mensagem_erro'])) {
        echo "<div class='mensagem-erro'>" . $_SESSION['mensagem_erro'] . "</div>";
        unset($_SESSION['mensagem_erro']); // Limpar a mensagem de erro após exibir
    }
    ?>
    <div class="carrinho-container">
        <div class="tabela-container">
            <?php
            // Verifica se o carrinho contém produtos
            if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
                echo "<table>";
                echo "<tr><th>Produto</th><th>Quantidade</th><th>Preço Unitário</th><th>Total</th><th>Ação</th></tr>";

                $total_geral = 0;

                // Percorre os itens do carrinho
                foreach ($_SESSION['cart'] as $item) {
                    $total_produto = $item['quantity'] * $item['price'];
                    $total_geral += $total_produto;

                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($item['name']) . "</td>";
                    echo "<td>" . htmlspecialchars($item['quantity']) . "</td>";
                    echo "<td>" . number_format($item['price'], 2) . "€</td>";
                    echo "<td>" . number_format($total_produto, 2) . "€</td>";
                    echo "<td><a href='carrinho.php?action=remove&id=" . htmlspecialchars($item['id']) . "'>Remover</a></td>";
                    echo "</tr>";
                }

                // Exibe o total geral
                echo "<tr><td colspan='3'><strong>Total Geral:</strong></td><td><strong>" . number_format($total_geral, 2) . "€</strong></td><td></td></tr>";
                echo "</table>";
                // Verificar se existe mensagem de erro na sessão
                if (isset($_SESSION['mensagem_erro'])) {
                    echo "<div class='mensagem-erro'>" . $_SESSION['mensagem_erro'] . "</div>";
                    unset($_SESSION['mensagem_erro']); // Limpar a mensagem de erro após exibir
                }
            } else {
                echo "<p>O teu carrinho está vazio.</p>";
            }
            ?>
        </div>
    </div>

    <div class="carrinho-acoes">
        <a href="index.php" class="continuar-compras">Continuar Compras</a>
        <a href="pagamento.php" class="finalizar-compras">Finalizar Compras</a>
    </div>
</body>

</html>