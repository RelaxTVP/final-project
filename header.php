<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Miguel Carretas">
    <meta name="keywords" content="loja online, compras online, produtos, entrega rápida, ofertas, qualidade, melhor preço">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/af9ce2ef07.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/header.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand mx-auto" href="index.php">.Store</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-between" id="navbarNavAltMarkup">
                <div class="navbar-nav">
                    <a class="nav-link active" aria-current="page" href="index.php#produtos">Produtos</a>
                </div>
                <div class="navbar-nav ms-auto">
                    <a class="nav-link cart" href="admin.php">Administração</a>
                    <a class="nav-link cart" href="../final-project/carrinho.php"><i class="fa-solid fa-cart-shopping"></i></a>
                    <?php if (!isset($_SESSION['user_id'])): ?>
                        <a class="nav-link login" href="../final-project/login.php">Iniciar Sessão / Registar</a>
                    <?php else: ?>
                        <form action="confs/logout.php" method="post" class="nav-link logout"><button type="submit">Logout</button></form>
                    <?php endif; ?>


                </div>
            </div>
        </div>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>