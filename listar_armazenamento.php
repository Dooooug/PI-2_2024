<?php

session_start();
if (empty($_SESSION)) {
    header("Location: index.php");
    exit();
}

function getLocaisArmazenamentoCadastrados() {
    $url = "http://191.252.110.154:3000/api/consulta_armazenamento";
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    return json_decode($response, true);
}

$locais_armazenamento = getLocaisArmazenamentoCadastrados();

$mensagem = isset($_SESSION["mensagem"]) ? '<div class="message">' . $_SESSION["mensagem"] . '</div>' : '';

$content = '
    <body>
        <div class="sidebar">
            <h2>Menu Armazenamento</h2>
            <ul>
                <li><a href="dashboard.php">Inicio</a></li>
                <li><a href="cadastro_armazenamento.php">Cadastro de Locais de Armazenamento</a></li>
                <li><a href="listar_armazenamento.php">Consultar Locais de Armazenamento</a></li>
                <li><a href="dashboard.php">Voltar</a></li>
            </ul>
        </div>
        <div class="container">
            <h2>Locais de Armazenamento</h2>
            ' . $mensagem . '
            <table>
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Local de Armazenamento</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>';

if (is_array($locais_armazenamento) && count($locais_armazenamento) > 0) {
    foreach ($locais_armazenamento as $local) {
        $content .= '
        <tr>
            <td>' . htmlspecialchars($local['cod'], ENT_QUOTES, 'UTF-8') . '</td>
            <td>' . htmlspecialchars($local['desc_local'], ENT_QUOTES, 'UTF-8') . '</td>
            <td><a href="update_armazenamento.php?cod=' . htmlspecialchars($local['cod'], ENT_QUOTES, 'UTF-8') . '&desc_local=' . htmlspecialchars($local['desc_local'], ENT_QUOTES, 'UTF-8') . '" class="btn-edit">Editar</a></td>
        </tr>';
    }
} else {
    $content .= '<tr><td colspan="3">Nenhum local de armazenamento encontrado.</td></tr>';
}

$content .= '
                </tbody>
            </table>
        </div>
    </body>
';

include('template.php');
?>
