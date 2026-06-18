<?php
declare(strict_types=1);

require_once __DIR__ . '/_funcoes.php';

$pdo = conectar();
$id = pegarId($_GET['id'] ?? null);
// essa mensagem costuma vir das telas que mandam de volta para os detalhes
$mensagem = trim((string) ($_GET['msg'] ?? ''));

if ($id <= 0) {
    irPara('index.php?msg=' . urlencode('Servico invalido.'));
}

// se nao achar o servico nao compensa seguir montando a pagina
$servico = buscarServico($pdo, $id);
if ($servico === null) {
    irPara('index.php?msg=' . urlencode('Servico nao encontrado.'));
}

$pageTitle = 'Silvana | Visualizar servico';
$basePath = '../';
$activeSection = 'servicos';
?>
<?php require __DIR__ . '/../includes/head.php'; ?>
<?php require __DIR__ . '/../includes/sidebar.php'; ?>
<section class="page-header">
    <div>
        <span class="page-eyebrow">Detalhes do servico</span>
        <h1 class="page-title">Visualizar servico</h1>
        <p class="page-description">Confira os dados do servi&ccedil;o antes de seguir para edi&ccedil;&atilde;o ou exclus&atilde;o.</p>
    </div>
    <div class="page-actions">
        <a class="btn btn--ghost" href="index.php">Voltar para servicos</a>
        <a class="btn btn--secondary" href="editar-servico.php?id=<?= $id ?>">Editar</a>
        <a class="btn btn--danger" href="excluir-servico.php?id=<?= $id ?>">Excluir</a>
    </div>
</section>

<?php if ($mensagem !== ''): ?>
    <div class="alert"><?= escapar($mensagem) ?></div>
<?php endif; ?>

<section class="panel panel--soft">
    <div class="section-header">
        <div>
            <h2 class="section-title">Dados do servico</h2>
            <p class="section-copy">Informa&ccedil;&otilde;es do servi&ccedil;o armazenadas para consulta.</p>
        </div>
    </div>

    <div class="detail-grid">
        <article class="detail-item">
            <span class="detail-label">ID</span>
            <p class="detail-value"><?= (int) $servico['id'] ?></p>
        </article>
        <article class="detail-item">
            <span class="detail-label">Valor base</span>
            <p class="detail-value">R$ <?= number_format((float) $servico['valor_base'], 2, ',', '.') ?></p>
        </article>
        <article class="detail-item detail-item--full">
            <span class="detail-label">Nome</span>
            <p class="detail-value"><?= escapar((string) $servico['nome']) ?></p>
        </article>
        <article class="detail-item detail-item--full">
            <span class="detail-label">Descricao</span>
            <p class="detail-value"><?= nl2br(escapar((string) ($servico['descricao'] ?? ''))) ?></p>
        </article>
    </div>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
