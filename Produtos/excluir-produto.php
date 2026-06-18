<?php

require_once __DIR__ . '/_funcoes.php';

$pdo = conectar();
// o id vem por get para abrir a tela e por post para confirmar
$id = pegarId($_GET['id'] ?? $_POST['id'] ?? null);

if ($id <= 0) {
    irPara('index.php?msg=' . urlencode('Produto inválido.'));
}

// confirma se o produto ainda existe antes de mostrar ou excluir
$produto = buscarProduto($pdo, $id);
if (!$produto) {
    irPara('index.php?msg=' . urlencode('Produto não encontrado.'));
}

// a exclusao so roda no post para nao apagar por engano
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare('DELETE FROM produtos WHERE id = :id');
    $stmt->execute([':id' => $id]);

    irPara('index.php?msg=' . urlencode('Produto excluído com sucesso.'));
}

$pageTitle = 'Silvana | Excluir produto';
$basePath = '../';
$activeSection = 'produtos';
?>
<?php require __DIR__ . '/../includes/head.php'; ?>
<?php require __DIR__ . '/../includes/sidebar.php'; ?>
<section class="page-header">
    <div>
        <span class="page-eyebrow">Produtos</span>
        <h1 class="page-title">Excluir produto</h1>
        <p class="page-description">Confirme se quer excluir.</p>
    </div>
    <div class="page-actions">
        <a class="btn btn--ghost" href="index.php">Voltar para produtos</a>
        <a class="btn btn--secondary" href="visualizar-produto.php?id=<?= $id ?>">Cancelar</a>
    </div>
</section>

<section class="confirm-shell">
    <article class="confirm-card">
        <div class="confirm-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24">
                <path d="M12 9v4"></path>
                <path d="M12 17h.01"></path>
                <path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.72 3h16.92a2 2 0 0 0 1.72-3L13.71 3.86a2 2 0 0 0-3.42 0Z"></path>
            </svg>
        </div>
        <h2 class="confirm-title">Confirmar exclus&atilde;o</h2>
        <p class="confirm-copy">Este produto ser&aacute; removido.</p>
        <p class="confirm-target"><?= escapar($produto['nome']) ?></p>

        <form method="post" class="form-actions confirm-actions">
            <input type="hidden" name="id" value="<?= $id ?>">
            <button class="btn btn--danger" type="submit">Confirmar exclus&atilde;o</button>
            <a class="btn btn--secondary" href="visualizar-produto.php?id=<?= $id ?>">Cancelar</a>
        </form>
    </article>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
