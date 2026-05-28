<?php
declare(strict_types=1);

require_once __DIR__ . '/_funcoes.php';

$pdo = conectar();
$id = pegarId($_GET['id'] ?? null);
$mensagem = trim((string) ($_GET['msg'] ?? ''));

if ($id <= 0) {
    irPara('index.php?msg=' . urlencode('Cliente invalido.'));
}

$cliente = buscarCliente($pdo, $id);
if ($cliente === null) {
    irPara('index.php?msg=' . urlencode('Cliente nao encontrado.'));
}

$stmt = $pdo->prepare(
    'SELECT h.id, h.data_historico, h.observacao, s.nome AS servico_nome
     FROM historico_clientes h
     LEFT JOIN servicos s ON s.id = h.servico_id
     WHERE h.cliente_id = :cliente_id
     ORDER BY h.data_historico DESC, h.id DESC'
);
$stmt->bindValue(':cliente_id', $id, PDO::PARAM_INT);
$stmt->execute();
$historicos = $stmt->fetchAll();

$stmtServicos = $pdo->prepare(
    'SELECT id, nome, valor_base
     FROM servicos
     ORDER BY nome ASC'
);
$stmtServicos->execute();
$servicos = $stmtServicos->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizar cliente</title>
</head>
<body>
    <h1>Visualizar cliente</h1>

    <p>
        <a href="index.php">Voltar para clientes</a> |
        <a href="editar-cliente.php?id=<?= $id ?>">Editar</a> |
        <a href="excluir-cliente.php?id=<?= $id ?>">Excluir</a>
    </p>

    <?php if ($mensagem !== ''): ?>
        <p><strong><?= escapar($mensagem) ?></strong></p>
    <?php endif; ?>

    <h2>Dados da cliente</h2>
    <p><strong>ID:</strong> <?= (int) $cliente['id'] ?></p>
    <p><strong>Nome:</strong> <?= escapar((string) $cliente['nome']) ?></p>
    <p><strong>Telefone:</strong> <?= escapar((string) ($cliente['telefone'] ?? '')) ?></p>
    <p><strong>Observacoes:</strong><br><?= nl2br(escapar((string) ($cliente['observacoes'] ?? ''))) ?></p>
    <p><strong>Criado em:</strong> <?= escapar((string) $cliente['criado_em']) ?></p>

    <h2>Adicionar historico</h2>
    <form method="post" action="adicionar-historico.php">
        <input type="hidden" name="cliente_id" value="<?= $id ?>">

        <p>
            <label for="data_historico">Data:</label><br>
            <input type="date" name="data_historico" id="data_historico" value="<?= date('Y-m-d') ?>" required>
        </p>

        <p>
            <label for="servico_id">Servico:</label><br>
            <select name="servico_id" id="servico_id">
                <option value="">Sem servico vinculado</option>
                <?php foreach ($servicos as $servico): ?>
                    <option value="<?= (int) $servico['id'] ?>">
                        <?= escapar((string) $servico['nome']) ?>
                        - R$ <?= number_format((float) $servico['valor_base'], 2, ',', '.') ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php if (empty($servicos)): ?>
                <br><small>Nenhum servico cadastrado ainda.</small>
            <?php endif; ?>
        </p>

        <p>
            <label for="observacao">Observacao:</label><br>
            <textarea name="observacao" id="observacao" rows="5" cols="50" required></textarea>
        </p>

        <p>
            <button type="submit">Adicionar historico</button>
        </p>
    </form>

    <h2>Historico</h2>

    <?php if (empty($historicos)): ?>
        <p>Nenhum historico cadastrado para esta cliente.</p>
    <?php else: ?>
        <table border="1" cellpadding="8" cellspacing="0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Data</th>
                    <th>Servico</th>
                    <th>Observacao</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($historicos as $historico): ?>
                    <tr>
                        <td><?= (int) $historico['id'] ?></td>
                        <td><?= escapar((string) $historico['data_historico']) ?></td>
                        <td><?= escapar((string) ($historico['servico_nome'] ?? 'Sem servico vinculado')) ?></td>
                        <td><?= nl2br(escapar((string) $historico['observacao'])) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>
