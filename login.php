<?php

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["usuario"]) || empty($_POST["departamento"]) || empty($_POST["senha"])) {
        $_SESSION["mensagem"] = "Todos os campos são obrigatórios.";
        header("Location: index.php");
        exit();
    }

    include('config.php');

    $usuario = $_POST["usuario"];
    $senha = $_POST["senha"];

    // Usando consultas preparadas para evitar injeção de SQL
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE usuario = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
        $row = $res->fetch_object();
        if (password_verify($senha, $row->senha)) {
            $_SESSION["usuario"] = $usuario;
            $_SESSION["departamento"] = $row->departamento;
            $_SESSION["nome"] = $row->nome;
            $_SESSION["tipo"] = $row->tipo;
            $_SESSION["mensagem"] = "Login bem-sucedido!";
            header("Location: dashboard.php");
            exit();
        } else {
            $_SESSION["mensagem"] = "Senha incorreta.";
            header("Location: index.php");
            exit();
        }
    } else {
        $_SESSION["mensagem"] = "Usuário não encontrado.";
        header("Location: index.php");
        exit();
    }
}
?>
