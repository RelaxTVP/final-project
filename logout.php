<?php
session_start();
session_destroy(); // Encerrar a sessão
header("Location: index.php"); // Redirecionar para a página inicial
exit;
