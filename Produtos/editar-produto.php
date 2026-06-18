<?php

require_once __DIR__ . '/_funcoes.php';

$pdo = conectar();
// o id pode vir da url ao abrir a tela ou do post ao salvar
$id = pegarId($_GET['id'] ?? $_POST['id'] ?? null);
$erros = [];

if ($id <= 0) {
    irPara('index.php?msg=' . urlencode('Produto inválido.'));
}

// carrega o produto atual para preencher o formulario
$produto = buscarProduto($pdo, $id);
if (!$produto) {
    irPara('index.php?msg=' . urlencode('Produto não encontrado.'));
}

$nome = $produto['nome'];
$descricao = $produto['descricao'] ?? '';
$marca = $produto['marca'] ?? '';
$quantidade = (string) ($produto['quantidade'] ?? '0');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // quando envia o formulario a nova tentativa passa a valer aqui
    $nome = trim($_POST['nome'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $marca = trim($_POST['marca'] ?? '');
    $quantidade = trim($_POST['quantidade'] ?? '0');

    // quantidade precisa continuar inteira para nao baguncar o estoque
    $quantidadeValidada = filter_var($quantidade, FILTER_VALIDATE_INT, [
        'options' => ['min_range' => 0],
    ]);

    if ($nome === '') {
        $erros[] = 'O nome do produto é obrigatório.';
    }

    if ($quantidadeValidada === false) {
        $erros[] = 'Informe uma quantidade válida.';
    }

    // com tudo certo atualiza o mesmo registro
    if (!$erros) {
        $stmt = $pdo->prepare(
            'UPDATE produtos
             SET nome = :nome, descricao = :descricao, marca = :marca, quantidade = :quantidade
             WHERE id = :id'
        );
        // descricao e marca vazias viram null no banco
        $stmt->execute([
            ':nome' => $nome,
            ':descricao' => valorOuNulo($descricao),
            ':marca' => valorOuNulo($marca),
            ':quantidade' => (int) $quantidadeValidada,
            ':id' => $id,
        ]);

        irPara('visualizar-produto.php?id=' . $id . '&msg=' . urlencode('Produto atualizado com sucesso.'));
    }
}

$pageTitle = 'Silvana | Editar produto';
$basePath = '../';
$activeSection = 'produtos';
?>
<?php require __DIR__ . '/../includes/head.php'; ?>
<?php require __DIR__ . '/../includes/sidebar.php'; ?>
<section class="page-header">
    <div>
        <span class="page-eyebrow">Produtos</span>
        <h1 class="page-title">Editar produto</h1>
        <p class="page-description">Altere os dados abaixo.</p>
    </div>
    <div class="page-actions">
        <a class="btn btn--ghost" href="index.php">Voltar para produtos</a>
        <a class="btn btn--secondary" href="visualizar-produto.php?id=<?= $id ?>">Visualizar produto</a>
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
            <p class="section-copy">Edite e salve.</p>
        </div>
    </div>

    <form method="post" class="form-grid">
        <input type="hidden" name="id" value="<?= $id ?>">

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
            <button class="btn btn--primary" type="submit">Atualizar produto</button>
            <a class="btn btn--secondary" href="visualizar-produto.php?id=<?= $id ?>">Cancelar</a>
        </div>
    </form>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
