<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QuimiDocs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <style>
        body {
            background-color: #F2F2F2;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            width: 100%;
            max-width: 400px;
        }
    </style>
</head>
<body class="text-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-4">
                <h1 class="text-center">QuimiDocs</h1>
                <div class="card">
                    <div class="card-body">
                        <h3> Acesso Restrito </h3>
                        <?php
                            session_start();
                            if (isset($_SESSION["mensagem"])) {
                                echo '<div class="alert alert-danger">' . $_SESSION["mensagem"] . '</div>';
                                unset($_SESSION["mensagem"]);
                            }
                        ?>
                    <div>
                <div class="card">
                    <div class="card-body">
                        <form action="login.php" method="POST">
                            <div class="mb-3">
                                <label>Usuario</label>
                                <input type="text" name="usuario" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label>Departamento</label>                                
                                <select name="departamento" id= "departameto" class="form-control">
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
                            </div>
                            <div class="mb-3">
                                <label>Senha</label>
                                <input type="password" name="senha" class="form-control">
                            </div>
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary">Entrar</button>
                                <a href="cadastro.php" class="btn btn-dark">Cadastro</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
