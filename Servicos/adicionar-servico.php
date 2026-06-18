<?php

require_once __DIR__ . '/_funcoes.php';

$pdo = conectar();
// segura o que a pessoa digitou se o formulario voltar com erro
$nome = '';
$descricao = '';
$valorBase = '';
$erros = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // limpa o post antes de validar
    $nome = trim($_POST['nome'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $valorBase = normalizarValor($_POST['valor_base'] ?? '');

    // tenta transformar o valor digitado em numero de verdade
    $valorValidado = filter_var($valorBase, FILTER_VALIDATE_FLOAT);

    if ($nome === '') {
        $erros[] = 'O nome do serviço é obrigatório.';
    }

    if ($valorValidado === false || $valorValidado < 0) {
        $erros[] = 'Informe um valor base válido.';
    }

    // so salva quando nao sobrou erro
    if (!$erros) {
        $stmt = $pdo->prepare(
            'INSERT INTO servicos (nome, descricao, valor_base)
             VALUES (:nome, :descricao, :valor_base)
             RETURNING id'
        );
        // descricao vazia vira null e o valor vai formatado certinho para o banco
        $stmt->execute([
            ':nome' => $nome,
            ':descricao' => valorOuNulo($descricao),
            ':valor_base' => number_format((float) $valorValidado, 2, '.', ''),
        ]);

        $id = (int) $stmt->fetchColumn();
        irPara('visualizar-servico.php?id=' . $id . '&msg=' . urlencode('Serviço cadastrado com sucesso.'));
    }
}

$pageTitle = 'Silvana | Cadastrar serviço';
$basePath = '../';
$activeSection = 'servicos';
?>
<?php require __DIR__ . '/../includes/head.php'; ?>
<?php require __DIR__ . '/../includes/sidebar.php'; ?>
<section class="page-header">
    <div>
        <span class="page-eyebrow">Servi&ccedil;os</span>
        <h1 class="page-title">Cadastrar servi&ccedil;o</h1>
        <p class="page-description">Preencha os campos abaixo.</p>
    </div>
    <div class="page-actions">
        <a class="btn btn--ghost" href="index.php">Voltar para servi&ccedil;os</a>
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
            <h2 class="section-title">Dados do servi&ccedil;o</h2>
            <p class="section-copy">Digite os dados e salve.</p>
        </div>
    </div>

    <form method="post" class="form-grid">
        <div class="field">
            <label for="nome">Nome</label>
            <input type="text" name="nome" id="nome" value="<?= escapar($nome) ?>" required>
        </div>

        <div class="field">
            <label for="valor_base">Valor base</label>
            <input type="number" name="valor_base" id="valor_base" step="0.01" min="0" value="<?= escapar($valorBase) ?>" required>
        </div>

        <div class="field field--full">
            <label for="descricao">Descri&ccedil;&atilde;o</label>
            <textarea name="descricao" id="descricao" rows="5" cols="50"><?= escapar($descricao) ?></textarea>
        </div>

        <div class="form-actions field--full">
            <button class="btn btn--primary" type="submit">Salvar servi&ccedil;o</button>
            <a class="btn btn--secondary" href="index.php">Cancelar</a>
        </div>
    </form>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
