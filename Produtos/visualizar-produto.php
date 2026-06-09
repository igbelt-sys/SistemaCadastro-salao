<?php
declare(strict_types=1);

require_once __DIR__ . '/_funcoes.php';

$pdo = conectar();
$id = pegarId($_GET['id'] ?? null);
$mensagem = trim((string) ($_GET['msg'] ?? ''));

if ($id <= 0) {
    irPara('index.php?msg=' . urlencode('Produto invalido.'));
}

$produto = buscarProduto($pdo, $id);
if ($produto === null) {
    irPara('index.php?msg=' . urlencode('Produto nao encontrado.'));
}

$pageTitle = 'Silvana | Visualizar produto';
$basePath = '../';
$activeSection = 'produtos';
?>
<?php require __DIR__ . '/../includes/head.php'; ?>
<?php require __DIR__ . '/../includes/sidebar.php'; ?>
<section class="page-header">
    <div>
        <span class="page-eyebrow">Detalhes do produto</span>
        <h1 class="page-title">Visualizar produto</h1>
        <p class="page-description">Confira os dados do produto antes de seguir para edi&ccedil;&atilde;o ou exclus&atilde;o.</p>
    </div>
    <div class="page-actions">
        <a class="btn btn--ghost" href="index.php">Voltar para produtos</a>
        <a class="btn btn--secondary" href="editar-produto.php?id=<?= $id ?>">Editar</a>
        <a class="btn btn--danger" href="excluir-produto.php?id=<?= $id ?>">Excluir</a>
    </div>
</section>

<?php if ($mensagem !== ''): ?>
    <div class="alert"><?= escapar($mensagem) ?></div>
<?php endif; ?>

<section class="panel panel--soft">
    <div class="section-header">
        <div>
            <h2 class="section-title">Dados do produto</h2>
            <p class="section-copy">Informa&ccedil;&otilde;es registradas para consulta r&aacute;pida.</p>
        </div>
    </div>

    <div class="detail-grid">
        <article class="detail-item">
            <span class="detail-label">ID</span>
            <p class="detail-value"><?= (int) $produto['id'] ?></p>
        </article>
        <article class="detail-item">
            <span class="detail-label">Quantidade</span>
            <p class="detail-value"><?= (int) ($produto['quantidade'] ?? 0) ?></p>
        </article>
        <article class="detail-item">
            <span class="detail-label">Nome</span>
            <p class="detail-value"><?= escapar((string) $produto['nome']) ?></p>
        </article>
        <article class="detail-item">
            <span class="detail-label">Marca</span>
            <p class="detail-value"><?= escapar((string) ($produto['marca'] ?? '')) ?></p>
        </article>
        <article class="detail-item detail-item--full">
            <span class="detail-label">Descricao</span>
            <p class="detail-value"><?= nl2br(escapar((string) ($produto['descricao'] ?? ''))) ?></p>
        </article>
    </div>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
