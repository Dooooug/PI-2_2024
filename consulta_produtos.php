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

// Função para consultar todos os produtos
function consulta_produtos() {
    $url = "http://191.252.110.154:3000/api/consulta_produto";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if (curl_errno($ch)) {
        $error_msg = curl_error($ch);
        curl_close($ch);
        return [
            "response" => [],
            "httpcode" => $httpcode,
            "error" => true,
            "message" => "Erro na requisição: " . $error_msg
        ];
    }

    curl_close($ch);

    // Verificar se a resposta está vazia
    if (empty($response)) {
        return [
            "response" => [],
            "httpcode" => $httpcode,
            "error" => true,
            "message" => "Resposta vazia da API"
        ];
    }

    // Decodificar a resposta da API
    $decoded_response = json_decode($response, true);

    // Verificar se houve erro na decodificação do JSON
    if (json_last_error() !== JSON_ERROR_NONE) {
        return [
            "response" => [],
            "httpcode" => $httpcode,
            "error" => true,
            "message" => "Erro ao decodificar a resposta: " . json_last_error_msg()
        ];
    }

    return [
        "response" => $decoded_response,
        "httpcode" => $httpcode,
        "error" => false
    ];
}

// Obter a lista de produtos
$resultado = consulta_produtos();
if ($resultado['error'] ?? false) {
    $produtos = [];
    $mensagem = '<div class="message">Erro ao buscar produtos: ' . htmlspecialchars($resultado['message'], ENT_QUOTES, 'UTF-8') . '</div>';
} else if (empty($resultado['response'])) {
    $produtos = [];
    $mensagem = '<div class="message">Nenhum produto encontrado.</div>';
} else {
    $produtos = $resultado['response'];
    $mensagem = '<div class="message">Produtos encontrados com sucesso!</div>';
}

// Certifique-se de que a variável não é nula antes de passar para htmlspecialchars
$codigo = isset($produto['cod']) ? htmlspecialchars($produto['cod'], ENT_QUOTES, 'UTF-8') : '';
$nomeProduto = isset($produto['dscNome']) ? htmlspecialchars($produto['dscNome'], ENT_QUOTES, 'UTF-8') : '';
$fornecedor = isset($produto['fornecedor']) ? htmlspecialchars($produto['fornecedor'], ENT_QUOTES, 'UTF-8') : '';

$content = '
<body>
    <div class="sidebar">
        <h2> Menu Produtos</h2>
        <ul>
            <li><a href="dashboard.php">Inicio</a></li>
            <li><a href="cadastro_produtos.php">Cadastro de Produtos</a></li>
            <li><a href="consulta_produtos.php">Consultar Produtos</a></li>
            <li><a href="dashboard.php">Voltar</a></li>
        </ul>
    </div>
    <h2>Lista de Produtos</h2>
    ' . $mensagem . '
    <table>
        <thead>
            <tr>
                <th>Código</th>
                <th>Nome do Produto</th>
                <th>Fornecedor</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
';

// Inserir dados dos produtos
if (is_array($produtos) && !empty($produtos)) {
    foreach ($produtos as $produto) {
        if (is_array($produto)) {
            $codigo = isset($produto['cod']) ? htmlspecialchars($produto['cod'], ENT_QUOTES, 'UTF-8') : '';
            $nomeProduto = isset($produto['dscNome']) ? htmlspecialchars($produto['dscNome'], ENT_QUOTES, 'UTF-8') : '';
            $fornecedor = isset($produto['fornecedor']) ? htmlspecialchars($produto['fornecedor'], ENT_QUOTES, 'UTF-8') : '';

            $content .= '
            <tr>
                <td>' . $codigo . '</td>
                <td>' . $nomeProduto . '</td>
                <td>' . $fornecedor . '</td>
                <td>
                    <form action="update_produto.php" method="POST" style="display:inline;">
                        <input type="hidden" name="cod" value="' . $codigo . '">
                        <input type="hidden" name="dscNome" value="' . $nomeProduto . '">
                        <button type="submit" class="btn btn-edit">Editar</button>
                    </form>
                    <form action="excluir_produto.php" method="POST" style="display:inline;">
                        <input type="hidden" name="cod" value="' . $codigo . '">
                        <button type="submit" class="btn btn-delete">Excluir</button>
                    </form>
                </td>
            </tr>
            ';
        }
    }
} else {
    $content .= '<tr><td colspan="4">Nenhum produto encontrado.</td></tr>';
}

$content .= '
        </tbody>
    </table>
</body>
';

include('template.php');
?>
