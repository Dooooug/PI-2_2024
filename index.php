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
            background: url('https://i.postimg.cc/Qx6fY7Kc/fundo.jpg') no-repeat center center fixed; 
            background-size: cover;
            background-position: center;

        }

        .login-container {
            width: 420px;
            background-color: transparent;
            border: 2px solid rgba (255,255,255, .2);
            max-width: 800px;
            border-radius: 10px;
            color: #fff;
            padding: 30px 40px;
            box-shadow: 0 0 10px rgba (0,0,0 .2);
        }

        .card {
            background-color: rgba (255,255,255, 0.2);
            border: none;
        }

        .card-body{
            background-color: rgba(255,255,255,0.2);
        }

        .container h3{
            font-family: "Poppins", sans-serif;
            font-size: 70px;
            text-align: center;
        }
    </style>
</head>
<body class="text-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-4">
           
                <div class="card">
                    <div class="card-body">
                        <h3>QuimiDocs</h3>
                        <?php
                        session_start();
                        if (isset($_SESSION["mensagem"])) {
                            echo '<div class="alert alert-danger">' . $_SESSION["mensagem"] . '</div>';
                            unset($_SESSION["mensagem"]);
                        }
                        ?>
                        <form action="login.php" method="POST">
                            <div class="mb-3">
                                <label>Usuário</label>
                                <input type="text" name="usuario" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>Senha</label>
                                <input type="password" name="senha" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary">Entrar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

