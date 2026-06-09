<?php
declare(strict_types=1);

require_once __DIR__ . '/_funcoes.php';

$pdo = conectar();
$id = pegarId($_GET['id'] ?? $_POST['id'] ?? null);
$erros = [];

if ($id <= 0) {
    irPara('index.php?msg=' . urlencode('Produto invalido.'));
}

$produto = buscarProduto($pdo, $id);
if ($produto === null) {
    irPara('index.php?msg=' . urlencode('Produto nao encontrado.'));
}

$nome = (string) $produto['nome'];
$descricao = (string) ($produto['descricao'] ?? '');
$marca = (string) ($produto['marca'] ?? '');
$quantidade = (string) ($produto['quantidade'] ?? '0');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim((string) ($_POST['nome'] ?? ''));
    $descricao = trim((string) ($_POST['descricao'] ?? ''));
    $marca = trim((string) ($_POST['marca'] ?? ''));
    $quantidade = trim((string) ($_POST['quantidade'] ?? '0'));
    $quantidadeValidada = filter_var($quantidade, FILTER_VALIDATE_INT, [
        'options' => ['min_range' => 0],
    ]);

    if ($nome === '') {
        $erros[] = 'O nome do produto e obrigatorio.';
    }

    if ($quantidadeValidada === false) {
        $erros[] = 'Informe uma quantidade valida.';
    }

    if (empty($erros)) {
        $sql = 'UPDATE produtos
                SET nome = :nome, descricao = :descricao, marca = :marca, quantidade = :quantidade
                WHERE id = :id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':nome', $nome, PDO::PARAM_STR);
        if ($descricao === '') {
            $stmt->bindValue(':descricao', null, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':descricao', $descricao, PDO::PARAM_STR);
        }
        if ($marca === '') {
            $stmt->bindValue(':marca', null, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':marca', $marca, PDO::PARAM_STR);
        }
        $stmt->bindValue(':quantidade', (int) $quantidadeValidada, PDO::PARAM_INT);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

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
        <span class="page-eyebrow">Atualizacao de cadastro</span>
        <h1 class="page-title">Editar produto</h1>
        <p class="page-description">Ajuste os dados do produto preservando os mesmos campos enviados por POST e o redirecionamento atual.</p>
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
            <p class="section-copy">Os valores atuais permanecem preenchidos para facilitar a atualiza&ccedil;&atilde;o do registro.</p>
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
            <label for="descricao">Descricao</label>
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
