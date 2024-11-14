<?php

session_start();

function validar_login($usuario, $senha) {
    $url = "http://191.252.110.154:3000/api/validar_login";
    $data = ["usuario" => $usuario, "senha" => $senha];

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
        $response = json_encode(["status" => false, "aut" => 0, "error" => curl_error($ch)]);
    }
    curl_close($ch);
    return ["response" => json_decode($response, true), "httpcode" => $httpcode];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["usuario"]) || empty($_POST["senha"])) {
        $_SESSION["mensagem"] = "Todos os campos são obrigatórios.";
        header("Location: index.php");
        exit();
    }

    $usuario = $_POST["usuario"];
    $senha = $_POST["senha"];

    $resultado = validar_login($usuario, $senha);
    $response = $resultado["response"];
    $httpcode = $resultado["httpcode"];

    if ($httpcode == 200 && $response["status"] === true) {
        $_SESSION["mensagem"] = "Login bem-sucedido!";
        $_SESSION["aut"] = $response["aut"];
        header("Location: dashboard.php");
        exit();
    } elseif ($httpcode == 401) {
        $_SESSION["mensagem"] = "Login inválido.";
        header("Location: index.php");
        exit();
    } elseif ($httpcode == 400) {
        $_SESSION["mensagem"] = "Parâmetros inválidos ou faltantes.";
        header("Location: index.php");
        exit();
    } else {
        $_SESSION["mensagem"] = "Erro na requisição.";
        header("Location: index.php");
        exit();
    }
}
?>
