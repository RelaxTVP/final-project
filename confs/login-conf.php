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

    // Consultar DB para verificar o username, password e user_type
    $sql = "SELECT user_id, password, user_type FROM utilizadores WHERE username = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        // Debug para verificar se a consulta está a devolver resultados
        error_log('Number of rows: ' . $stmt->num_rows);

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($user_id, $hashed_password, $user_type);
            $stmt->fetch();

            // Verificar se a senha corresponde ao hash
            if (password_verify($password, $hashed_password)) {
                error_log('Autenticação bem-sucedida para o usuário: ' . $username);

                // Armazenar informações na sessão
                $_SESSION['user_id'] = $user_id;
                $_SESSION['username'] = $username;
                $_SESSION['user_type'] = $user_type;

                // Verificar o tipo de utilizador
                if ($user_type == 'admin') {

                    header("Location: ../admin_dashboard.php");
                    exit;
                } else if ($user_type == 'user') {
                    header("Location: ../index.php");
                }
            } else {
                // Falha na autenticação
                error_log('Senha incorreta para o utilizador: ' . $username);
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
