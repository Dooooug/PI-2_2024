<?php

session_start();
if (empty($_SESSION)) {
    header("Location: index.php");
    exit();
}

$mensagem = '';
if (isset($_SESSION["mensagem"])) {
    $mensagem = '<div class="message">' . $_SESSION["mensagem"] . '</div>';
    unset($_SESSION["mensagem"]);
}

// Função para atualizar o local de armazenamento no banco de dados
function updateArmazenamento($cod, $desc) {
    $url = 'http://191.252.110.154:3000/api/update_armazenamento';

    $data = array(
        'cod' => $cod,
        'desc_local' => $desc
    );

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json'
    ));
    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}

// Recuperar o valor de $cod e $desc_local da URL
$cod = isset($_GET['cod']) ? $_GET['cod'] : '';
$desc_local = isset($_GET['desc_local']) ? $_GET['desc_local'] : '';

// Verificar se a requisição foi um POST e executar a atualização
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST["cod"]) && !empty($_POST["desc_local"])) {
    $cod = $_POST["cod"];
    $desc_local = $_POST["desc_local"];
    $result = updateArmazenamento($cod, $desc_local);
    if ($result && isset($result['status']) && $result['status']) {
        $_SESSION["mensagem"] = 'Local de armazenamento atualizado com sucesso.';
    } else {
        $_SESSION["mensagem"] = 'Erro ao atualizar o local de armazenamento: ' . $result['message'];
    }
    header("Location: listar_armazenamento.php"); // Redirecionar para a listagem após a atualização
    exit();
}

$content = '
    <body>
        <div class="container">
            <h2>Atualização de Local de Armazenamento</h2>
            ' . htmlspecialchars($mensagem) . '
            <form action="update_armazenamento.php" method="POST">
                <div class="form-group">
                    <label for="cod">Código do Local</label>
                    <input type="number" id="cod" name="cod" value="' . htmlspecialchars($cod, ENT_QUOTES, 'UTF-8') . '" readonly>
                </div>
                <div class="form-group">
                    <label for="desc_local">Local de Armazenamento</label>
                    <input type="text" id="desc_local" name="desc_local" value="' . htmlspecialchars($desc_local, ENT_QUOTES, 'UTF-8') . '" placeholder="Local de Armazenamento" required>
                </div>
                <button type="submit">Atualizar</button>
                <button type="button" onclick="window.location.href=\'listar_armazenamento.php\'">Voltar</button>
            </form>
        </div>
    </body>
';

include('template.php');
?>
