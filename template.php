<?php

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QuimiDocs</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 20px;
            border: 1px solid #add;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .navbar {
            position:fixed;
            top:0;
            left:0;
            width: 100%;
            background-color: #007bff;
            z-index: 1000;
            color: white;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar div {
            margin: 0 10px;
        }
        .sidebar {
           
            background-color: #343a40;
            color: white;
            padding: 10px;
            width: 200px;
            height: calc(100vh - 50px);
            position:fixed;
            top: 50px;
            left: 0;
        }
        .sidebar ul {
            list-style-type: none;
            padding: 0;
        }
        .sidebar ul li {
            margin: 20px 0;
        }
        .sidebar ul li a {
            color: white;
            text-decoration: none;
        }
        .main-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            padding: 20px;
            margin-left: 220px;
            margin-top: 70px;
        }
        .container {
            background-color: white;
            color: black;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            margin-bottom: 20px;
            width: 100%;
            max-width: 500px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .success-message {
            background-color: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        .button-group {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
        .button-group input, .button-group a {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin: 5px;
        }
        .button-group input:hover, .button-group a:hover {
            background-color: #0056b3;
        }
        .card {
            background-color: #007bff;
            color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a class="navbar-brand">QuimiDocs</a>
        
    </div>
    <div class="sidebar">
        <ul>
            <li><a href="dashboard.php">Inicio</a></li>
            <li><a href="cadastro.php">Cadastro de Usuarios</a></li>
            <li><a href="#">Cadastro de Produtos</a></li>
            <li><a href="#">Cadastro de Armazenamentos</a></li>
            <li><a href="#">Relatório de Produtos Quimicos</a></li>
            <li><a href='logout.php' >Sair</a></li>
        </ul>
    </div>
    <div class="main-content">
        <!-- Conteúdo dinâmico -->
        <?php echo $content; ?>
    </div>
</body>
</html>
