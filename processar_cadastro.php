<?php
session_start();
include('config.php'); // Inclui o arquivo de configuração do banco de dados

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = mysqli_real_escape_string($conn, trim($_POST["nome"]));
    $usuario = mysqli_real_escape_string($conn, trim($_POST["usuario"]));
    $departamento = mysqli_real_escape_string($conn, trim($_POST["departamento"]));
    $email = mysqli_real_escape_string($conn, trim($_POST["email"]));
    $senha = mysqli_real_escape_string($conn, trim($_POST["senha"]));

       // Debugging
    ///var_dump($departamento);
    //exit();

    // Verificações de validação
    if (empty($email)) {
        $_SESSION["mensagem"] = "O campo de email não pode estar vazio!";
        header("Location: cadastro.php");
        exit();
    }
    if (strlen($senha) < 6) {
        $_SESSION["mensagem"] = "A senha deve ter pelo menos 6 caracteres!";
        header("Location: cadastro.php");
        exit();
    }
    if (strlen($nome) < 5) {
        $_SESSION["mensagem"] = "O nome deve ter pelo menos 5 letras!";
        header("Location: cadastro.php");
        exit();
    }
    if (empty($departamento)) {
        $_SESSION["mensagem"] = "O campo de departamento não pode estar vazio!";
        header("Location: cadastro.php");
        exit();
    }

    $senha_hash = password_hash($senha, PASSWORD_DEFAULT); // Criptografa a senha

    // Verifica se o usuário já existe
    $sql = "SELECT id FROM usuarios WHERE usuario = '$usuario' OR email = '$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $_SESSION["mensagem"] = "Usuário ou email já cadastrado!";
        header("Location: cadastro.php");
        exit();
    } else {
        // Insere o novo usuário no banco de dados
        $sql = "INSERT INTO usuarios (nome, usuario, departamento, email, senha) VALUES ('$nome', '$usuario', '$departamento', '$email', '$senha_hash')";
        if (mysqli_query($conn, $sql)) {
            $_SESSION["mensagem"] = "Cadastro realizado com sucesso!";
            header("Location: cadastro.php");
            exit();
        } else {
            $_SESSION["mensagem"] = "Erro ao cadastrar usuário!";
            header("Location: cadastro.php");
            exit();
        }
    }
}
?>
