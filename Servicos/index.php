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

$pageTitle = 'Silvana | Servicos';
$basePath = '../';
$activeSection = 'servicos';
?>
<?php require __DIR__ . '/../includes/head.php'; ?>
<?php require __DIR__ . '/../includes/sidebar.php'; ?>
<section class="page-header">
    <div>
        <span class="page-eyebrow">Gestao de servicos</span>
        <h1 class="page-title">Servicos</h1>
        <p class="page-description">Centralize os servi&ccedil;os oferecidos com descri&ccedil;&otilde;es claras e valores acess&iacute;veis.</p>
    </div>
    <div class="page-actions">
        <a class="btn btn--primary" href="adicionar-servico.php">Cadastrar servico</a>
    </div>
</section>

<?php if ($mensagem !== ''): ?>
    <div class="alert"><?= escapar($mensagem) ?></div>
<?php endif; ?>

<section class="panel panel--soft">
    <form method="get" class="toolbar-search">
        <div class="field field--grow">
            <label for="pesquisa">Pesquisar por nome</label>
            <input type="text" name="pesquisa" id="pesquisa" value="<?= escapar($pesquisa) ?>">
        </div>
        <div class="page-actions">
            <button class="btn btn--primary" type="submit">Pesquisar</button>
            <a class="btn btn--ghost" href="index.php">Limpar</a>
        </div>
    </form>
</section>

<section class="panel">
    <div class="section-header">
        <div>
            <h2 class="section-title">Lista de servicos</h2>
            <p class="section-copy">Acesse os servi&ccedil;os cadastrados e siga para visualizar, editar ou excluir.</p>
        </div>
        <span class="count-badge"><?= count($servicos) ?></span>
    </div>

    <?php if (empty($servicos)): ?>
        <div class="empty-state">Nenhum servico encontrado.</div>
    <?php else: ?>
        <div class="table-wrap">
            <table class="data-table">
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
                                <div class="table-actions">
                                    <a class="pill-link pill-link--view" href="visualizar-servico.php?id=<?= (int) $servico['id'] ?>">Visualizar</a>
                                    <a class="pill-link pill-link--edit" href="editar-servico.php?id=<?= (int) $servico['id'] ?>">Editar</a>
                                    <a class="pill-link pill-link--danger" href="excluir-servico.php?id=<?= (int) $servico['id'] ?>">Excluir</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
