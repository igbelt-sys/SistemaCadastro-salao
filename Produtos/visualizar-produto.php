<?php

require_once __DIR__ . '/_funcoes.php';

$pdo = conectar();
// a mensagem vem pela url depois de cadastrar ou editar
$id = pegarId($_GET['id'] ?? null);
$mensagem = trim($_GET['msg'] ?? '');

if ($id <= 0) {
    irPara('index.php?msg=' . urlencode('Produto inválido.'));
}

// sem produto valido nao tem nada para mostrar
$produto = buscarProduto($pdo, $id);
if (!$produto) {
    irPara('index.php?msg=' . urlencode('Produto não encontrado.'));
}

$pageTitle = 'Silvana | Visualizar produto';
$basePath = '../';
$activeSection = 'produtos';
?>
<?php require __DIR__ . '/../includes/head.php'; ?>
<?php require __DIR__ . '/../includes/sidebar.php'; ?>
<section class="page-header">
    <div>
        <span class="page-eyebrow">Produtos</span>
        <h1 class="page-title">Visualizar produto</h1>
        <p class="page-description">Veja os dados do produto.</p>
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
            <p class="section-copy">Dados salvos.</p>
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
            <p class="detail-value"><?= escapar($produto['nome']) ?></p>
        </article>
        <article class="detail-item">
            <span class="detail-label">Marca</span>
            <p class="detail-value"><?= escapar($produto['marca'] ?? '') ?></p>
        </article>
        <article class="detail-item detail-item--full">
            <span class="detail-label">Descri&ccedil;&atilde;o</span>
            <p class="detail-value"><?= nl2br(escapar($produto['descricao'] ?? '')) ?></p>
        </article>
    </div>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
