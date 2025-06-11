<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Produtos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            max-width: 600px;
            margin: auto;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label, input, textarea {
            margin-bottom: 10px;
        }
        input[type="submit"] {
            width: 150px;
            padding: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
        }
        .mensagem {
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <h1>Cadastro de Produtos</h1>
    <form method="POST" action="">
        <label>Nome do Produto:</label>
        <input type="text" name="nome" required>

        <label>Descrição:</label>
        <textarea name="descricao" rows="4"></textarea>

        <label>Preço (R$):</label>
        <input type="number" name="preco" step="0.01" required>

        <label>Quantidade:</label>
        <input type="number" name="quantidade" required>

        <input type="submit" value="Cadastrar Produto">
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nome = $_POST["nome"];
        $descricao = $_POST["descricao"];
        $preco = $_POST["preco"];
        $quantidade = $_POST["quantidade"];

        $conn = new mysqli("localhost", "root", "", "estoque");

        if ($conn->connect_error) {
            die("<p class='mensagem'>Erro na conexão: " . $conn->connect_error . "</p>");
        }

        $sql = "INSERT INTO produtos (nome, descricao, preco, quantidade)
                VALUES ('$nome', '$descricao', '$preco', '$quantidade')";

        if ($conn->query($sql) === TRUE) {
            echo "<p class='mensagem'>Produto cadastrado com sucesso!</p>";
        } else {
            echo "<p class='mensagem'>Erro: " . $conn->error . "</p>";
        }

        $conn->close();
    }
    ?>
</body>
</html>
