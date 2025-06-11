<?php
header('Content-Type: application/json');

$conn = new mysqli("localhost", "root", "", "manto_db");

if ($conn->connect_error) {
    echo json_encode(["status" => "erro", "mensagem" => "Erro na conexão com o banco de dados."]);
    exit();
}

$nome = $_POST['nome'];
$email = $_POST['email'];
$senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
$telefone = $_POST['telefone'];
$cep = $_POST['cep'];
$logradouro = $_POST['logradouro'];
$numero = $_POST['numero'];
$bairro = $_POST['bairro'];
$cidade = $_POST['cidade'];
$estado = $_POST['estado'];

$stmt = $conn->prepare("INSERT INTO usuarios (nome, email, senha, telefone, cep, logradouro, numero, bairro, cidade, estado) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssssssss", $nome, $email, $senha, $telefone, $cep, $logradouro, $numero, $bairro, $cidade, $estado);

if ($stmt->execute()) {
    echo json_encode(["status" => "ok", "mensagem" => "Cadastro realizado com sucesso."]);
} else {
    echo json_encode(["status" => "erro", "mensagem" => "Erro ao cadastrar. E-mail já pode estar em uso."]);
}

$stmt->close();
$conn->close();
?>
