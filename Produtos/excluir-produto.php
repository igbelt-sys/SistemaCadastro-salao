<?php
declare(strict_types=1);

require_once __DIR__ . '/_funcoes.php';

$pdo = conectar();
// o id serve tanto para montar a confirmacao quanto para remover de fato no post
$id = pegarId($_GET['id'] ?? $_POST['id'] ?? null);

if ($id <= 0) {
    irPara('index.php?msg=' . urlencode('Produto invalido.'));
}

// antes de excluir confirma se o registro ainda existe para nao disparar delete no vazio
$produto = buscarProduto($pdo, $id);
if ($produto === null) {
    irPara('index.php?msg=' . urlencode('Produto nao encontrado.'));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // a exclusao fica presa no post para evitar remocao acidental so por abrir link
    $stmt = $pdo->prepare('DELETE FROM produtos WHERE id = :id');
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    irPara('index.php?msg=' . urlencode('Produto excluido com sucesso.'));
}

$pageTitle = 'Silvana | Excluir produto';
$basePath = '../';
$activeSection = 'produtos';
?>
<?php require __DIR__ . '/../includes/head.php'; ?>
<?php require __DIR__ . '/../includes/sidebar.php'; ?>
<section class="page-header">
    <div>
        <span class="page-eyebrow">Acao sensivel</span>
        <h1 class="page-title">Excluir produto</h1>
        <p class="page-description">Revise a confirma&ccedil;&atilde;o com cuidado antes de remover este item do cadastro.</p>
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
        <h2 class="confirm-title">Confirmar exclusao</h2>
        <p class="confirm-copy">Tem certeza que deseja excluir este produto? O processamento continua exatamente o mesmo.</p>
        <p class="confirm-target"><?= escapar((string) $produto['nome']) ?></p>

        <form method="post" class="form-actions confirm-actions">
            <input type="hidden" name="id" value="<?= $id ?>">
            <button class="btn btn--danger" type="submit">Confirmar exclusao</button>
            <a class="btn btn--secondary" href="visualizar-produto.php?id=<?= $id ?>">Cancelar</a>
        </form>
    </article>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
