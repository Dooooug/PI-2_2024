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

// Definir variáveis com valores padrão ou valores da requisição POST
$mensagem = isset($_SESSION["mensagem"]) ? $_SESSION["mensagem"] : '';
$cod_usuario = isset($_POST["cod"]) ? $_POST["cod"] : '';
$usuario_nome = isset($_POST["usuario"]) ? $_POST["usuario"] : '';
$usuario_aut = isset($_POST["aut"]) ? $_POST["aut"] : '';

$content = '
    <body>
        <div class="container">
            <h2>Atualização de Usuário</h2>
            ' . htmlspecialchars($mensagem) . '
            <form action="update_usuario.php" method="POST">
                <div class="form-group">
                    <label for="cod">Código do Usuário</label>
                    <input type="number" id="cod" name="cod" value="' . htmlspecialchars($cod_usuario) . '" readonly>
                </div>
                <div class="form-group">
                    <label for="usuario">Usuário</label>
                    <input type="text" id="usuario" name="usuario" value="' . htmlspecialchars($usuario_nome) . '" placeholder="Nome do Usuário" required>
                </div>
                <div class="form-group">
                    <label for="senha">Senha</label>
                    <input type="password" id="senha" name="senha" placeholder="Senha (deixe em branco para não alterar)">
                </div>
                <div class="form-group">
                    <label for="aut">Nível de Acesso:</label>
                    <select name="aut" id="aut">
                        <option value="1"' . ($usuario_aut == 1 ? ' selected' : '') . '> Básico</option>
                        <option value="5"' . ($usuario_aut == 5 ? ' selected' : '') . '> Administrador</option>
                    </select>
                </div>
                <button type="submit">Atualizar</button>
                <button type="button" onclick="window.location.href=\'consulta_usuarios.php\'">Voltar</button>
            </form>
        </div>
    </body>
';
include('template.php');
?>