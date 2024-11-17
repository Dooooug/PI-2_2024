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

// Função para consultar a API e retornar os arquivos
function listarArquivos($search = '') {
    $url = "http://191.252.110.154:3000/api/listar_arquivos";  // Ajustar a URL conforme necessário
    
    // Inicializa a sessão cURL
    $ch = curl_init();
    
    // Configurações do cURL
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPGET, true);
    
    // Executa a solicitação
    $response = curl_exec($ch);
    
    // Verifica se houve erro na solicitação
    if ($response === false) {
        echo "Erro na solicitação cURL: " . curl_error($ch);
        curl_close($ch);
        return [];
    }
    
    // Fecha a sessão cURL
    curl_close($ch);
    
    // Depuração: Imprime a resposta bruta da API
    echo "<pre>Resposta bruta da API: " . htmlspecialchars($response) . "</pre>";
    
    // Decodifica a resposta JSON
    $arquivos = json_decode($response, true);
    
    // Verifica se a decodificação foi bem-sucedida e é um array
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo "Erro ao decodificar JSON: " . json_last_error_msg();
        return [];
    }
    
    // Verifica se a resposta é um array válido
    if (!is_array($arquivos)) {
        echo "A resposta da API não é um array.";
        return [];
    }
    
    // Filtra os arquivos pela palavra-chave, se fornecida
    if ($search) {
        $arquivos = array_filter($arquivos, function($arquivo) use ($search) {
            return isset($arquivo['file_name']) && strpos(strtolower($arquivo['file_name']), strtolower($search)) !== false;
        });
    }
    
    return $arquivos;
}

// Obtém a palavra-chave de busca, se fornecida
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Obtém a lista de arquivos da API
$arquivos = listarArquivos($search);

// Início do conteúdo dinâmico
$content = '
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Arquivos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
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
        .main-content {
            margin-left: 220px;
            padding: 20px;
        }
        .search-container {
            margin-bottom: 20px;
        }
        .file-list {
            list-style-type: none;
            padding: 0;
        }
        .file-list li {
            padding: 10px;
            border: 1px solid #ccc;
            margin-bottom: 10px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <ul>
            <li><a href="dashboard.php">Inicio</a></li>
            <li><a href="Listar_arquivos.php">Lista de FISPQ</a></li>
            <li><a href="upload_arquivo.php">Upload de FISPQ</a></li>
            <li><a href="dashboard.php">Voltar</a></li>
        </ul>
    </div>
    <div class="main-content">
        <h1>Lista de Arquivos</h1>
        <div class="search-container">
            <form method="GET" action="">
                <input type="text" name="search" placeholder="Buscar por palavra-chave">
                <button type="submit">Buscar</button>
            </form>
        </div>
        <ul class="file-list">
';

if (is_array($arquivos) && count($arquivos) > 0) {
    foreach ($arquivos as $arquivo) {
        if (isset($arquivo['download_url']) && isset($arquivo['file_name'])) {
            $content .= "<li>
                            <a href='{$arquivo['download_url']}' target='_blank'>{$arquivo['file_name']}</a>
                         </li>";
        } else {
            $content .= "<li>Arquivo inválido encontrado.</li>";
        }
    }
} else {
    $content .= "<li>Nenhum arquivo encontrado.</li>";
}

$content .= '
        </ul>
    </div>
</body>
</html>
';

echo $content;
?>
