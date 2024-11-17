<?php

// Ativar a exibição de erros
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if (empty($_SESSION)) {
    header("Location: index.php");
    exit();
}

// Verificar se a requisição é POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obter os dados do formulário
    $cod_usuario = $_POST["cod"];
    $usuario_nome = $_POST["usuario"];
    $senha = $_POST["senha"];
    $usuario_aut = $_POST["aut"];

    // Verificar se os campos obrigatórios estão preenchidos
    if (empty($cod_usuario) || empty($usuario_nome) || empty($usuario_aut)) {
        $_SESSION["mensagem"] = "Todos os campos obrigatórios devem ser preenchidos.";
        header("Location: update.php");
        exit();
    }

    // Atualizar os dados no banco de dados
    $url = "http://191.252.110.154:3000/api/update_usuario";
    $data = [
        "cod" => $cod_usuario,
        "usuario" => $usuario_nome,
        "senha" => $senha,
        "aut" => $usuario_aut
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Content-Length: ' . strlen(json_encode($data))
    ]);
    $response = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if (curl_errno($ch)) {
        $response = json_encode(["status" => false, "message" => "Erro na requisição", "error" => curl_error($ch)]);
    }
    curl_close($ch);

    $decoded_response = json_decode($response, true);

    if ($httpcode == 200 && isset($decoded_response["status"]) && $decoded_response["status"] === true) {
        $_SESSION["mensagem"] = "Usuário atualizado com sucesso!";
    } else {
        $_SESSION["mensagem"] = "Erro ao atualizar usuário: " . ($decoded_response["message"] ?? "Desconhecido");
    }

    header("Location: consulta_usuarios.php");
    exit();
} else {
    $_SESSION["mensagem"] = "Método de requisição inválido.";
    header("Location: consulta_usuarios.php");
    exit();
}

?>
