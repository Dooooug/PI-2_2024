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
    $aut_usuario = $_SESSION["aut"] ?? 0;

    // Verificar se o usuário tem permissão para excluir
    if ($aut_usuario != 5) {
        $_SESSION["mensagem"] = "Acesso negado: Apenas administradores podem excluir usuários.";
        header("Location: consulta_usuarios.php");
        exit();
    }

    // Excluir os dados no banco de dados
    $url = "http://191.252.110.154:3000/api/delete_usuario";
    $data = [
        "cod" => $cod_usuario,
        "aut_usuario" => $aut_usuario
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);
    $response = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if (curl_errno($ch)) {
        $response = json_encode(["status" => false, "message" => "Erro na requisição", "error" => curl_error($ch)]);
    }
    curl_close($ch);

    $decoded_response = json_decode($response, true);

    // Registra a resposta e o código HTTP para depuração
    error_log("Resposta da API: " . print_r($response, true));
    error_log("Código HTTP: " . $httpcode);

    if ($httpcode == 200 && isset($decoded_response["status"]) && $decoded_response["status"] === true) {
        $_SESSION["mensagem"] = "Usuário excluído com sucesso!";
    } else {
        $_SESSION["mensagem"] = "Erro ao excluir usuário: " . ($decoded_response["message"] ?? "Desconhecido");
    }

    header("Location: consulta_usuarios.php");
    exit();
} else {
    $_SESSION["mensagem"] = "Método de requisição inválido.";
    header("Location: consulta_usuarios.php");
    exit();
}
?>
