<?php
session_start();
include 'confs/db.php';
include 'header.php';

// Verificar se o formulário foi submetido
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Consultar o utilizador na tabela
    $sql = "SELECT * FROM utilizadores WHERE username = 'admin'";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificar se existe apenas um utilizador com esse nome
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verificar a senha
        if (password_verify($password, $user['password'])) {
            $_SESSION['admin_logged_in'] = true; // Sinaliza que o admin está logado
            header("Location: admin_dashboard.php"); // Redireciona para o painel de administração
            exit;
        } else {
            $error_message = "Nome de utilizador ou palavra-passe incorretos.";
        }
    } else {
        $error_message = "Nome de utilizador ou palavra-passe incorretos.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Login</title>
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/login-admin.css"> <!-- Adicione este link -->
</head>

<body>
    <div class="container">
        <div class="login-container">
            <h2>Login de Administração</h2>
            <?php if (isset($error_message)) : ?>
                <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
            <?php endif; ?>
            <form method="post" action="">
                <div class="form-group">
                    <label for="username">Nome de Utilizador:</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Palavra-Passe:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit">Entrar</button>
            </form>
        </div>
    </div>
</body>

</html>