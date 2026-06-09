<?php
declare(strict_types=1);

require_once __DIR__ . '/_funcoes.php';

$pdo = conectar();
$id = pegarId($_GET['id'] ?? $_POST['id'] ?? null);
$erros = [];

if ($id <= 0) {
    irPara('index.php?msg=' . urlencode('Servico invalido.'));
}

$servico = buscarServico($pdo, $id);
if ($servico === null) {
    irPara('index.php?msg=' . urlencode('Servico nao encontrado.'));
}

$nome = (string) $servico['nome'];
$descricao = (string) ($servico['descricao'] ?? '');
$valorBase = number_format((float) $servico['valor_base'], 2, '.', '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim((string) ($_POST['nome'] ?? ''));
    $descricao = trim((string) ($_POST['descricao'] ?? ''));
    $valorBase = normalizarValor((string) ($_POST['valor_base'] ?? ''));
    $valorValidado = filter_var($valorBase, FILTER_VALIDATE_FLOAT);

    if ($nome === '') {
        $erros[] = 'O nome do servico e obrigatorio.';
    }

    if ($valorValidado === false || $valorValidado < 0) {
        $erros[] = 'Informe um valor base valido.';
    }

    if (empty($erros)) {
        $sql = 'UPDATE servicos
                SET nome = :nome, descricao = :descricao, valor_base = :valor_base
                WHERE id = :id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':nome', $nome, PDO::PARAM_STR);
        if ($descricao === '') {
            $stmt->bindValue(':descricao', null, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':descricao', $descricao, PDO::PARAM_STR);
        }
        $stmt->bindValue(':valor_base', number_format((float) $valorValidado, 2, '.', ''), PDO::PARAM_STR);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        irPara('visualizar-servico.php?id=' . $id . '&msg=' . urlencode('Servico atualizado com sucesso.'));
    }
}

$pageTitle = 'Silvana | Editar servico';
$basePath = '../';
$activeSection = 'servicos';
?>
<?php require __DIR__ . '/../includes/head.php'; ?>
<?php require __DIR__ . '/../includes/sidebar.php'; ?>
<section class="page-header">
    <div>
        <span class="page-eyebrow">Atualizacao de cadastro</span>
        <h1 class="page-title">Editar servico</h1>
        <p class="page-description">Atualize descri&ccedil;&atilde;o e valor do servi&ccedil;o sem alterar a l&oacute;gica do cadastro.</p>
    </div>
    <div class="page-actions">
        <a class="btn btn--ghost" href="index.php">Voltar para servicos</a>
        <a class="btn btn--secondary" href="visualizar-servico.php?id=<?= $id ?>">Visualizar servico</a>
    </div>
</section>

<?php if (!empty($erros)): ?>
    <ul class="notice-list">
        <?php foreach ($erros as $erro): ?>
            <li><?= escapar($erro) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<section class="panel panel--soft">
    <div class="section-header">
        <div>
            <h2 class="section-title">Dados para edi&ccedil;&atilde;o</h2>
            <p class="section-copy">Os campos carregados continuam usando os mesmos nomes e o mesmo processamento.</p>
        </div>
    </div>

    <form method="post" class="form-grid">
        <input type="hidden" name="id" value="<?= $id ?>">

        <div class="field">
            <label for="nome">Nome</label>
            <input type="text" name="nome" id="nome" value="<?= escapar($nome) ?>" required>
        </div>

        <div class="field">
            <label for="valor_base">Valor base</label>
            <input type="number" name="valor_base" id="valor_base" step="0.01" min="0" value="<?= escapar($valorBase) ?>" required>
        </div>

        <div class="field field--full">
            <label for="descricao">Descricao</label>
            <textarea name="descricao" id="descricao" rows="5" cols="50"><?= escapar($descricao) ?></textarea>
        </div>

        <div class="form-actions field--full">
            <button class="btn btn--primary" type="submit">Atualizar servico</button>
            <a class="btn btn--secondary" href="visualizar-servico.php?id=<?= $id ?>">Cancelar</a>
        </div>
    </form>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
