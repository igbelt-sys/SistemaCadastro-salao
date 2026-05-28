<?php
declare(strict_types=1);

require_once __DIR__ . '/_funcoes.php';

$pdo = conectar();
$id = pegarId($_GET['id'] ?? null);
$mensagem = trim((string) ($_GET['msg'] ?? ''));

if ($id <= 0) {
    irPara('index.php?msg=' . urlencode('Produto invalido.'));
}

$produto = buscarProduto($pdo, $id);
if ($produto === null) {
    irPara('index.php?msg=' . urlencode('Produto nao encontrado.'));
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizar produto</title>
</head>
<body>
    <h1>Visualizar produto</h1>

    <p>
        <a href="index.php">Voltar para produtos</a> |
        <a href="editar-produto.php?id=<?= $id ?>">Editar</a> |
        <a href="excluir-produto.php?id=<?= $id ?>">Excluir</a>
    </p>

    <?php if ($mensagem !== ''): ?>
        <p><strong><?= escapar($mensagem) ?></strong></p>
    <?php endif; ?>

    <h2>Dados do produto</h2>
    <p><strong>ID:</strong> <?= (int) $produto['id'] ?></p>
    <p><strong>Nome:</strong> <?= escapar((string) $produto['nome']) ?></p>
    <p><strong>Descricao:</strong><br><?= nl2br(escapar((string) ($produto['descricao'] ?? ''))) ?></p>
    <p><strong>Marca:</strong> <?= escapar((string) ($produto['marca'] ?? '')) ?></p>
    <p><strong>Quantidade:</strong> <?= (int) ($produto['quantidade'] ?? 0) ?></p>
</body>
</html>
