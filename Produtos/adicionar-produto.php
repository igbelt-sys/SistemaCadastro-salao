<?php

require_once __DIR__ . '/_funcoes.php';

$pdo = conectar();
// segura o que a pessoa digitou se o formulario voltar com erro
$nome = '';
$descricao = '';
$marca = '';
$quantidade = '0';
$erros = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // limpa o post antes de validar
    $nome = trim($_POST['nome'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $marca = trim($_POST['marca'] ?? '');
    $quantidade = trim($_POST['quantidade'] ?? '0');

    // quantidade precisa virar numero inteiro valido
    $quantidadeValidada = filter_var($quantidade, FILTER_VALIDATE_INT, [
        'options' => ['min_range' => 0],
    ]);

    if ($nome === '') {
        $erros[] = 'O nome do produto é obrigatório.';
    }

    if ($quantidadeValidada === false) {
        $erros[] = 'Informe uma quantidade válida.';
    }

    // so salva quando nao sobrou erro
    if (!$erros) {
        $stmt = $pdo->prepare(
            'INSERT INTO produtos (nome, descricao, marca, quantidade)
             VALUES (:nome, :descricao, :marca, :quantidade)
             RETURNING id'
        );
        // descricao e marca vazias viram null no banco
        $stmt->execute([
            ':nome' => $nome,
            ':descricao' => valorOuNulo($descricao),
            ':marca' => valorOuNulo($marca),
            ':quantidade' => (int) $quantidadeValidada,
        ]);

        $id = (int) $stmt->fetchColumn();
        irPara('visualizar-produto.php?id=' . $id . '&msg=' . urlencode('Produto cadastrado com sucesso.'));
    }
}

$pageTitle = 'Silvana | Cadastrar produto';
$basePath = '../';
$activeSection = 'produtos';
?>
<?php require __DIR__ . '/../includes/head.php'; ?>
<?php require __DIR__ . '/../includes/sidebar.php'; ?>
<section class="page-header">
    <div>
        <span class="page-eyebrow">Produtos</span>
        <h1 class="page-title">Cadastrar produto</h1>
        <p class="page-description">Preencha os campos abaixo.</p>
    </div>
    <div class="page-actions">
        <a class="btn btn--ghost" href="index.php">Voltar para produtos</a>
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
            <h2 class="section-title">Dados do produto</h2>
            <p class="section-copy">Digite os dados e salve.</p>
        </div>
    </div>

    <form method="post" class="form-grid">
        <div class="field">
            <label for="nome">Nome</label>
            <input type="text" name="nome" id="nome" value="<?= escapar($nome) ?>" required>
        </div>

        <div class="field">
            <label for="marca">Marca</label>
            <input type="text" name="marca" id="marca" value="<?= escapar($marca) ?>">
        </div>

        <div class="field field--full">
            <label for="descricao">Descri&ccedil;&atilde;o</label>
            <textarea name="descricao" id="descricao" rows="5" cols="50"><?= escapar($descricao) ?></textarea>
        </div>

        <div class="field">
            <label for="quantidade">Quantidade</label>
            <input type="number" name="quantidade" id="quantidade" min="0" value="<?= escapar($quantidade) ?>">
        </div>

        <div class="form-actions field--full">
            <button class="btn btn--primary" type="submit">Salvar produto</button>
            <a class="btn btn--secondary" href="index.php">Cancelar</a>
        </div>
    </form>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
