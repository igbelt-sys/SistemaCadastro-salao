<?php

require_once __DIR__ . '/_funcoes.php';

$pdo = conectar();
// pega a busca e a mensagem que vieram pela url
$pesquisa = trim($_GET['pesquisa'] ?? '');
$mensagem = trim($_GET['msg'] ?? '');

// a consulta comeca simples e so ganha filtro se a pessoa pesquisar
$sql = 'SELECT id, nome, descricao, valor_base FROM servicos';
$params = [];

if ($pesquisa !== '') {
    $sql .= ' WHERE nome LIKE :pesquisa';
    $params[':pesquisa'] = '%' . $pesquisa . '%';
}

$sql .= ' ORDER BY nome ASC';

// executa no final com ou sem filtro
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$servicos = $stmt->fetchAll();

$pageTitle = 'Silvana | Serviços';
$basePath = '../';
$activeSection = 'servicos';
?>
<?php require __DIR__ . '/../includes/head.php'; ?>
<?php require __DIR__ . '/../includes/sidebar.php'; ?>
<section class="page-header">
    <div>
        <span class="page-eyebrow">Servi&ccedil;os</span>
        <h1 class="page-title">Servi&ccedil;os</h1>
        <p class="page-description">Veja os servi&ccedil;os cadastrados.</p>
    </div>
    <div class="page-actions">
        <a class="btn btn--primary" href="adicionar-servico.php">Cadastrar servi&ccedil;o</a>
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
            <h2 class="section-title">Lista de servi&ccedil;os</h2>
            <p class="section-copy">Pesquise ou abra um servi&ccedil;o.</p>
        </div>
        <span class="count-badge"><?= count($servicos) ?></span>
    </div>

    <?php if (empty($servicos)): ?>
        <div class="empty-state">Nenhum servi&ccedil;o encontrado.</div>
    <?php else: ?>
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Valor base</th>
                        <th>A&ccedil;&otilde;es</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($servicos as $servico): ?>
                        <tr>
                            <td><?= (int) $servico['id'] ?></td>
                            <td><?= escapar($servico['nome']) ?></td>
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
