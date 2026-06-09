<?php
declare(strict_types=1);

require_once __DIR__ . '/_funcoes.php';

$pdo = conectar();
$id = pegarId($_GET['id'] ?? $_POST['id'] ?? null);

if ($id <= 0) {
    irPara('index.php?msg=' . urlencode('Cliente invalido.'));
}

$cliente = buscarCliente($pdo, $id);
if ($cliente === null) {
    irPara('index.php?msg=' . urlencode('Cliente nao encontrado.'));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare('DELETE FROM clientes WHERE id = :id');
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    irPara('index.php?msg=' . urlencode('Cliente excluido com sucesso.'));
}

$pageTitle = 'Silvana | Excluir cliente';
$basePath = '../';
$activeSection = 'clientes';
?>
<?php require __DIR__ . '/../includes/head.php'; ?>
<?php require __DIR__ . '/../includes/sidebar.php'; ?>
<section class="page-header">
    <div>
        <span class="page-eyebrow">Acao sensivel</span>
        <h1 class="page-title">Excluir cliente</h1>
        <p class="page-description">Confirme a exclus&atilde;o apenas se tiver certeza de que este cadastro n&atilde;o deve permanecer no sistema.</p>
    </div>
    <div class="page-actions">
        <a class="btn btn--ghost" href="index.php">Voltar para clientes</a>
        <a class="btn btn--secondary" href="visualizar-cliente.php?id=<?= $id ?>">Cancelar</a>
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
        <p class="confirm-copy">Tem certeza que deseja excluir esta cliente? Esta a&ccedil;&atilde;o segue o mesmo fluxo j&aacute; existente no sistema.</p>
        <p class="confirm-target"><?= escapar((string) $cliente['nome']) ?></p>

        <form method="post" class="form-actions confirm-actions">
            <input type="hidden" name="id" value="<?= $id ?>">
            <button class="btn btn--danger" type="submit">Confirmar exclusao</button>
            <a class="btn btn--secondary" href="visualizar-cliente.php?id=<?= $id ?>">Cancelar</a>
        </form>
    </article>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
