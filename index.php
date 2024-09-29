<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'header.php';
include 'confs/db.php';


// Verificar se há sessão iniciada

if (isset($_SESSION['user_id']) && isset($_SESSION['username'])) {
    $user_id = $_SESSION['user_id'];
    $username = $_SESSION['username'];



    $sql = "SELECT name from utilizadores WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($name);
    $stmt->fetch();
    $stmt->close();

    //Exibir mensagem de boas-vindas

    echo " <h3> Bem-vindo(a), <strong>$name</strong>!</h2>";
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/index.css">
    <link rel="stylesheet" href="styles/index-produtos.css">
    <title>.Store - Homepage</title>
    <title>.Store</title>
</head>

<body>
    <div class="container-info">
        <div class="info-tit">
            <h1>Sobre a <strong>.Store</strong></h1>
        </div>
        <p class="info-p">Lorem ipsum dolor sit amet consectetur adipisicing elit. Doloremque quod voluptates aspernatur voluptatibus ab neque officia dicta hic reprehenderit earum repellendus quo praesentium quos quas nulla eos mollitia, natus eligendi <br>
            Lorem ipsum dolor sit amet consectetur adipisicing elit. Similique quam, reiciendis pariatur corporis praesentium a ut sunt dolor doloribus nostrum. Molestias, soluta. Dolorem, voluptatem voluptate est incidunt vel at iusto?</p>
    </div>
    <section id="produtos">
        <div class="container-produtos">
            <div class="container-tit">
                <h2 class="tit-produtos">Produtos</h2>
            </div>
            <div class="grelha-produtos">
                <?php
                $sql = "SELECT id, nome, stock, preco, imagem FROM produtos";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                ?>
                        <div class="produto">
                            <img src="data:image/png;base64,<?php echo base64_encode($row['imagem']); ?>" alt="<?php echo $row['nome']; ?>">
                            <h3><?php echo $row['nome']; ?></h3>
                            <div class="detalhes-produto">
                                <p>Stock: <?php echo $row['stock']; ?></p>
                                <p>Preço: <?php echo $row['preco']; ?></p>

                                <!-- Formulário para adicionar ao carrinho -->
                                <form method="post" action="carrinho.php">
                                    <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                                    <input type="hidden" name="product_name" value="<?php echo $row['nome']; ?>">
                                    <input type="hidden" name="product_price" value="<?php echo $row['preco']; ?>">
                                    <input type="number" name="quantity" value="1" min="1">
                                    <button type="submit" name="add_to_cart">Adicionar ao Carrinho</button>
                                </form>
                            </div>
                        </div>
                <?php
                    }
                } else {
                    echo '<p>Nenhum produto encontrado</p>';
                }
                ?>
            </div>
        </div>
    </section>

</body>

</html>