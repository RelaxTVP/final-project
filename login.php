<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'header.php'




?>
<!DOCTYPE html>
<html lang="pt-pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/login.css">
    <title>.Store - Login / Iniciar Sessão</title>
</head>

<body>
    <div class="container-forms">
        <div class="container-login">
            <h2 class="tit-login">Iniciar Sessão</h2>
            <form action="confs/login-conf.php" method="post">
                <label for="username">Username</label>
                <input type="text" name="username" placeholder="Username" id="username" required><br>
                <label for="password">Password</label>
                <input type="password" name="password" placeholder="Password" id="password" required><br>
                <input type="submit" name="submit-btn" placeholder="Iniciar Sessão" id="submit-btn">
            </form>
        </div>

        <div class="container-registar">
            <h2 class="tit-registar">Registar</h2>
            <form action="confs/signup-conf.php" method="post">
                <label for="name">Nome</label>
                <input type="text" name="name" placeholder="Nome" id="nome" required><br>
                <label for="morada">Morada</label>
                <input type="text" name="morada" placeholder="Morada" id="morada" required><br>
                <label for="username">Username</label>
                <input type="text" name="username" placeholder="Username" id="username" required><br>
                <label for="email">Email</label>
                <input type="email" name="email" placeholder="Email" id="email" required><br>
                <label for="password">Password</label>
                <input type="password" name="password" placeholder="Password" id="password" required><br>
                <input type="submit" name="submit-btn" placeholder="Registar" id="submit-btn">

            </form>
        </div>
    </div>
</body>

</html>