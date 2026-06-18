<?php
declare(strict_types=1);

require_once __DIR__ . '/_funcoes.php';

$pdo = conectar();
// isso segura os valores do formulario caso a validacao mande tentar de novo
$nome = '';
$descricao = '';
$marca = '';
$quantidade = '0';
$erros = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // limpa o que veio do post e ja reaproveita nas variaveis que voltam para a tela
    $nome = trim((string) ($_POST['nome'] ?? ''));
    $descricao = trim((string) ($_POST['descricao'] ?? ''));
    $marca = trim((string) ($_POST['marca'] ?? ''));
    $quantidade = trim((string) ($_POST['quantidade'] ?? '0'));
    // aqui a quantidade precisa virar inteiro valido e sem numero negativo
    $quantidadeValidada = filter_var($quantidade, FILTER_VALIDATE_INT, [
        'options' => ['min_range' => 0],
    ]);

    // produto sem nome vira confusao na lista entao barra logo de cara
    if ($nome === '') {
        $erros[] = 'O nome do produto e obrigatorio.';
    }

    if ($quantidadeValidada === false) {
        $erros[] = 'Informe uma quantidade valida.';
    }

    if (empty($erros)) {
        // so grava quando tudo passou porque a listagem depende desses campos estarem coerentes
        $sql = 'INSERT INTO produtos (nome, descricao, marca, quantidade)
                VALUES (:nome, :descricao, :marca, :quantidade)
                RETURNING id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':nome', $nome, PDO::PARAM_STR);
        if ($descricao === '') {
            // texto opcional vazio vira null para o banco guardar ausencia de dado de forma limpa
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
        $stmt->execute();

        // depois do cadastro ja pula para os detalhes e evita novo envio no refresh
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
        <span class="page-eyebrow">Novo cadastro</span>
        <h1 class="page-title">Cadastrar produto</h1>
        <p class="page-description">Cadastre um novo produto mantendo o mesmo fluxo de valida&ccedil;&atilde;o e persist&ecirc;ncia existente.</p>
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
            <p class="section-copy">Use os mesmos campos j&aacute; esperados pela regra atual da aplica&ccedil;&atilde;o.</p>
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
            <label for="descricao">Descricao</label>
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
