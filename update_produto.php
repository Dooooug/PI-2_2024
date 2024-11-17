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
    $cod_produto = $_POST["cod"];
    $dscNome = $_POST["dscNome"];
    $fornecedor = $_POST["fornecedor"];
    $qtdMaxima = $_POST["qtdMaxima"];
    $estadoFisico = $_POST["estadoFisico"];
    $substancias = $_POST["substancias"];
    $numeroCas = $_POST["numeroCas"];
    $concentracao = $_POST["concentracao"];
    $classsGHS = $_POST["classsGHS"];
    $elemento1 = !empty($_POST["elemento1"]) ? 1 : 0;
    $elemento2 = !empty($_POST["elemento2"]) ? 1 : 0;
    $elemento3 = !empty($_POST["elemento3"]) ? 1 : 0;
    $elemento4 = !empty($_POST["elemento4"]) ? 1 : 0;
    $elemento5 = !empty($_POST["elemento5"]) ? 1 : 0;
    $elemento6 = !empty($_POST["elemento6"]) ? 1 : 0;
    $elemento7 = !empty($_POST["elemento7"]) ? 1 : 0;
    $elemento8 = !empty($_POST["elemento8"]) ? 1 : 0;
    $elemento9 = !empty($_POST["elemento9"]) ? 1 : 0;
    $advertencia = $_POST["advertencia"];
    $frasePerigo = $_POST["frasePerigo"];
    $frasePrecaucao = $_POST["frasePrecaucao"];

    // Verificar se os campos obrigatórios estão preenchidos
    if (empty($cod_produto) || empty($dscNome) || empty($fornecedor) || empty($qtdMaxima) || empty($estadoFisico) || empty($substancias) || empty($numeroCas) || empty($concentracao) || empty($classsGHS) || empty($advertencia) || empty($frasePerigo) || empty($frasePrecaucao)) {
        $_SESSION["mensagem"] = "Todos os campos obrigatórios devem ser preenchidos.";
        header("Location: update_produto.php");
        exit();
    }

    // Atualizar os dados no banco de dados
    $url = "http://191.252.110.154:3000/api/update_produto";
    $data = [
        "cod" => $cod_produto,
        "dscNome" => $dscNome,
        "fornecedor" => $fornecedor,
        "qtdMaxima" => $qtdMaxima,
        "estadoFisico" => $estadoFisico,
        "substancias" => $substancias,
        "numeroCas" => $numeroCas,
        "concentracao" => $concentracao,
        "classsGHS" => $classsGHS,
        "elemento1" => $elemento1,
        "elemento2" => $elemento2,
        "elemento3" => $elemento3,
        "elemento4" => $elemento4,
        "elemento5" => $elemento5,
        "elemento6" => $elemento6,
        "elemento7" => $elemento7,
        "elemento8" => $elemento8,
        "elemento9" => $elemento9,
        "advertencia" => $advertencia,
        "frasePerigo" => $frasePerigo,
        "frasePrecaucao" => $frasePrecaucao
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
        $_SESSION["mensagem"] = "Produto atualizado com sucesso!";
    } else {
        $_SESSION["mensagem"] = "Erro ao atualizar produto: " . ($decoded_response["message"] ?? "Desconhecido");
    }

    header("Location: consulta_produtos.php");
    exit();
} else {
    $_SESSION["mensagem"] = "Método de requisição inválido.";
    header("Location: consulta_produtos.php");
    exit();
}

