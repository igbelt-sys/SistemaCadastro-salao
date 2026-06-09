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

$pageTitle = 'Silvana | Produtos';
$basePath = '../';
$activeSection = 'produtos';
?>
<?php require __DIR__ . '/../includes/head.php'; ?>
<?php require __DIR__ . '/../includes/sidebar.php'; ?>
<section class="page-header">
    <div>
        <span class="page-eyebrow">Gestao de produtos</span>
        <h1 class="page-title">Produtos</h1>
        <p class="page-description">Organize os produtos utilizados nos atendimentos com uma listagem clara, elegante e funcional.</p>
    </div>
    <div class="page-actions">
        <a class="btn btn--primary" href="adicionar-produto.php">Cadastrar produto</a>
    </div>
</section>

<?php if ($mensagem !== ''): ?>
    <div class="alert"><?= escapar($mensagem) ?></div>
<?php endif; ?>

<section class="panel panel--soft">
    <form method="get" class="toolbar-search">
        <div class="field field--grow">
            <label for="pesquisa">Pesquisar por nome ou marca</label>
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
            <h2 class="section-title">Lista de produtos</h2>
            <p class="section-copy">Acompanhe os produtos cadastrados e siga para visualizar, editar ou excluir.</p>
        </div>
        <span class="count-badge"><?= count($produtos) ?></span>
    </div>

    <?php if (empty($produtos)): ?>
        <div class="empty-state">Nenhum produto encontrado.</div>
    <?php else: ?>
        <div class="table-wrap">
            <table class="data-table">
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
                                <div class="table-actions">
                                    <a class="pill-link pill-link--view" href="visualizar-produto.php?id=<?= (int) $produto['id'] ?>">Visualizar</a>
                                    <a class="pill-link pill-link--edit" href="editar-produto.php?id=<?= (int) $produto['id'] ?>">Editar</a>
                                    <a class="pill-link pill-link--danger" href="excluir-produto.php?id=<?= (int) $produto['id'] ?>">Excluir</a>
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
