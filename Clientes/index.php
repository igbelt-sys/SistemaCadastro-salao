<?php

require_once __DIR__ . '/_funcoes.php';

$pdo = conectar();
// pega a busca e a mensagem que vieram pela url
$pesquisa = trim($_GET['pesquisa'] ?? '');
$mensagem = trim($_GET['msg'] ?? '');

// a consulta comeca simples e so ganha filtro se a pessoa pesquisar
$sql = 'SELECT id, nome, telefone, observacoes, criado_em FROM clientes';
$params = [];

if ($pesquisa !== '') {
    $sql .= ' WHERE nome LIKE :pesquisa';
    $params[':pesquisa'] = '%' . $pesquisa . '%';
}

$sql .= ' ORDER BY nome ASC';

// executa no final com ou sem filtro
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$clientes = $stmt->fetchAll();

$pageTitle = 'Silvana | Clientes';
$basePath = '../';
$activeSection = 'clientes';
?>
<?php require __DIR__ . '/../includes/head.php'; ?>
<?php require __DIR__ . '/../includes/sidebar.php'; ?>
<section class="page-header">
    <div>
        <span class="page-eyebrow">Clientes</span>
        <h1 class="page-title">Clientes</h1>
        <p class="page-description">Veja as clientes cadastradas.</p>
    </div>
    <div class="page-actions">
        <a class="btn btn--primary" href="adicionar-cliente.php">Cadastrar cliente</a>
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
            <h2 class="section-title">Lista de clientes</h2>
            <p class="section-copy">Pesquise ou abra um cadastro.</p>
        </div>
        <span class="count-badge"><?= count($clientes) ?></span>
    </div>

    <?php if (empty($clientes)): ?>
        <div class="empty-state">Nenhum cliente encontrado.</div>
    <?php else: ?>
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Telefone</th>
                        <th>Observa&ccedil;&otilde;es</th>
                        <th>Criado em</th>
                        <th>A&ccedil;&otilde;es</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($clientes as $cliente): ?>
                        <tr>
                            <td><?= (int) $cliente['id'] ?></td>
                            <td><?= escapar($cliente['nome']) ?></td>
                            <td><?= escapar($cliente['telefone'] ?? '') ?></td>
                            <td><?= nl2br(escapar($cliente['observacoes'] ?? '')) ?></td>
                            <td><?= escapar(formatarDataHora($cliente['criado_em'])) ?></td>
                            <td>
                                <div class="table-actions">
                                    <a class="pill-link pill-link--view" href="visualizar-cliente.php?id=<?= (int) $cliente['id'] ?>">Visualizar</a>
                                    <a class="pill-link pill-link--edit" href="editar-cliente.php?id=<?= (int) $cliente['id'] ?>">Editar</a>
                                    <a class="pill-link pill-link--danger" href="excluir-cliente.php?id=<?= (int) $cliente['id'] ?>">Excluir</a>
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
