<?php
declare(strict_types=1);

require_once __DIR__ . '/_funcoes.php';

$pdo = conectar();
$pesquisa = trim((string) ($_GET['pesquisa'] ?? ''));
$mensagem = trim((string) ($_GET['msg'] ?? ''));

if ($pesquisa !== '') {
    $stmt = $pdo->prepare(
        'SELECT id, nome, telefone, observacoes, criado_em
         FROM clientes
         WHERE nome LIKE :pesquisa
         ORDER BY nome ASC'
    );
    $stmt->bindValue(':pesquisa', '%' . $pesquisa . '%', PDO::PARAM_STR);
} else {
    $stmt = $pdo->prepare(
        'SELECT id, nome, telefone, observacoes, criado_em
         FROM clientes
         ORDER BY nome ASC'
    );
}

$stmt->execute();
$clientes = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes</title>
</head>
<body>
    <h1>Clientes</h1>

    <p>
        <a href="../index.php">Inicio</a> |
        <a href="adicionar-cliente.php">Cadastrar cliente</a>
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

    <h2>Lista de clientes</h2>

    <?php if (empty($clientes)): ?>
        <p>Nenhum cliente encontrado.</p>
    <?php else: ?>
        <table border="1" cellpadding="8" cellspacing="0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Telefone</th>
                    <th>Observacoes</th>
                    <th>Criado em</th>
                    <th>Acoes</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($clientes as $cliente): ?>
                    <tr>
                        <td><?= (int) $cliente['id'] ?></td>
                        <td><?= escapar((string) $cliente['nome']) ?></td>
                        <td><?= escapar((string) ($cliente['telefone'] ?? '')) ?></td>
                        <td><?= nl2br(escapar((string) ($cliente['observacoes'] ?? ''))) ?></td>
                        <td><?= escapar((string) $cliente['criado_em']) ?></td>
                        <td>
                            <a href="visualizar-cliente.php?id=<?= (int) $cliente['id'] ?>">Visualizar</a> |
                            <a href="editar-cliente.php?id=<?= (int) $cliente['id'] ?>">Editar</a> |
                            <a href="excluir-cliente.php?id=<?= (int) $cliente['id'] ?>">Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>
