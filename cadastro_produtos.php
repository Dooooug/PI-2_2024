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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION["input_data"] = $_POST; // Armazenar todos os dados do formulário na sessão
}

$mensagem = isset($_SESSION["mensagem"]) && $_SESSION["mensagem"] !== "Login bem-sucedido!" 
            ? '<div class="success-message">' . $_SESSION["mensagem"] . '</div>' 
            : '';

$cadastro_sucesso = isset($_SESSION["cadastro_sucesso"]) && $_SESSION["cadastro_sucesso"] === true;

// Função cadastro_produto
function cadastro_produto($codArmaz, $dscNome, $fornecedor, $qtdMaxima, $estadoFisico, $substancias, $numeroCas, $concentracao, $classsGHS, $elemento1, $elemento2, $elemento3, $elemento4, $elemento5, $elemento6, $elemento7, $elemento8, $elemento9, $advertencia, $frasePerigo, $frasePrecaucao) {
    $url = "http://191.252.110.154:3000/api/cadastro_produto";
    $data = [
        "codArmaz" => $codArmaz,
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
    $_SESSION["input_data"] = $_POST; // Armazenar todos os dados do formulário na sessão
    if (empty($_POST["dscNome"]) || empty($_POST["fornecedor"]) || empty($_POST["qtdMaxima"]) || empty($_POST["estadoFisico"]) || empty($_POST["substancias"]) || empty($_POST["numeroCas"]) || empty($_POST["concentracao"]) || empty($_POST["classsGHS"]) || empty($_POST["advertencia"]) || empty($_POST["frasePerigo"]) || empty($_POST["frasePrecaucao"])) {
        $_SESSION["mensagem"] = "Todos os campos são obrigatórios.";
        header("Location: cadastro_produtos.php");
        exit();
    }

    // Definir valores dos elementos como 0 ou 1
    $elemento1 = !empty($_POST["elemento1"]) ? 1 : 0;
    $elemento2 = !empty($_POST["elemento2"]) ? 1 : 0;
    $elemento3 = !empty($_POST["elemento3"]) ? 1 : 0;
    $elemento4 = !empty($_POST["elemento4"]) ? 1 : 0;
    $elemento5 = !empty($_POST["elemento5"]) ? 1 : 0;
    $elemento6 = !empty($_POST["elemento6"]) ? 1 : 0;
    $elemento7 = !empty($_POST["elemento7"]) ? 1 : 0;
    $elemento8 = !empty($_POST["elemento8"]) ? 1 : 0;
    $elemento9 = !empty($_POST["elemento9"]) ? 1 : 0;

    ; // Verificar se todos os campos obrigatórios foram preenchidos $required_fields = ["descNome", "fornecedor", "qtdMaxima", "estadoFisico", "substancias", "numeroCAS", "concentracao", "classsGHS", "advertencia", "frasePerigo", "frasePrecaucao"]; foreach ($required_fields as $field) { if (empty($_POST[$field])) { $_SESSION["mensagem"] = "Todos os campos são obrigatórios."; header("Location: cadastro_produtos.php"); exit(); }

    $response = cadastro_produto(
        $_POST["codArmaz"],
        $_POST["dscNome"],
        $_POST["fornecedor"],
        $_POST["qtdMaxima"],
        $_POST["estadoFisico"],
        $_POST["substancias"],
        $_POST["numeroCas"],
        $_POST["concentracao"],
        $_POST["classsGHS"],
        $_POST["elemento1"],
        $_POST["elemento2"],
        $_POST["elemento3"],
        $_POST["elemento4"],
        $_POST["elemento5"],
        $_POST["elemento6"],
        $_POST["elemento7"],
        $_POST["elemento8"],
        $_POST["elemento9"],
        $_POST["advertencia"],
        $_POST["frasePerigo"],
        $_POST["frasePrecaucao"]
    );

    $httpcode = $response["httpcode"];
    $response_data = $response["response"];

    if ($httpcode == 201 && isset($response_data["status"]) && $response_data["status"] === true) {
        $_SESSION["mensagem"] = "Cadastro bem-sucedido!";
        header("Location: cadastro_produtos.php");
        exit();
    } elseif ($httpcode == 400) {
        $_SESSION["mensagem"] = "Parâmetros inválidos ou faltantes.";
        header("Location: cadastro_produtos.php");
        exit();
    } else {
        $_SESSION["mensagem"] = "Erro na requisição.";
        $_SESSION["error_data"] = $response["data"];
        $_SESSION["error_response"] = $response["response"];
        $_SESSION["raw_response"] = $response["raw_response"];
        $_SESSION["httpcode"] = $httpcode;
        header("Location: error.php");
        exit();
    }
}

// Verifica se há dados armazenados na sessão e os utiliza como valores padrão
$descNome = isset($_SESSION["input_data"]["descNome"]) ? $_SESSION["input_data"]["descNome"] : '';
$fornecedor = isset($_SESSION["input_data"]["fornecedor"]) ? $_SESSION["input_data"]["fornecedor"] : '';
$qtdMaxima = isset($_SESSION["input_data"]["qtdMaxima"]) ? $_SESSION["input_data"]["qtdMaxima"] : '';
$estadoFisico = isset($_SESSION["input_data"]["estadoFisico"]) ? $_SESSION["input_data"]["estadoFisico"] : '';
$substancias = isset($_SESSION["input_data"]["substancias"]) ? $_SESSION["input_data"]["substancias"] : '';
$numeroCAS = isset($_SESSION["input_data"]["numeroCAS"]) ? $_SESSION["input_data"]["numeroCAS"] : '';
$concentracao = isset($_SESSION["input_data"]["concentracao"]) ? $_SESSION["input_data"]["concentracao"] : '';
$classsGHS = isset($_SESSION["input_data"]["classsGHS"]) ? $_SESSION["input_data"]["classsGHS"] : '';
$elemento1 = isset($_SESSION["input_data"]["elemento1"]) ? $_SESSION["input_data"]["elemento1"] : '';
$elemento2 = isset($_SESSION["input_data"]["elemento2"]) ? $_SESSION["input_data"]["elemento2"] : '';
$elemento3 = isset($_SESSION["input_data"]["elemento3"]) ? $_SESSION["input_data"]["elemento3"] : '';
$elemento4 = isset($_SESSION["input_data"]["elemento4"]) ? $_SESSION["input_data"]["elemento4"] : '';
$elemento5 = isset($_SESSION["input_data"]["elemento5"]) ? $_SESSION["input_data"]["elemento5"] : '';
$elemento6 = isset($_SESSION["input_data"]["elemento6"]) ? $_SESSION["input_data"]["elemento6"] : '';
$elemento7 = isset($_SESSION["input_data"]["elemento7"]) ? $_SESSION["input_data"]["elemento7"] : '';
$elemento8 = isset($_SESSION["input_data"]["elemento8"]) ? $_SESSION["input_data"]["elemento8"] : '';
$elemento9 = isset($_SESSION["input_data"]["elemento9"]) ? $_SESSION["input_data"]["elemento9"] : '';
$advertencia = isset($_SESSION["input_data"]["advertencia"]) ? $_SESSION["input_data"]["advertencia"] : '';
$frasePerigo = isset($_SESSION["input_data"]["frasePerigo"]) ? $_SESSION["input_data"]["frasePerigo"] : '';
$frasePrecaucao = isset($_SESSION["input_data"]["frasePrecaucao"]) ? $_SESSION["input_data"]["frasePrecaucao"] : '';

$content = '
    <body>
        <div class="sidebar">
         <h2> Menu Produtos</h2>
            <ul>
                <li><a href="dashboard.php">Inicio</a></li>
                <li><a href="cadastro_produtos.php">Cadastro de Produtos</a></li>
                <li><a href="consulta_produtos.php">Consultar de produtos</a></li>
                <li><a href="dashboard.php">Voltar</a></li>
            </ul>
        </div>
            
        <div class="container">
            <h2>Cadastro de Produtos Químicos</h2>
            ' . $mensagem . '
            ' . $authorized_message . '
            <form id="cadastro-form" action="cadastro_produtos.php" method="POST">';
            
if (!$cadastro_sucesso) {
    $content .= '
                <div class="form-group">
                    <input type="text" name="descNome" placeholder="Nome do Produto" value="' . htmlspecialchars($descNome) . '" required>
                </div>
                <div class="form-group">
                    <input type="text" name="fornecedor" placeholder="Fornecedor" value="' . htmlspecialchars($fornecedor) . '" required>
                </div>
                <div class="form-group">
                    <input type="number" name="qtdMaxima" min="1" placeholder="Quantidade" value="' . htmlspecialchars($qtdMaxima) . '" required>
                </div>
                <div class="form-group">
                    <label for="estadoFisico"> Estado Fisico:</label>
                    <select name="estadoFisico" id="estadoFisico">
                        <option value="Sólido"' . ($estadoFisico == 'Sólido' ? ' selected' : '') . '> Sólido</option>
                        <option value="Liquido"' . ($estadoFisico == 'Liquido' ? ' selected' : '') . '> Liquido</option>
                        <option value="Gasoso"' . ($estadoFisico == 'Gasoso' ? ' selected' : '') . '> Gasoso</option>
                    </select>
                </div>
                <div class="form-group">
                    <input type="text" name="substancias" placeholder="Substâncias" value="' . htmlspecialchars($substancias) . '" required>
                </div>
                <div class="form-group">
                    <input type="text" name="numeroCAS" placeholder="CAS" value="' . htmlspecialchars($numeroCAS) . '" required>
                </div>
                <div class="form-group">
                    <input type="number" name="concentracao" placeholder="Concentração" min="1" value="' . htmlspecialchars($concentracao) . '" required>
                </div>
                <div class="form-group">
                    <input type="text" name="classsGHS" placeholder="Classe GHS" value="' . htmlspecialchars($classsGHS) . '" required>
                </div>
                <div class="form-group">
                    <input type="text" name="elemento1" placeholder="Elemento 1" value="' . htmlspecialchars($elemento1) . '" required>
                </div>
                <div class="form-group">
                    <input type="text" name="elemento2" placeholder="Elemento 2" value="' . htmlspecialchars($elemento2) . '" required>
                </div>
                <div class="form-group">
                    <input type="text" name="elemento3" placeholder="Elemento 3" value="' . htmlspecialchars($elemento3) . '" required>
                </div>
                <div class="form-group">
                    <input type="text" name="elemento4" placeholder="Elemento 4" value="' . htmlspecialchars($elemento4) . '" required>
                </div>
                <div class="form-group">
                    <input type="text" name="elemento5" placeholder="Elemento 5" value="' . htmlspecialchars($elemento5) . '" required>
                </div>
                <div class="form-group">
                    <input type="text" name="elemento6" placeholder="Elemento 6" value="' . htmlspecialchars($elemento6) . '" required>
                </div>
                <div class="form-group">
                    <input type="text" name="elemento7" placeholder="Elemento 7" value="' . htmlspecialchars($elemento7) . '" required>
                </div>
                <div class="form-group">
                    <input type="text" name="elemento8" placeholder="Elemento 8" value="' . htmlspecialchars($elemento8) . '" required>
                </div>
                <div class="form-group">
                    <input type="text" name="elemento9" placeholder="Elemento 9" value="' . htmlspecialchars($elemento9) . '" required>
                </div>
                <div class="form-group">
                    <input type="text" name="advertencia" placeholder="Advertência" value="' . htmlspecialchars($advertencia) . '" required>
                </div>
                <div class="form-group">
                    <input type="text" name="frasePerigo" placeholder="Frase de Perigo" value="' . htmlspecialchars($frasePerigo) . '" required>
                </div>
                <div class="form-group">
                    <input type="text" name="frasePrecaucao" placeholder="Frase de Precaução" value="' . htmlspecialchars($frasePrecaucao) . '" required>
                </div>';
}

$content = '
    <body>
        <div class="sidebar">
         <h2> Menu Produtos</h2>
            <ul>
                <li><a href="dashboard.php">Inicio</a></li>
                <li><a href="cadastro_produtos.php">Cadastro de Produtos</a></li>
                <li><a href="consulta_produtos.php">Consultar de produtos</a></li>
                <li><a href="dashboard.php">Voltar</a></li>
            </ul>
        </div>
            
        <div class="container">
            <h2>Cadastro de Produtos Químicos</h2>
            ' . $mensagem . '
            ' . $authorized_message . '
            <form id="cadastro-form" action="cadastro_produtos.php" method="POST">';
            
if (!$cadastro_sucesso) {
    $content .= '
                <div class="form-group">
                    <input type="text" name="descNome" placeholder="Nome do Produto" value="' . htmlspecialchars(isset($_SESSION["input_data"]["descNome"]) ? $_SESSION["input_data"]["descNome"] : '') . '" required>
                </div>
                <div class="form-group">
                    <input type="text" name="fornecedor" placeholder="Fornecedor" value="' . htmlspecialchars(isset($_SESSION["input_data"]["fornecedor"]) ? $_SESSION["input_data"]["fornecedor"] : '') . '" required>
                </div>
                <div class="form-group">
                    <input type="number" name="qtdMaxima" min="1" placeholder="Quantidade" value="' . htmlspecialchars(isset($_SESSION["input_data"]["qtdMaxima"]) ? $_SESSION["input_data"]["qtdMaxima"] : '') . '" required>
                </div>
                <div class="form-group">
                    <label for="estadoFisico"> Estado Fisico:</label>
                    <select name="estadoFisico" id="estadoFisico">
                        <option value="Sólido"' . (isset($_SESSION["input_data"]["estadoFisico"]) && $_SESSION["input_data"]["estadoFisico"] == 'Sólido' ? ' selected' : '') . '> Sólido</option>
                        <option value="Liquido"' . (isset($_SESSION["input_data"]["estadoFisico"]) && $_SESSION["input_data"]["estadoFisico"] == 'Liquido' ? ' selected' : '') . '> Liquido</option>
                        <option value="Gasoso"' . (isset($_SESSION["input_data"]["estadoFisico"]) && $_SESSION["input_data"]["estadoFisico"] == 'Gasoso' ? ' selected' : '') . '> Gasoso</option>
                    </select>
                </div>
                <div class="form-group">
                    <input type="text" name="substancias" placeholder="Substâncias" value="' . htmlspecialchars(isset($_SESSION["input_data"]["substancias"]) ? $_SESSION["input_data"]["substancias"] : '') . '" required>
                </div>
                <div class="form-group">
                    <input type="text" name="numeroCAS" placeholder="CAS" value="' . htmlspecialchars(isset($_SESSION["input_data"]["numeroCAS"]) ? $_SESSION["input_data"]["numeroCAS"] : '') . '" required>
                </div>
                <div class="form-group">
                    <input type="number" name="concentracao" placeholder="Concentração" min="1" value="' . htmlspecialchars(isset($_SESSION["input_data"]["concentracao"]) ? $_SESSION["input_data"]["concentracao"] : '') . '" required>
                </div>
                <div class="form-group">
                    <input type="text" name="classsGHS" placeholder="Classe GHS" value="' . htmlspecialchars(isset($_SESSION["input_data"]["classsGHS"]) ? $_SESSION["input_data"]["classsGHS"] : '') . '" required>
                </div>
                <div class="form-group">
                    <input type="text" name="elemento1" placeholder="Elemento 1" value="' . htmlspecialchars(isset($_SESSION["input_data"]["elemento1"]) ? $_SESSION["input_data"]["elemento1"] : '') . '" required>
                </div>
                <div class="form-group">
                    <input type="text" name="elemento2" placeholder="Elemento 2" value="' . htmlspecialchars(isset($_SESSION["input_data"]["elemento2"]) ? $_SESSION["input_data"]["elemento2"] : '') . '" required>
                </div>
                <div class="form-group">
                    <input type="text" name="elemento3" placeholder="Elemento 3" value="' . htmlspecialchars(isset($_SESSION["input_data"]["elemento3"]) ? $_SESSION["input_data"]["elemento3"] : '') . '" required>
                </div>
                <div class="form-group">
                    <input type="text" name="elemento4" placeholder="Elemento 4" value="' . htmlspecialchars(isset($_SESSION["input_data"]["elemento4"]) ? $_SESSION["input_data"]["elemento4"] : '') . '" required>
                </div>
                <div class="form-group">
                    <input type="text" name="elemento5" placeholder="Elemento 5" value="' . htmlspecialchars(isset($_SESSION["input_data"]["elemento5"]) ? $_SESSION["input_data"]["elemento5"] : '') . '" required>
                </div>
                <div class="form-group">
                    <input type="text" name="elemento6" placeholder="Elemento 6" value="' . htmlspecialchars(isset($_SESSION["input_data"]["elemento6"]) ? $_SESSION["input_data"]["elemento6"] : '') . '" required>
                </div>
                <div class="form-group">
                    <input type="text" name="elemento7" placeholder="Elemento 7" value="' . htmlspecialchars(isset($_SESSION["input_data"]["elemento7"]) ? $_SESSION["input_data"]["elemento7"] : '') . '" required>
                </div>
                <div class="form-group">
                    <input type="text" name="elemento8" placeholder="Elemento 8" value="' . htmlspecialchars(isset($_SESSION["input_data"]["elemento8"]) ? $_SESSION["input_data"]["elemento8"] : '') . '" required>
                </div>
                <div class="form-group">
                    <input type="text" name="elemento9" placeholder="Elemento 9" value="' . htmlspecialchars(isset($_SESSION["input_data"]["elemento9"]) ? $_SESSION["input_data"]["elemento9"] : '') . '" required>
                </div>
                <div class="form-group">
                    <input type="text" name="advertencia" placeholder="Advertência" value="' . htmlspecialchars(isset($_SESSION["input_data"]["advertencia"]) ? $_SESSION["input_data"]["advertencia"] : '') . '" required>
                </div>
                <div class="form-group">
                    <input type="text" name="frasePerigo" placeholder="Frase de Perigo" value="' . htmlspecialchars(isset($_SESSION["input_data"]["frasePerigo"]) ? $_SESSION["input_data"]["frasePerigo"] : '') . '" required>
                </div>
                <div class="form-group">
                    <input type="text" name="frasePrecaucao" placeholder="Frase de Precaução" value="' . htmlspecialchars(isset($_SESSION["input_data"]["frasePrecaucao"]) ? $_SESSION["input_data"]["frasePrecaucao"] : '') . '" required>
                </div>';
}

$content .= '
                <button type="submit">' . ($cadastro_sucesso ? 'Novo Produto' : 'Cadastrar') . '</button>
            </form>
        </div>
    </body>
';

// Adicionando a validação JavaScript no final do HTML
$content .= '
    <script>
        document.getElementById("cadastro-form").addEventListener("submit", function(event) {
            var numeroCAS = document.getElementsByName("numeroCAS")[0].value;
            var regex = /^[0-9\-]+$/;
            if (!regex.test(numeroCAS)) {
                event.preventDefault();
                alert("Por favor, insira um número CAS válido (apenas números e hífens).");
            }
        });
    </script>
';

include('template.php');
?>
