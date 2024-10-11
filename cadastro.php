<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuário</title>
    <style>
        /* Estilo geral do corpo */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        /* Estilo do container principal */
        .container {
            width: 300px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        /* Estilo do título */
        .container h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        /* Estilo dos campos de entrada */
        .container input[type="text"],
        .container input[type="password"],
        .container input[type="email"],
        .container select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: white;
            font-family: Arial, sans-serif; /* Certifique-se de que a fonte seja a mesma */
            font-size: 16px; /* Ajuste o tamanho da fonte conforme necessário */
        }
        /* Estilo do grupo de botões */
        .container .button-group {
            display: flex;
            justify-content: space-between;
        }
        /* Estilo dos botões */
        .container input[type="submit"],
        .container .back-button {
            width: 48%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            display: inline-block;
        }
        /* Estilo dos botões ao passar o mouse */
        .container input[type="submit"]:hover,
        .container .back-button:hover {
            background-color: #28a745;
        }
        /* Estilo das mensagens de sucesso e erro */
        .success-message {
            text-align: center;
            color: green;
            margin-top: 20px;
        }
        .error-message {
            text-align: center;
            color: red;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Cadastro de Usuário</h2>
        <?php
            session_start();
            if (isset($_SESSION["mensagem"])) {
                echo '<div class="success-message">' . $_SESSION["mensagem"] . '</div>';
                unset($_SESSION["mensagem"]);
            }
        ?>
        <form action="processar_cadastro.php" method="POST">
            <input type="text" name="nome" placeholder="Nome Completo" required>
            <input type="text" name="usuario" placeholder="Usuário" required>
            <label for="departamento">Departamento:</label>
            <select name="departamento" id= "departameto">
                <option value="">Selecione o Departamento</option>
                <option value="Almoxarifado">Almoxarifado</option>
                <option value="Apollo">Apollo</option>
                <option value="Casa de Tintas">Casa de Tintas</option>
                <option value="Central de Residuos">Central de Residuos</option>
                <option value="DPA">DPA</option>
                <option value="Jardinagem">Jardinagem</option>
                <option value="Limpeza">Limpeza</option>
                <option value="Login">Login</option>
                <option value="Manutenção de Empilhadeira">Manutenção de Empilhadeira</option>
                <option value="Restaurante 1">Restaurante 1</option>
                <option value="Resturante 2">Resturante 2</option>
                <option value="Rouparia">Rouparia</option>
                <option value="Sala de Lubrificação">Sala de Lubrificação</option>
            </select>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="senha" placeholder="Senha ( Minimo 6 Caracteres)" required>
            <div class="button-group">
                <input type="submit" value="Cadastrar">
                <a href="index.php" class="back-button">Voltar para Login</a>
            </div>
        </form>
    </div>
</body>
</html>
