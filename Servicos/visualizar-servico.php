<?php

require_once __DIR__ . '/_funcoes.php';

$pdo = conectar();
// a mensagem vem pela url depois de cadastrar ou editar
$id = pegarId($_GET['id'] ?? null);
$mensagem = trim($_GET['msg'] ?? '');

if ($id <= 0) {
    irPara('index.php?msg=' . urlencode('Serviço inválido.'));
}

// sem servico valido nao tem nada para mostrar
$servico = buscarServico($pdo, $id);
if (!$servico) {
    irPara('index.php?msg=' . urlencode('Serviço não encontrado.'));
}

$pageTitle = 'Silvana | Visualizar serviço';
$basePath = '../';
$activeSection = 'servicos';
?>
<?php require __DIR__ . '/../includes/head.php'; ?>
<?php require __DIR__ . '/../includes/sidebar.php'; ?>
<section class="page-header">
    <div>
        <span class="page-eyebrow">Servi&ccedil;os</span>
        <h1 class="page-title">Visualizar servi&ccedil;o</h1>
        <p class="page-description">Veja os dados do servi&ccedil;o.</p>
    </div>
    <div class="page-actions">
        <a class="btn btn--ghost" href="index.php">Voltar para servi&ccedil;os</a>
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
            <h2 class="section-title">Dados do servi&ccedil;o</h2>
            <p class="section-copy">Dados salvos.</p>
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
            <p class="detail-value"><?= escapar($servico['nome']) ?></p>
        </article>
        <article class="detail-item detail-item--full">
            <span class="detail-label">Descri&ccedil;&atilde;o</span>
            <p class="detail-value"><?= nl2br(escapar($servico['descricao'] ?? '')) ?></p>
        </article>
    </div>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
