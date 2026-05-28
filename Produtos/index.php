<?php
declare(strict_types=1);

require_once __DIR__ . '/_funcoes.php';

$pdo = conectar();
$pesquisa = trim((string) ($_GET['pesquisa'] ?? ''));
$mensagem = trim((string) ($_GET['msg'] ?? ''));

if ($pesquisa !== '') {
    $stmt = $pdo->prepare(
        'SELECT id, nome, descricao, marca, quantidade
         FROM produtos
         WHERE nome LIKE :pesquisa OR marca LIKE :pesquisa
         ORDER BY nome ASC'
    );
    $stmt->bindValue(':pesquisa', '%' . $pesquisa . '%', PDO::PARAM_STR);
} else {
    $stmt = $pdo->prepare(
        'SELECT id, nome, descricao, marca, quantidade
         FROM produtos
         ORDER BY nome ASC'
    );
}

$stmt->execute();
$produtos = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos</title>
</head>
<body>
    <h1>Produtos</h1>

    <p>
        <a href="../index.php">Inicio</a> |
        <a href="adicionar-produto.php">Cadastrar produto</a>
    </p>

    <?php if ($mensagem !== ''): ?>
        <p><strong><?= escapar($mensagem) ?></strong></p>
    <?php endif; ?>

    <form method="get">
        <label for="pesquisa">Pesquisar:</label>
        <input type="text" name="pesquisa" id="pesquisa" value="<?= escapar($pesquisa) ?>">
        <button type="submit">Pesquisar</button>
        <a href="index.php">Limpar</a>
    </form>

    <h2>Lista de produtos</h2>

    <?php if (empty($produtos)): ?>
        <p>Nenhum produto encontrado.</p>
    <?php else: ?>
        <table border="1" cellpadding="8" cellspacing="0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Marca</th>
                    <th>Quantidade</th>
                    <th>Acoes</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($produtos as $produto): ?>
                    <tr>
                        <td><?= (int) $produto['id'] ?></td>
                        <td><?= escapar((string) $produto['nome']) ?></td>
                        <td><?= escapar((string) ($produto['marca'] ?? '')) ?></td>
                        <td><?= (int) ($produto['quantidade'] ?? 0) ?></td>
                        <td>
                            <a href="visualizar-produto.php?id=<?= (int) $produto['id'] ?>">Visualizar</a> |
                            <a href="editar-produto.php?id=<?= (int) $produto['id'] ?>">Editar</a> |
                            <a href="excluir-produto.php?id=<?= (int) $produto['id'] ?>">Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>
