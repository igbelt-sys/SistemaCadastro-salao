<?php

require_once __DIR__ . '/_funcoes.php';

$pdo = conectar();
// pega a busca e a mensagem que vieram pela url
$pesquisa = trim($_GET['pesquisa'] ?? '');
$mensagem = trim($_GET['msg'] ?? '');

// a consulta comeca simples e so ganha filtro se a pessoa pesquisar
$sql = 'SELECT id, nome, descricao, marca, quantidade FROM produtos';
$params = [];

if ($pesquisa !== '') {
    $sql .= ' WHERE nome LIKE :pesquisa OR marca LIKE :pesquisa';
    $params[':pesquisa'] = '%' . $pesquisa . '%';
}

$sql .= ' ORDER BY nome ASC';

// executa no final com ou sem filtro
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$produtos = $stmt->fetchAll();

$pageTitle = 'Silvana | Produtos';
$basePath = '../';
$activeSection = 'produtos';
?>
<?php require __DIR__ . '/../includes/head.php'; ?>
<?php require __DIR__ . '/../includes/sidebar.php'; ?>
<section class="page-header">
    <div>
        <span class="page-eyebrow">Produtos</span>
        <h1 class="page-title">Produtos</h1>
        <p class="page-description">Veja os produtos cadastrados.</p>
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
            <p class="section-copy">Pesquise ou abra um produto.</p>
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
                        <th>A&ccedil;&otilde;es</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($produtos as $produto): ?>
                        <tr>
                            <td><?= (int) $produto['id'] ?></td>
                            <td><?= escapar($produto['nome']) ?></td>
                            <td><?= escapar($produto['marca'] ?? '') ?></td>
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
