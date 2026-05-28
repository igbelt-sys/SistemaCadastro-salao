<?php
declare(strict_types=1);

require_once __DIR__ . '/_funcoes.php';

$pdo = conectar();
$id = pegarId($_GET['id'] ?? null);
$mensagem = trim((string) ($_GET['msg'] ?? ''));

if ($id <= 0) {
    irPara('index.php?msg=' . urlencode('Servico invalido.'));
}

$servico = buscarServico($pdo, $id);
if ($servico === null) {
    irPara('index.php?msg=' . urlencode('Servico nao encontrado.'));
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizar servico</title>
</head>
<body>
    <h1>Visualizar servico</h1>

    <p>
        <a href="index.php">Voltar para servicos</a> |
        <a href="editar-servico.php?id=<?= $id ?>">Editar</a> |
        <a href="excluir-servico.php?id=<?= $id ?>">Excluir</a>
    </p>

    <?php if ($mensagem !== ''): ?>
        <p><strong><?= escapar($mensagem) ?></strong></p>
    <?php endif; ?>

    <h2>Dados do servico</h2>
    <p><strong>ID:</strong> <?= (int) $servico['id'] ?></p>
    <p><strong>Nome:</strong> <?= escapar((string) $servico['nome']) ?></p>
    <p><strong>Descricao:</strong><br><?= nl2br(escapar((string) ($servico['descricao'] ?? ''))) ?></p>
    <p><strong>Valor base:</strong> R$ <?= number_format((float) $servico['valor_base'], 2, ',', '.') ?></p>
</body>
</html>
