<?php

session_start();
if(empty($_SESSION)){
    print "<script> location.href='index.php'; </script>";
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QuimiDocs</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .navbar {
            background-color: #007bff;
            color: white;
            padding: 10px;
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
            height: calc(100vh - 50px); /* Ajuste a altura da sidebar */
            position: absolute;
            top: 38px; /* Posiciona abaixo da navbar */
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
            margin-top: 70px; /* Ajuste conforme necessário */
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
        <?php
           print "Olá, " . $_SESSION["nome"];
           print "<a href='logout.php' class='btn btn-danger'>Sair</a>";
        ?>
    </div>
</body>
    <div class="sidebar">
        <ul>
            <li><a href="#">Inicio</a></li>
            <li><a href="#">Download de FISPQ</a></li>
            <li><a href="#">Adicionar Item</a></li>
            <li><a href="#">Relatório de Produtos Quimicos</a></li>
            <li><a href="#">Categorias</a></li>
        </ul>
    </div>
    <div class="main-content">
        <div class="card">
            <h3>Ultimo Produto Quimico Cadastrado</h3>
            <p>Cloro (Empresa A)</p>
        </div>
        <div class="card">
            <h3>Total de Produtos Cadastrados</h3>
            <p>36</p>
        </div>
        <div class="card">
            <h3>Categorias</h3>
            <p>5</p>
        </div>
        <div class="card">
            <h3>Departamentos</h3>
            <p>Informação</p>
        </div>
    </div>
</body>
</html>