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

// Função para buscar os produtos cadastrados
function getProdutosCadastrados() {
    $url = "http://191.252.110.154:3000/api/consulta_produto"; // Supondo que exista uma rota para consultar os produtos cadastrados

    // Inicializa a sessão cURL
    $ch = curl_init($url);
    
    // Configurações do cURL
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    // Executa a solicitação
    $response = curl_exec($ch);
    
    // Fecha a sessão cURL
    curl_close($ch);
    
    return json_decode($response, true);
}

// Função para buscar os usuários cadastrados
function getUsuariosCadastrados() {
    $url = "http://191.252.110.154:3000/api/consulta_usuarios"; // Supondo que exista uma rota para consultar os usuários cadastrados

    // Inicializa a sessão cURL
    $ch = curl_init($url);
    
    // Configurações do cURL
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    // Executa a solicitação
    $response = curl_exec($ch);
    
    // Fecha a sessão cURL
    curl_close($ch);
    
    return json_decode($response, true);
}

// Função para buscar os locais de armazenamento cadastrados
function getLocaisArmazenamentoCadastrados() {
    $url = "http://191.252.110.154:3000/api/consulta_armazenamento"; // Supondo que exista uma rota para consultar os locais de armazenamento

    // Inicializa a sessão cURL
    $ch = curl_init($url);
    
    // Configurações do cURL
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    // Executa a solicitação
    $response = curl_exec($ch);
    
    // Fecha a sessão cURL
    curl_close($ch);
    
    return json_decode($response, true);
}

// Obtém os produtos cadastrados
$produtos = getProdutosCadastrados();

// Verifica se a resposta está correta
if (!is_array($produtos)) {
    die('Erro ao buscar produtos.');
}

// Variáveis para armazenar informações dos produtos
$numero_produtos = 0;
$ultimo_produto = "Nenhum produto cadastrado.";

// Verifica a quantidade de produtos e o último produto cadastrado
if (count($produtos) > 0) {
    $numero_produtos = count($produtos);
    $ultimo_produto = $produtos[$numero_produtos - 1]['dscNome'] . " (" . $produtos[$numero_produtos - 1]['fornecedor'] . ")";
}

// Obtém os usuários cadastrados
$usuarios = getUsuariosCadastrados();

// Verifica se a resposta está correta
if (!is_array($usuarios)) {
    die('Erro ao buscar usuários.');
}

// Variáveis para armazenar informações dos usuários
$numero_usuarios = 0;

// Verifica a quantidade de usuários cadastrados
if (count($usuarios) > 0) {
    $numero_usuarios = count($usuarios);
}

// Obtém os locais de armazenamento cadastrados
$locais_armazenamento = getLocaisArmazenamentoCadastrados();

// Verifica se a resposta está correta
if (!is_array($locais_armazenamento)) {
    die('Erro ao buscar locais de armazenamento.');
}

// Variáveis para armazenar informações dos locais de armazenamento
$numero_locais = 0;

// Verifica a quantidade de locais de armazenamento cadastrados
if (count($locais_armazenamento) > 0) {
    $numero_locais = count($locais_armazenamento);
}

$content = '
</div>
<div class="main-content">
    <div class="card-container">
    <div class="card">
        <h3>Último Produto Químico Cadastrado</h3>
        <p>Moddus</p>
    </div>
    <div class="card">
        <h3>Total de Produtos Cadastrados</h3>
        <p>' . htmlspecialchars($numero_produtos) . '</p>
    </div>
    <div class="card">
        <h3>Total de Usuários Cadastrados</h3>
        <p>' . htmlspecialchars($numero_usuarios) . '</p>
    </div>
    <div class="card">
        <h3>Total de Locais de Armazenamento</h3>
        <p>' . htmlspecialchars($numero_locais) . '</p>
    </div>
';

include('template.php');
?>
