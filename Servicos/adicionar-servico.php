<?php
declare(strict_types=1);

require_once __DIR__ . '/_funcoes.php';

$pdo = conectar();
// essas variaveis guardam a ultima tentativa do formulario caso apareca erro
$nome = '';
$descricao = '';
$valorBase = '';
$erros = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // limpa os campos do post e ja normaliza o valor para aceitar virgula ou ponto
    $nome = trim((string) ($_POST['nome'] ?? ''));
    $descricao = trim((string) ($_POST['descricao'] ?? ''));
    $valorBase = normalizarValor((string) ($_POST['valor_base'] ?? ''));
    // aqui o valor precisa realmente virar numero para seguir para o banco
    $valorValidado = filter_var($valorBase, FILTER_VALIDATE_FLOAT);

    // nome e o minimo para a lista de servicos fazer sentido
    if ($nome === '') {
        $erros[] = 'O nome do servico e obrigatorio.';
    }

    if ($valorValidado === false || $valorValidado < 0) {
        $erros[] = 'Informe um valor base valido.';
    }

    if (empty($erros)) {
        // so insere quando nome e valor passaram para nao nascer servico quebrado
        $sql = 'INSERT INTO servicos (nome, descricao, valor_base)
                VALUES (:nome, :descricao, :valor_base)
                RETURNING id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':nome', $nome, PDO::PARAM_STR);
        if ($descricao === '') {
            // descricao opcional vazia vai como null e deixa o banco mais limpo
            $stmt->bindValue(':descricao', null, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':descricao', $descricao, PDO::PARAM_STR);
        }
        // formatar com duas casas evita salvar valor com formato baguncado
        $stmt->bindValue(':valor_base', number_format((float) $valorValidado, 2, '.', ''), PDO::PARAM_STR);
        $stmt->execute();

        // depois do insert cai nos detalhes e evita envio repetido ao atualizar a pagina
        $id = (int) $stmt->fetchColumn();
        irPara('visualizar-servico.php?id=' . $id . '&msg=' . urlencode('Servico cadastrado com sucesso.'));
    }
}

$pageTitle = 'Silvana | Cadastrar servico';
$basePath = '../';
$activeSection = 'servicos';
?>
<?php require __DIR__ . '/../includes/head.php'; ?>
<?php require __DIR__ . '/../includes/sidebar.php'; ?>
<section class="page-header">
    <div>
        <span class="page-eyebrow">Novo cadastro</span>
        <h1 class="page-title">Cadastrar servico</h1>
        <p class="page-description">Cadastre um novo servi&ccedil;o seguindo o mesmo fluxo de valida&ccedil;&atilde;o do sistema.</p>
    </div>
    <div class="page-actions">
        <a class="btn btn--ghost" href="index.php">Voltar para servicos</a>
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
            <h2 class="section-title">Dados do servico</h2>
            <p class="section-copy">Os campos abaixo mant&ecirc;m o mesmo envio esperado pelas regras atuais.</p>
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
            <label for="descricao">Descricao</label>
            <textarea name="descricao" id="descricao" rows="5" cols="50"><?= escapar($descricao) ?></textarea>
        </div>

        <div class="form-actions field--full">
            <button class="btn btn--primary" type="submit">Salvar servico</button>
            <a class="btn btn--secondary" href="index.php">Cancelar</a>
        </div>
    </form>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
