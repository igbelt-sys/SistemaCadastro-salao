<?php
declare(strict_types=1);

require_once __DIR__ . '/_funcoes.php';

$pdo = conectar();
$pesquisa = trim((string) ($_GET['pesquisa'] ?? ''));
$mensagem = trim((string) ($_GET['msg'] ?? ''));

if ($pesquisa !== '') {
    $stmt = $pdo->prepare(
        'SELECT id, nome, descricao, valor_base
         FROM servicos
         WHERE nome LIKE :pesquisa
         ORDER BY nome ASC'
    );
    $stmt->bindValue(':pesquisa', '%' . $pesquisa . '%', PDO::PARAM_STR);
} else {
    $stmt = $pdo->prepare(
        'SELECT id, nome, descricao, valor_base
         FROM servicos
         ORDER BY nome ASC'
    );
}

$stmt->execute();
$servicos = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Servicos</title>
</head>
<body>
    <h1>Servicos</h1>

    <p>
        <a href="../index.php">Inicio</a> |
        <a href="adicionar-servico.php">Cadastrar servico</a>
    </p>

    <?php if ($mensagem !== ''): ?>
        <p><strong><?= escapar($mensagem) ?></strong></p>
    <?php endif; ?>

    <form method="get">
        <label for="pesquisa">Pesquisar por nome:</label>
        <input type="text" name="pesquisa" id="pesquisa" value="<?= escapar($pesquisa) ?>">
        <button type="submit">Pesquisar</button>
        <a href="index.php">Limpar</a>
    </form>

    <h2>Lista de servicos</h2>

    <?php if (empty($servicos)): ?>
        <p>Nenhum servico encontrado.</p>
    <?php else: ?>
        <table border="1" cellpadding="8" cellspacing="0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Valor base</th>
                    <th>Acoes</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($servicos as $servico): ?>
                    <tr>
                        <td><?= (int) $servico['id'] ?></td>
                        <td><?= escapar((string) $servico['nome']) ?></td>
                        <td>R$ <?= number_format((float) $servico['valor_base'], 2, ',', '.') ?></td>
                        <td>
                            <a href="visualizar-servico.php?id=<?= (int) $servico['id'] ?>">Visualizar</a> |
                            <a href="editar-servico.php?id=<?= (int) $servico['id'] ?>">Editar</a> |
                            <a href="excluir-servico.php?id=<?= (int) $servico['id'] ?>">Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>
