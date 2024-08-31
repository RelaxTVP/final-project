<?php

include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $morada = $_POST['morada'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // hash da senha

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    //inserir dados na db

    $sql = "INSERT INTO utilizadores (username, name, password, morada, email) VALUES (?,?,?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $username, $name, $hashed_password, $morada, $email);

    if ($stmt->execute()) {
        echo "Registo bem sucedido!";
        header("refresh:3;url=../login.php");
        exit;
    } else {
        echo "Erro ao registar: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
