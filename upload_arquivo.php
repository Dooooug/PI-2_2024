<?php
// Ativar a exibição de erros
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$mensagem = ""; // Inicializa a variável mensagem

// Função para realizar o upload do arquivo
function uploadArquivo($filePath, $cod = null) {
    $url ="http://191.252.110.154:3000/api/upload";
    
    // Adiciona o parâmetro cod à URL, se fornecido
    if ($cod) {
        $url .= "?cod=" . urlencode($cod);
    }
    
    // Verificar se o arquivo foi enviado corretamente
    if (!file_exists($filePath)) {
        return [
            "status" => false,
            "message" => "Arquivo não encontrado no caminho especificado."
        ];
    }

    // Configuração do arquivo para upload
    $file = new CURLFile($filePath);
    $postData = ['file' => $file];
    
    // Inicializa a sessão cURL
    $ch = curl_init();
    
    // Configurações do cURL
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10); // Timeout de conexão de 10 segundos
    
    // Executa a solicitação
    $response = curl_exec($ch);
    
    // Verifica se houve erro na solicitação
    if ($response === false) {
        return [
            "status" => false,
            "message" => "Erro na solicitação cURL: " . curl_error($ch)
        ];
    }
    
    // Fecha a sessão cURL
    curl_close($ch);
    
    // Depuração: Imprime a resposta bruta da API
    echo "<pre>Resposta bruta da API: " . htmlspecialchars($response) . "</pre>";
    
    // Verifica se a resposta é válida e contém JSON
    if (empty($response)) {
        return [
            "status" => false,
            "message" => "Resposta da API está vazia ou é inválida."
        ];
    }

    // Decodifica a resposta JSON
    $result = json_decode($response, true);
    
    // Verifica se a decodificação foi bem-sucedida e é um array
    if (json_last_error() !== JSON_ERROR_NONE) {
        return [
            "status" => false,
            "message" => "Erro ao decodificar JSON: " . json_last_error_msg()
        ];
    }
    
    return $result;
}

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["arquivo"])) {
    // Caminho temporário do arquivo
    $filePath = $_FILES["arquivo"]["tmp_name"];
    
    // Parâmetro cod (opcional)
    $cod = isset($_POST['cod']) ? $_POST['cod'] : null;
    
    // Chama a função de upload
    $result = uploadArquivo($filePath, $cod);
    
    // Define a mensagem de acordo com o resultado
    if ($result['status']) {
        $mensagem = "<div class='success-message'>{$result['message']}</div>";
    } else {
        $mensagem = "<div class='error-message'>{$result['message']}</div>";
    }
}

$content = '
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload de Arquivo</title>
    <style>
        .success-message {
            background-color: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        .error-message {
            background-color: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        .sidebar {
            background-color: #343a40;
            color: white;
            padding: 10px;
            width: 200px;
            height: 100%;
            position: fixed;
            top: 0;
            left: 0;
            overflow-y: auto;
            box-sizing: border-box;
        }
        .sidebar ul {
            list-style-type: none;
            padding: 0;
        }
        .sidebar ul li {
            margin: 20px 0;
        }
        .sidebar ul li a {
            color: white;
            text-decoration: none;
        }
        .container {
            margin-left: 220px;
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Menu FISPQ</h2>
        <ul>
            <li><a href="dashboard.php">Inicio</a></li>
            <li><a href="Listar_arquivos.php">Lista de FISPQ</a></li>
            <li><a href="upload_arquivo.php">Upload de FISPQ</a></li>
            <li><a href="dashboard.php">Voltar</a></li>
        </ul>
    </div>
    <div class="container">
        ' . $mensagem . '
        <h1>Upload de Arquivo</h1>
        <form action="upload_arquivo.php" method="POST" enctype="multipart/form-data">
            <input type="file" name="arquivo" required>
            <input type="text" name="cod" placeholder="Código (opcional)">
            <button type="submit">Enviar</button>
        </form>
    </div>
</body>
</html>
';

echo $content;
?>
