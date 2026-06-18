<?php
declare(strict_types=1);

require_once __DIR__ . '/_funcoes.php';

$pdo = conectar();
// aqui a gente pega o que veio da url para montar a busca e mostrar aviso depois de redirecionar
$pesquisa = trim((string) ($_GET['pesquisa'] ?? ''));
$mensagem = trim((string) ($_GET['msg'] ?? ''));

if ($pesquisa !== '') {
    // com texto preenchido a lista ja volta filtrada para facilitar achar a cliente
    $stmt = $pdo->prepare(
        'SELECT id, nome, telefone, observacoes, criado_em
         FROM clientes
         WHERE nome LIKE :pesquisa
         ORDER BY nome ASC'
    );
    $stmt->bindValue(':pesquisa', '%' . $pesquisa . '%', PDO::PARAM_STR);
} else {
    // sem pesquisa a ideia e trazer tudo ja ordenado para a tela nascer completa
    $stmt = $pdo->prepare(
        'SELECT id, nome, telefone, observacoes, criado_em
         FROM clientes
         ORDER BY nome ASC'
    );
}

// a consulta so roda depois que os parametros ficaram certinhos
$stmt->execute();
$clientes = $stmt->fetchAll();

$pageTitle = 'Silvana | Clientes';
$basePath = '../';
$activeSection = 'clientes';
?>
<?php require __DIR__ . '/../includes/head.php'; ?>
<?php require __DIR__ . '/../includes/sidebar.php'; ?>
<section class="page-header">
    <div>
        <span class="page-eyebrow">Gestao de clientes</span>
        <h1 class="page-title">Clientes</h1>
        <p class="page-description">
            Consulte cadastros, acompanhe observa&ccedil;&otilde;es e acesse as a&ccedil;&otilde;es principais em um ambiente visual leve e organizado.
        </p>
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
            <p class="section-copy">Visualize os registros cadastrados e siga para visualizar, editar ou excluir.</p>
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
                            <td><?= escapar(formatarDataHora((string) $cliente['criado_em'])) ?></td>
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
