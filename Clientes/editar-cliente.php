<?php

require_once __DIR__ . '/_funcoes.php';

$pdo = conectar();
// o id pode vir da url ao abrir a tela ou do post ao salvar
$id = pegarId($_GET['id'] ?? $_POST['id'] ?? null);
$erros = [];

if ($id <= 0) {
    irPara('index.php?msg=' . urlencode('Cliente inválido.'));
}

// carrega o cadastro atual para preencher o formulario
$cliente = buscarCliente($pdo, $id);
if (!$cliente) {
    irPara('index.php?msg=' . urlencode('Cliente não encontrado.'));
}

$nome = $cliente['nome'];
$telefone = $cliente['telefone'] ?? '';
$observacoes = $cliente['observacoes'] ?? '';
$telefoneOriginal = $telefone;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // quando envia o formulario a nova tentativa passa a valer aqui
    $nome = trim($_POST['nome'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');
    $observacoes = trim($_POST['observacoes'] ?? '');

    if ($nome === '') {
        $erros[] = 'O nome da cliente é obrigatório.';
    }

    // so checa duplicidade se o telefone realmente mudou
    if (normalizarTelefone($telefone) !== normalizarTelefone($telefoneOriginal)) {
        $clienteComMesmoTelefone = buscarClientePorTelefone($pdo, $telefone, $id);

        if ($clienteComMesmoTelefone) {
            $erros[] = montarMensagemTelefoneDuplicado($clienteComMesmoTelefone);
        }
    }

    // com tudo certo atualiza o mesmo registro
    if (!$erros) {
        try {
            $stmt = $pdo->prepare(
                'UPDATE clientes
                 SET nome = :nome, telefone = :telefone, observacoes = :observacoes
                 WHERE id = :id'
            );
            // campo vazio vira null para o banco nao guardar string vazia
            $stmt->execute([
                ':nome' => $nome,
                ':telefone' => valorOuNulo($telefone),
                ':observacoes' => valorOuNulo($observacoes),
                ':id' => $id,
            ]);

            irPara('visualizar-cliente.php?id=' . $id . '&msg=' . urlencode('Cliente atualizado com sucesso.'));
        } catch (PDOException $erro) {
            // se o banco barrar duplicidade a gente mostra a mensagem certa
            if (!ehViolacaoTelefoneDuplicado($erro)) {
                throw $erro;
            }

            $clienteComMesmoTelefone = buscarClientePorTelefone($pdo, $telefone, $id);
            $erros[] = $clienteComMesmoTelefone
                ? montarMensagemTelefoneDuplicado($clienteComMesmoTelefone)
                : 'Já existe uma cliente cadastrada com este telefone.';
        }
    }
}

$pageTitle = 'Silvana | Editar cliente';
$basePath = '../';
$activeSection = 'clientes';
?>
<?php require __DIR__ . '/../includes/head.php'; ?>
<?php require __DIR__ . '/../includes/sidebar.php'; ?>
<section class="page-header">
    <div>
        <span class="page-eyebrow">Clientes</span>
        <h1 class="page-title">Editar cliente</h1>
        <p class="page-description">Altere os dados abaixo.</p>
    </div>
    <div class="page-actions">
        <a class="btn btn--ghost" href="index.php">Voltar para clientes</a>
        <a class="btn btn--secondary" href="visualizar-cliente.php?id=<?= $id ?>">Visualizar cliente</a>
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
            <label for="telefone">Telefone</label>
            <input type="text" name="telefone" id="telefone" value="<?= escapar($telefone) ?>">
        </div>

        <div class="field field--full">
            <label for="observacoes">Observa&ccedil;&otilde;es</label>
            <textarea name="observacoes" id="observacoes" rows="5" cols="50"><?= escapar($observacoes) ?></textarea>
        </div>

        <div class="form-actions field--full">
            <button class="btn btn--primary" type="submit">Atualizar cliente</button>
            <a class="btn btn--secondary" href="visualizar-cliente.php?id=<?= $id ?>">Cancelar</a>
        </div>
    </form>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
