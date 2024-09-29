<?php
session_start();

include 'header.php';


// Verificar se o admin está logado
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin.php"); // Redireciona para a página de login se não estiver logado
    exit;
}

// Aqui você pode adicionar o código para mostrar as encomendas e produtos
?>

<!DOCTYPE html>
<html lang="pt-pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Administração</title>
    <link rel="stylesheet" href="styles/main.css">
</head>

<body>
    <h2>Painel de Administração</h2>
    <h3>Encomendas</h3>
    <!-- Código para exibir a lista de encomendas -->

    <h3>Produtos</h3>
    <!-- Código para exibir a lista de produtos -->
</body>

</html>