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

function getLocaisArmazenamento() {
    $url = 'http://191.252.110.154:3000/api/consulta_armazenamento'; // Supondo que exista uma rota para listar os locais

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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $desc_local = $_POST['desc_local'];
    
    // Obter a lista de locais existentes
    $locais_existentes = getLocaisArmazenamento();
    $local_existe = false;

    // Verificar se o local já existe
    if (is_array($locais_existentes)) {
        foreach ($locais_existentes as $local) {
            if (strtolower($local['desc_local']) == strtolower($desc_local)) {
                $local_existe = true;
                break;
            }
        }
    }

    if ($local_existe) {
        $_SESSION['mensagem'] = "Erro: O local de armazenamento já existe!";
        $_SESSION['cadastro_sucesso'] = false;
    } else {
        // Dados da requisição
        $data = json_encode(array("desc_local" => $desc_local));

        // Inicializa a sessão cURL
        $ch = curl_init('http://191.252.110.154:3000/api/cadastro_armazenamento');
        
        // Configurações do cURL
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data)
        ));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        
        // Executa a requisição
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        // Fecha a sessão cURL
        curl_close($ch);
        
        // Processa a resposta
        if ($http_code == 201) {
            $_SESSION['mensagem'] = "Armazenamento cadastrado com sucesso!";
            $_SESSION['cadastro_sucesso'] = true;
        } elseif ($http_code == 400) {
            $_SESSION['mensagem'] = "Erro: Parâmetro obrigatório faltante!";
            $_SESSION['cadastro_sucesso'] = false;
        } else {
            $_SESSION['mensagem'] = "Erro ao cadastrar armazenamento.";
            $_SESSION['cadastro_sucesso'] = false;
        }
    }
    
    // Redireciona de volta ao formulário
    header("Location: cadastro_armazenamento.php");
    exit();
}

// Mensagem de sucesso ou erro
$mensagem = isset($_SESSION["mensagem"]) ? '<div class="success-message">' . $_SESSION["mensagem"] . '</div>' : '';
$cadastro_sucesso = isset($_SESSION["cadastro_sucesso"]) ? $_SESSION["cadastro_sucesso"] : false;

// Limpa as mensagens de sessão após exibir
unset($_SESSION["mensagem"]);
unset($_SESSION["cadastro_sucesso"]);

$authorized_message = '';

// Obter a lista de locais de armazenamento existentes para o select
$locais_existentes = getLocaisArmazenamento();
$opcoes_locais = '';
if (is_array($locais_existentes)) {
    foreach ($locais_existentes as $local) {
        $opcoes_locais .= '<option value="' . $local['desc_local'] . '">' . $local['desc_local'] . '</option>';
    }
}

$content = '
<div class="sidebar">
    <h2> Menu Armazenamento</h2>
    <ul>
        <li><a href="dashboard.php">Inicio</a></li>
        <li><a href="cadastro_armazenamento.php">Cadastro de Locais De Armazenamento</a></li>
        <li><a href="listar_armazenamento.php">Consultar Locais de Armazenamento</a></li>
        <li><a href="dashboard.php">Voltar</a></li>
    </ul>
</div>
<div class="container">
    ' . $mensagem . '
    <h2>Cadastro de Locais de Armazenamento</h2>
    
    ' . $authorized_message . '
    <form action="cadastro_armazenamento.php" method="POST">
        <div class="form-group">
            <label for="desc_local">Local de Armazenamento</label>
            <select name="desc_local" id="desc_local">
                ' . $opcoes_locais . '
            </select>
            <button type="button" onclick="openPopup()">Adicionar Novo Local</button>
        </div>
        <button type="submit">' . ($cadastro_sucesso ? 'Novo Cadastro' : 'Cadastrar') . '</button>
    </form>
</div>

<!-- Pop-up para adicionar novo local -->
<div id="popup" class="popup">
    <div class="popup-content">
        <span class="close" onclick="closePopup()">&times;</span>
        <h2>Adicionar Novo Local de Armazenamento</h2>
        <form id="popup-form">
            <label for="novo_local">Novo Local de Armazenamento:</label>
            <input type="text" id="novo_local" name="novo_local" required>
            <button type="button" onclick="adicionarLocal()">Adicionar</button>
        </form>
    </div>
</div>

<script>
function openPopup() {
    document.getElementById("popup").style.display = "block";
}

function closePopup() {
    document.getElementById("popup").style.display = "none";
}

function adicionarLocal() {
    var novoLocal = document.getElementById("novo_local").value;
    if (novoLocal) {
        var select = document.getElementById("desc_local");
        var option = document.createElement("option");
        option.text = novoLocal;
        option.value = novoLocal;
        select.add(option);
        closePopup();
    }
}
</script>

<style>
.popup {
    display: none;
    position: fixed;
    z-index: 1001;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0,0,0,0.4);
}

.popup-content {
    background-color: white;
    margin: 15% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 30%;
    border-radius: 10px;
    text-align: center;
}

.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
}

.success-message {
    background-color: #d4edda;
    border-color: #c3e6cb;
    color: #155724;
    padding: 10px;
    border-radius: 5px;
    margin-bottom: 10px;
}
</style>
';

include('template.php');
?>
