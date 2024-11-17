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

// Obter o valor de aut do usuário logado
$aut_usuario = $_SESSION["aut_usuario"] ?? 0;  
$authorized_message = $aut_usuario == 5 ? '<div class="authorized-message">Autorizado para cadastro de usuários</div>' : '';

$mensagem = isset($_SESSION["mensagem"]) && $_SESSION["mensagem"] !== "Login bem-sucedido!" 
            ? '<div class="success-message">' . $_SESSION["mensagem"] . '</div>' 
            : '';

$cadastro_sucesso = isset($_SESSION["cadastro_sucesso"]) && $_SESSION["cadastro_sucesso"] === true;

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
            
        <div class="container">
            <h2>Cadastro de Usuário</h2>
            ' . $mensagem . '
            ' . $authorized_message . '
            <form action="cadastro_usuario.php" method="POST">';

if (!$cadastro_sucesso) {
    $content .= '
                <div class="form-group">
                    <input type="text" name="usuario" placeholder="Usuário" required>
                </div>
                <div class="form-group">
                    <input type="password" name="senha" placeholder="Senha" required>
                </div>
                <div class="form-group">
                    <label for="aut">Nível de Acesso:</label>
                    <select name="aut" id="aut">
                        <option value="1"> Básico</option>
                        <option value="5"> Administrador</option>
                    </select>
                </div>';
}

$content .= '
                <button type="submit">' . ($cadastro_sucesso ? 'Novo Cadastro' : 'Cadastrar') . '</button>
            </form>
        </div>
    </body>
';
include('template.php');
?>
