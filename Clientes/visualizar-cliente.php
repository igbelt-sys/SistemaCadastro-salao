<?php

require_once __DIR__ . '/_funcoes.php';

$pdo = conectar();
// a mensagem vem pela url depois de cadastrar editar ou adicionar historico
$id = pegarId($_GET['id'] ?? null);
$mensagem = trim($_GET['msg'] ?? '');

if ($id <= 0) {
    irPara('index.php?msg=' . urlencode('Cliente inválido.'));
}

// sem cliente valido nao tem nada para mostrar
$cliente = buscarCliente($pdo, $id);
if (!$cliente) {
    irPara('index.php?msg=' . urlencode('Cliente não encontrado.'));
}

// carrega o historico dessa cliente para montar a tabela
$stmt = $pdo->prepare(
    'SELECT h.id, h.data_historico, h.observacao, s.nome AS servico_nome
     FROM historico_clientes h
     LEFT JOIN servicos s ON s.id = h.servico_id
     WHERE h.cliente_id = :cliente_id
     ORDER BY h.data_historico DESC, h.id DESC'
);
$stmt->execute([':cliente_id' => $id]);
$historicos = $stmt->fetchAll();

// busca os servicos para preencher o select do formulario
$servicos = $pdo->query(
    'SELECT id, nome, valor_base
     FROM servicos
     ORDER BY nome ASC'
)->fetchAll();

$pageTitle = 'Silvana | Visualizar cliente';
$basePath = '../';
$activeSection = 'clientes';
?>
<?php require __DIR__ . '/../includes/head.php'; ?>
<?php require __DIR__ . '/../includes/sidebar.php'; ?>
<section class="page-header">
    <div>
        <span class="page-eyebrow">Clientes</span>
        <h1 class="page-title">Visualizar cliente</h1>
        <p class="page-description">Veja os dados e o hist&oacute;rico.</p>
    </div>
    <div class="page-actions">
        <a class="btn btn--ghost" href="index.php">Voltar para clientes</a>
        <a class="btn btn--secondary" href="editar-cliente.php?id=<?= $id ?>">Editar</a>
        <a class="btn btn--danger" href="excluir-cliente.php?id=<?= $id ?>">Excluir</a>
    </div>
</section>

<?php if ($mensagem !== ''): ?>
    <div class="alert"><?= escapar($mensagem) ?></div>
<?php endif; ?>

<section class="panel panel--soft">
    <div class="section-header">
        <div>
            <h2 class="section-title">Dados da cliente</h2>
            <p class="section-copy">Dados salvos.</p>
        </div>
    </div>

    <div class="detail-grid">
        <article class="detail-item">
            <span class="detail-label">ID</span>
            <p class="detail-value"><?= (int) $cliente['id'] ?></p>
        </article>
        <article class="detail-item">
            <span class="detail-label">Criado em</span>
            <p class="detail-value"><?= escapar(formatarDataHora($cliente['criado_em'])) ?></p>
        </article>
        <article class="detail-item">
            <span class="detail-label">Nome</span>
            <p class="detail-value"><?= escapar($cliente['nome']) ?></p>
        </article>
        <article class="detail-item">
            <span class="detail-label">Telefone</span>
            <p class="detail-value"><?= escapar($cliente['telefone'] ?? '') ?></p>
        </article>
        <article class="detail-item detail-item--full">
            <span class="detail-label">Observa&ccedil;&otilde;es</span>
            <p class="detail-value"><?= nl2br(escapar($cliente['observacoes'] ?? '')) ?></p>
        </article>
    </div>
</section>

<section class="history-layout">
    <article class="panel panel--soft">
        <div class="section-header">
            <div>
                <h2 class="section-title">Adicionar hist&oacute;rico</h2>
                <p class="section-copy">Preencha e salve.</p>
            </div>
        </div>

        <form method="post" action="adicionar-historico.php" class="form-grid">
            <input type="hidden" name="cliente_id" value="<?= $id ?>">

            <div class="field field--full">
                <label for="data_historico">Data</label>
                <input type="date" name="data_historico" id="data_historico" value="<?= date('Y-m-d') ?>" required>
            </div>

            <div class="field field--full">
                <label for="servico_id">Servi&ccedil;o</label>
                <select name="servico_id" id="servico_id">
                    <option value="">Sem servi&ccedil;o vinculado</option>
                    <?php foreach ($servicos as $servico): ?>
                        <option value="<?= (int) $servico['id'] ?>">
                            <?= escapar($servico['nome']) ?>
                            - R$ <?= number_format((float) $servico['valor_base'], 2, ',', '.') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if (empty($servicos)): ?>
                    <p class="section-copy">Cadastre um servi&ccedil;o primeiro.</p>
                <?php endif; ?>
            </div>

            <div class="field field--full">
                <label for="observacao">Observa&ccedil;&atilde;o</label>
                <textarea name="observacao" id="observacao" rows="5" cols="50" required></textarea>
            </div>

            <div class="form-actions field--full">
                <button class="btn btn--primary" type="submit">Adicionar hist&oacute;rico</button>
            </div>
        </form>
    </article>

    <article class="panel">
        <div class="section-header">
            <div>
                <h2 class="section-title">Hist&oacute;rico</h2>
                <p class="section-copy">Hist&oacute;rico da cliente.</p>
            </div>
            <span class="count-badge"><?= count($historicos) ?></span>
        </div>

        <?php if (empty($historicos)): ?>
            <div class="empty-state">Nenhum hist&oacute;rico cadastrado para esta cliente.</div>
        <?php else: ?>
            <div class="table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Data</th>
                            <th>Servi&ccedil;o</th>
                            <th>Observa&ccedil;&atilde;o</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($historicos as $historico): ?>
                            <tr>
                                <td><?= (int) $historico['id'] ?></td>
                                <td><?= escapar($historico['data_historico']) ?></td>
                                <td><?= escapar($historico['servico_nome'] ?? 'Sem serviço vinculado') ?></td>
                                <td><?= nl2br(escapar($historico['observacao'])) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </article>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
