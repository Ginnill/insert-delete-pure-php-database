<?php

require_once 'database.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

function remover($id)
{
    $pdo = Connection::getConnection();
    $stmt = $pdo->prepare("UPDATE frutas SET removed_at = NOW() WHERE id = ? AND removed_at IS NULL");
    $stmt->execute([$id]);
    if ($stmt->rowCount() > 0) {
        echo json_encode(['ok' => true]);
    } else {
        http_response_code(404);
        echo json_encode(['ok' => false, 'error' => 'Fruta não encontrada ou já removida']);
    }
    exit;
}

function adicionar($nome, $preco)
{
    $pdo = Connection::getConnection();
    $stmt = $pdo->prepare("INSERT INTO frutas (nome, preco, created_at) VALUES (?, ?, NOW())");
    $stmt->execute([$nome, $preco]);
    if ($stmt->rowCount() > 0) {
        echo json_encode(['ok' => true]);
    } else {
        http_response_code(400);
        echo json_encode(['ok' => false, 'error' => 'Erro ao adicionar fruta']);
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id'])) {
        remover($_POST['id']);
    } elseif (isset($_POST['nome'], $_POST['preco'])) {
        adicionar($_POST['nome'], $_POST['preco']);
    }
}

try {
    $pdo = Connection::getConnection();
    $stmt = $pdo->query("SELECT id, nome, preco, created_at FROM frutas WHERE removed_at IS NULL ORDER BY id DESC");
    $rows = $stmt->fetchAll();

    echo json_encode([
        'ok' => true,
        'frutas' => $rows
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
}
