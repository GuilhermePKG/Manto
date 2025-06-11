<?php
session_start();

$host = 'localhost';
$db   = 'nome_do_banco';
$user = 'usuario';
$pass = 'senha';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];
try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    exit('Erro ao conectar ao banco: ' . $e->getMessage());
}

if (!isset($_SESSION['carrinho_id'])) {
    exit(json_encode(['error' => 'Carrinho não encontrado.']));
}
$carrinho_id = $_SESSION['carrinho_id'];

// Recebe dados do front, se precisar (ex: endereço, pagamento etc)
// Por simplicidade, só usaremos o carrinho

// Buscar os itens do carrinho
$stmt = $pdo->prepare("
    SELECT ci.produto_id, ci.tamanho, ci.quantidade, p.preco
    FROM carrinho_itens ci
    JOIN produtos p ON ci.produto_id = p.id
    WHERE ci.usuario_id = ?
");
$stmt->execute([$carrinho_id]);
$itens = $stmt->fetchAll();

if (!$itens) {
    exit(json_encode(['error' => 'Carrinho vazio.']));
}

// Calcular total
$total = 0;
foreach ($itens as $item) {
    $total += $item['preco'] * $item['quantidade'];
}

// Começar transação para inserir pedido e itens
try {
    $pdo->beginTransaction();

    // Inserir pedido
    $stmt = $pdo->prepare("INSERT INTO pedidos (usuario_id, total) VALUES (?, ?)");
    $stmt->execute([$carrinho_id, $total]);
    $pedido_id = $pdo->lastInsertId();

    // Inserir itens do pedido
    $stmt = $pdo->prepare("INSERT INTO pedido_itens (pedido_id, produto_id, tamanho, quantidade, preco_unitario) VALUES (?, ?, ?, ?, ?)");
    foreach ($itens as $item) {
        $stmt->execute([
            $pedido_id,
            $item['produto_id'],
            $item['tamanho'],
            $item['quantidade'],
            $item['preco']
        ]);
    }

    // Limpar carrinho (deletar itens)
    $stmt = $pdo->prepare("DELETE FROM carrinho_itens WHERE usuario_id = ?");
    $stmt->execute([$carrinho_id]);

    $pdo->commit();

    echo json_encode(['success' => true, 'pedido_id' => $pedido_id]);
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['error' => 'Erro ao finalizar pedido: ' . $e->getMessage()]);
}
