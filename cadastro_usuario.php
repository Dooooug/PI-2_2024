<?php

// Ativar a exibição de erros
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Função cadastro_usuario
function cadastro_usuario($aut_usuario, $usuario, $senha, $aut) {
    $url = "http://191.252.110.154:3000/api/cadastro_usuario";
    $data = [
        "aut_usuario" => $aut_usuario,
        "usuario" => $usuario,
        "senha" => $senha,
        "aut" => $aut
    ];

    // Mostrar os dados que estão sendo enviados para a API
    echo "<pre>Dados enviados para a API: " . print_r($data, true) . "</pre>";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
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

    // Mostrar a resposta da API
    echo "<pre>Resposta da API: " . print_r($response, true) . "</pre>";
    echo "<pre>Código HTTP: " . $httpcode . "</pre>";

    // Retornar os dados da requisição, a resposta e o código HTTP para exibição na página
    return ["response" => json_decode($response, true), "httpcode" => $httpcode, "data" => $data, "raw_response" => $response];
}

// Processamento de requisição POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["usuario"]) || empty($_POST["senha"])) {
        $_SESSION["mensagem"] = "Todos os campos são obrigatórios.";
        header("Location: cadastro.php");
        exit();
    }

    // Verificar o nível de autorização do usuário logado
    $aut_usuario = $_SESSION["aut"] ?? 0;
    if ($aut_usuario != 5) {
        $_SESSION["mensagem"] = "Usuário não autorizado para realizar cadastro.";
        header("Location: cadastro.php");
        exit();
    }

    $usuario = $_POST["usuario"];
    $senha = $_POST["senha"];
    $aut = $_POST["aut"] ?? 1;  // Define o nível de permissão do novo usuário

    $resultado = cadastro_usuario($aut_usuario, $usuario, $senha, $aut);
    $response = $resultado["response"];
    $httpcode = $resultado["httpcode"];
    $data = $resultado["data"];
    $raw_response = $resultado["raw_response"];

    if ($httpcode == 201 && isset($response["status"]) && $response["status"] === true) {
        $_SESSION["mensagem"] = "Cadastro bem-sucedido!";
        header("Location: cadastro.php");
        exit();
    } elseif ($httpcode == 400) {
        $_SESSION["mensagem"] = "Parâmetros inválidos ou faltantes.";
        header("Location: cadastro.php");
        exit();
    } else {
        $_SESSION["mensagem"] = "Erro na requisição.";
        $_SESSION["error_data"] = $data;
        $_SESSION["error_response"] = $response;
        $_SESSION["raw_response"] = $raw_response;
        $_SESSION["httpcode"] = $httpcode;
        header("Location: error.php");
        exit();
    }
}
?>

