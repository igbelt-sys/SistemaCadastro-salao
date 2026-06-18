<?php

require_once __DIR__ . '/_funcoes.php';

$pdo = conectar();
// o id pode vir da url ao abrir a tela ou do post ao salvar
$id = pegarId($_GET['id'] ?? $_POST['id'] ?? null);
$erros = [];

if ($id <= 0) {
    irPara('index.php?msg=' . urlencode('Serviço inválido.'));
}

// carrega o servico atual para preencher o formulario
$servico = buscarServico($pdo, $id);
if (!$servico) {
    irPara('index.php?msg=' . urlencode('Serviço não encontrado.'));
}

$nome = $servico['nome'];
$descricao = $servico['descricao'] ?? '';
$valorBase = number_format((float) $servico['valor_base'], 2, '.', '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // quando envia o formulario a nova tentativa passa a valer aqui
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

    // com tudo certo atualiza o mesmo registro
    if (!$erros) {
        $stmt = $pdo->prepare(
            'UPDATE servicos
             SET nome = :nome, descricao = :descricao, valor_base = :valor_base
             WHERE id = :id'
        );
        // descricao vazia vira null e o valor vai formatado certinho para o banco
        $stmt->execute([
            ':nome' => $nome,
            ':descricao' => valorOuNulo($descricao),
            ':valor_base' => number_format((float) $valorValidado, 2, '.', ''),
            ':id' => $id,
        ]);

        irPara('visualizar-servico.php?id=' . $id . '&msg=' . urlencode('Serviço atualizado com sucesso.'));
    }
}

$pageTitle = 'Silvana | Editar serviço';
$basePath = '../';
$activeSection = 'servicos';
?>
<?php require __DIR__ . '/../includes/head.php'; ?>
<?php require __DIR__ . '/../includes/sidebar.php'; ?>
<section class="page-header">
    <div>
        <span class="page-eyebrow">Servi&ccedil;os</span>
        <h1 class="page-title">Editar servi&ccedil;o</h1>
        <p class="page-description">Altere os dados abaixo.</p>
    </div>
    <div class="page-actions">
        <a class="btn btn--ghost" href="index.php">Voltar para servi&ccedil;os</a>
        <a class="btn btn--secondary" href="visualizar-servico.php?id=<?= $id ?>">Visualizar servi&ccedil;o</a>
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
            <label for="valor_base">Valor base</label>
            <input type="number" name="valor_base" id="valor_base" step="0.01" min="0" value="<?= escapar($valorBase) ?>" required>
        </div>

        <div class="field field--full">
            <label for="descricao">Descri&ccedil;&atilde;o</label>
            <textarea name="descricao" id="descricao" rows="5" cols="50"><?= escapar($descricao) ?></textarea>
        </div>

        <div class="form-actions field--full">
            <button class="btn btn--primary" type="submit">Atualizar servi&ccedil;o</button>
            <a class="btn btn--secondary" href="visualizar-servico.php?id=<?= $id ?>">Cancelar</a>
        </div>
    </form>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
