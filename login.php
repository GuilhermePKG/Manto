<?php
header('Content-Type: application/json');

$conn = new mysqli("localhost", "root", "", "manto_db");

if ($conn->connect_error) {
    echo json_encode(["status" => "erro", "mensagem" => "Erro na conexão com o banco de dados."]);
    exit();
}

$email = $_POST['email'];
$senha = $_POST['senha'];

$stmt = $conn->prepare("SELECT senha FROM usuarios WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($senha_hash);
    $stmt->fetch();

    if (password_verify($senha, $senha_hash)) {
        echo json_encode(["status" => "ok", "mensagem" => "Login bem-sucedido."]);
    } else {
        echo json_encode(["status" => "erro", "mensagem" => "Senha incorreta."]);
    }
} else {
    echo json_encode(["status" => "erro", "mensagem" => "E-mail não encontrado."]);
}

$stmt->close();
$conn->close();
?>
