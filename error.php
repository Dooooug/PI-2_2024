<?php

// Ativar a exibição de erros
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Verificar se a variável $_SESSION está definida
$error_data = $_SESSION["error_data"] ?? "Dados não disponíveis";
$error_response = $_SESSION["error_response"] ?? "Resposta não disponível";
$raw_response = $_SESSION["raw_response"] ?? "Resposta bruta não disponível";
$httpcode = $_SESSION["httpcode"] ?? "Código HTTP não disponível";

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erro</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .error-container {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .error-container h2 {
            margin-top: 0;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <h2>Status de Erro</h2>
        <p><strong>Dados Enviados:</strong> <?php echo print_r($error_data, true); ?></p>
        <p><strong>Resposta da API:</strong> <?php echo print_r($error_response, true); ?></p>
        <p><strong>Resposta Bruta:</strong> <?php echo $raw_response; ?></p>
        <p><strong>Código HTTP:</strong> <?php echo $httpcode; ?></p>
    </div>
</body>
</html>
