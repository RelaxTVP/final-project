<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inicie a sessão no início do script
session_start();

include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Debug para verificar se os dados estão a ser bem recebidos
    error_log('Username: ' . $username);
    error_log('Password: ' . $password);

    // Consultar DB
    $sql = "SELECT user_id, password FROM utilizadores WHERE username = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        // Debug para verificar se a consulta está a devolver resultados
        error_log('Number of rows: ' . $stmt->num_rows);

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($user_id, $hashed_password);
            $stmt->fetch();

            // Verificar se a senha corresponde ao hash
            if (password_verify($password, $hashed_password)) {
                error_log('Autenticação bem-sucedida para o usuário: ' . $username);

                // Armazenar informações na sessão
                $_SESSION['user_id'] = $user_id;
                $_SESSION['username'] = $username;

                // Redirecionar o usuário para a página principal (home)
                header("Location: ../index.php");
                exit;
            } else {
                // Falha na autenticação
                error_log('Senha incorreta para o usuário: ' . $username);
                echo "Senha incorreta";
                header("refresh:1;url=../login.php");
            }
        } else {
            error_log('Usuário não encontrado: ' . $username);
            echo "Utilizador não encontrado";
            header("refresh:1;url=../login.php");
        }
    } else {
        error_log('Falha na preparação da consulta: ' . $conn->error);
        echo "Erro na consulta do banco de dados";
        header("refresh:1;url=../login.php");
    }
}