// Conteúdo HTML para atualizar produto
$content = '
    <body>
        <div class="container">
            <h2>Atualização de Produtos</h2>
            ' . htmlspecialchars($mensagem) . '
            <form action="update_produto.php" method="POST">
                <div class="form-group">
                    <label for="cod">Código do Produto</label>
                    <input type="number" id="cod" name="cod" value="' . htmlspecialchars($cod_produto) . '" readonly>
                </div>
                <div class="form-group">
                    <label for="dscNome">Nome do Produto</label>
                    <input type="text" id="dscNome" name="dscNome" value="' . htmlspecialchars($dscNome) . '" placeholder="Nome do Produto" required>
                </div>
                <div class="form-group">
                    <label for="fornecedor">Fornecedor</label>
                    <input type="text" id="fornecedor" name="fornecedor" value="' . htmlspecialchars($fornecedor) . '" placeholder="Fornecedor" required>
                </div>
                <div class="form-group">
                    <label for="qtdMaxima">Quantidade Máxima</label>
                    <input type="number" id="qtdMaxima" name="qtdMaxima" value="' . htmlspecialchars($qtdMaxima) . '" placeholder="Quantidade Máxima" required>
                </div>
                <div class="form-group">
                    <label for="estadoFisico">Estado Físico</label>
                    <input type="text" id="estadoFisico" name="estadoFisico" value="' . htmlspecialchars($estadoFisico) . '" placeholder="Estado Físico" required>
                </div>
                <div class="form-group">
                    <label for="substancias">Substâncias</label>
                    <input type="text" id="substancias" name="substancias" value="' . htmlspecialchars($substancias) . '" placeholder="Substâncias" required>
                </div>
                <div class="form-group">
                    <label for="numeroCas">Número CAS</label>
                    <input type="text" id="numeroCas" name="numeroCas" value="' . htmlspecialchars($numeroCas) . '" placeholder="Número CAS" required>
                </div>
                <div class="form-group">
                    <label for="concentracao">Concentração</label>
                    <input type="text" id="concentracao" name="concentracao" value="' . htmlspecialchars($concentracao) . '" placeholder="Concentração" required>
                </div>
                <div class="form-group">
                    <label for="classsGHS">Classificação GHS</label>
                    <input type="text" id="classsGHS" name="classsGHS" value="' . htmlspecialchars($classsGHS) . '" placeholder="Classificação GHS" required>
                </div>
                <div class="form-group">
                    <label for="elemento1">Elemento 1</label>
                    <input type="number" id="elemento1" name="elemento1" value="' . htmlspecialchars($elemento1) . '" min="0" max="1">
                </div>
                <div class="form-group">
                    <label for="elemento2">Elemento 2</label>
                    <input type="number" id="elemento2" name="elemento2" value="' . htmlspecialchars($elemento2) . '" min="0" max="1">
                </div>
                <div class="form-group">
                    <label for="elemento3">Elemento 3</label>
                    <input type="number" id="elemento3" name="elemento3" value="' . htmlspecialchars($elemento3) . '" min="0" max="1">
                </div>
                <div class="form-group">
                    <label for="elemento4">Elemento 4</label>
                    <input type="number" id="elemento4" name="elemento4" value="' . htmlspecialchars($elemento4) . '" min="0" max="1">
                </div>
                <div class="form-group">
                    <label for="elemento5">Elemento 5</label>
                    <input type="number" id="elemento5" name="elemento5" value="' . htmlspecialchars($elemento5) . '" min="0" max="1">
                </div>
                <div class="form-group">
                    <label for="elemento6">Elemento 6</label>
                    <input type="number" id="elemento6" name="elemento6" value="' . htmlspecialchars($elemento6) . '" min="0" max="1">
                </div>
                <div class="form-group">
                    <label for="elemento7">Elemento 7</label>
                    <input type="number" id="elemento7" name="elemento7" value="' . htmlspecialchars($elemento7) . '" min="0" max="1">
                </div>
                <div class="form-group">
                    <label for="elemento8">Elemento 8</label>
                    <input type="number" id="elemento8" name="elemento8" value="' . htmlspecialchars($elemento8) . '" min="0" max="1">
                </div>
                <div class="form-group">
                    <label for="elemento9">Elemento 9</label>
                    <input type="number" id="elemento9" name="elemento9" value="' . htmlspecialchars($elemento9) . '" min="0" max="1">
                </div>
                <div class="form-group">
                    <label for="advertencia">Advertência</label>
                    <input type="text" id="advertencia" name="advertencia" value="' . htmlspecialchars($advertencia) . '" placeholder="Advertência" required>
                </div>
                <div class="form-group">
                    <label for="frasePerigo">Frase de Perigo</label>
                    <input type="text" id="frasePerigo" name="frasePerigo" value="' . htmlspecialchars($frasePerigo) . '" placeholder="Frase de Perigo" required>
                </div>
                <div class="form-group">
                    <label for="frasePrecaucao">Frase de Precaução</label>
                    <input type="text" id="frasePrecaucao" name="frasePrecaucao" value="' . htmlspecialchars($frasePrecaucao) . '" placeholder="Frase de Precaução" required>
                </div>
                <button type="submit">Atualizar</button>
                <button type="button" onclick="window.location.href=\'consulta_produtos.php\'">Voltar</button>
            </form>
        </div>
    </body>
';
include('template.php');
?>
