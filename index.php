<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'header.php';

session_start();

// Verificar se há sessão iniciada

if (isset($_SESSION['user_id']) && isset($_SESSION['username'])) {
    $user_id = $_SESSION['user_id'];
    $username = $_SESSION['username'];

    include 'confs/db.php';

    $sql = "SELECT name from utilizadores WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($name);
    $stmt->fetch();

    //Exibir mensagem de boas-vindas

    echo " <h3> Bem-vindo(a), <strong>$name</strong>!</h2>";
} else {
    header("Location: login.php");
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/index.css">
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


        </div>
    </section>

</body>

</html>