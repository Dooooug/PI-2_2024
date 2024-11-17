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

// Definir a mensagem de sessão se estiver definida
$mensagem = '';
if (isset($_SESSION["mensagem"])) {
    $mensagem = '<div class="message">' . $_SESSION["mensagem"] . '</div>';
    unset($_SESSION["mensagem"]);
}

// Função para consultar todos os usuários
function consulta_usuarios($aut_usuario) {
    $url = "http://191.252.110.154:3000/api/consulta_usuarios?aut_usuario=$aut_usuario";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if (curl_errno($ch)) {
        $response = json_encode(["status" => false, "message" => "Erro na requisição", "error" => curl_error($ch)]);
    }
    curl_close($ch);

    $decoded_response = json_decode($response, true);
    if (!is_array($decoded_response) || json_last_error() !== JSON_ERROR_NONE) {
        return ["response" => [], "httpcode" => $httpcode, "error" => true, "message" => "Erro ao decodificar a resposta"];
    }
    return ["response" => $decoded_response, "httpcode" => $httpcode, "error" => false];
}

// Obter o valor de autorização do usuário logado
$aut_usuario = $_SESSION['aut'] ?? 0;
if ($aut_usuario != 5) {
    $usuarios = [];
} else {
    $resultado = consulta_usuarios($aut_usuario);
    if ($resultado['error'] ?? false) {
        $usuarios = [];
        $mensagem = '<div class="message">Erro ao buscar usuários: ' . $resultado['message'] . '</div>';
    } else if (empty($resultado['response'])) {
        $usuarios = [];
        $mensagem = '<div class="message">Nenhum usuário encontrado.</div>';
    } else {
        $usuarios = $resultado['response'];
    }
}

$content = '
<body>
    <div class="sidebar">
        <h2> Menu Usuários</h2>
        <ul>
            <li><a href="dashboard.php">Inicio</a></li>
            <li><a href="cadastro.php">Cadastro de Usuarios</a></li>
            <li><a href="consulta_usuarios.php">Consultar Usuarios</a></li>
            <li><a href="dashboard.php">Voltar</a></li>
        </ul>
    </div>
    <h2>Lista de Usuários</h2>
    ' . $mensagem . '
    <table>
        <thead>
            <tr>
                <th>Código</th>
                <th>Usuário</th>
                <th>Senha</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
';

// Inserir dados dos usuários
if (is_array($usuarios) && !empty($usuarios)) {
    foreach ($usuarios as $usuario) {
        if (is_array($usuario)) {
            $content .= '
            <tr>
                <td>' . htmlspecialchars($usuario['cod'], ENT_QUOTES, 'UTF-8') . '</td>
                <td>' . htmlspecialchars($usuario['usuario'], ENT_QUOTES, 'UTF-8') . '</td>
                <td>' . htmlspecialchars($usuario['senha'], ENT_QUOTES, 'UTF-8') . '</td>
                <td>
                    <form action="update.php" method="POST" style="display:inline;">
                        <input type="hidden" name="cod" value="' . htmlspecialchars($usuario['cod'], ENT_QUOTES, 'UTF-8') . '">
                        <input type="hidden" name="usuario" value="' . htmlspecialchars($usuario['usuario'], ENT_QUOTES, 'UTF-8') . '">
                        <input type="hidden" name="aut" value="' . htmlspecialchars($usuario['aut'], ENT_QUOTES, 'UTF-8') . '">
                        <button type="submit" class="btn btn-edit">Editar</button>
                    </form>
                    <form action="excluir_usuario.php" method="POST" style="display:inline;">
                        <input type="hidden" name="cod" value="' . htmlspecialchars($usuario['cod'], ENT_QUOTES, 'UTF-8') . '">
                        <button type="submit" class="btn btn-delete">Excluir</button>
                    </form>
                </td>
            </tr>
            ';
        }
    }
} else {
    $content .= '<tr><td colspan="4">Erro ao buscar usuários</td></tr>';
}

$content .= '
        </tbody>
    </table>
</body>
';

include('template.php');
?>
