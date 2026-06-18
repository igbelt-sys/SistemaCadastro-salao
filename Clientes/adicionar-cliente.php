<?php

require_once __DIR__ . '/_funcoes.php';

$pdo = conectar();
// segura o que a pessoa digitou se o formulario voltar com erro
$nome = '';
$telefone = '';
$observacoes = '';
$erros = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // limpa o post antes de validar
    $nome = trim($_POST['nome'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');
    $observacoes = trim($_POST['observacoes'] ?? '');

    if ($nome === '') {
        $erros[] = 'O nome da cliente é obrigatório.';
    }

    // evita cadastrar duas clientes com o mesmo telefone
    $clienteComMesmoTelefone = buscarClientePorTelefone($pdo, $telefone);
    if ($clienteComMesmoTelefone) {
        $erros[] = montarMensagemTelefoneDuplicado($clienteComMesmoTelefone);
    }

    // so salva quando nao sobrou erro
    if (!$erros) {
        try {
            $stmt = $pdo->prepare(
                'INSERT INTO clientes (nome, telefone, observacoes)
                 VALUES (:nome, :telefone, :observacoes)
                 RETURNING id'
            );
            // telefone e observacao vazios viram null no banco
            $stmt->execute([
                ':nome' => $nome,
                ':telefone' => valorOuNulo($telefone),
                ':observacoes' => valorOuNulo($observacoes),
            ]);

            $id = (int) $stmt->fetchColumn();
            irPara('visualizar-cliente.php?id=' . $id . '&msg=' . urlencode('Cliente cadastrado com sucesso.'));
        } catch (PDOException $erro) {
            // se outro cadastro salvar no mesmo instante o banco ainda protege aqui
            if (!ehViolacaoTelefoneDuplicado($erro)) {
                throw $erro;
            }

            $clienteComMesmoTelefone = buscarClientePorTelefone($pdo, $telefone);
            $erros[] = $clienteComMesmoTelefone
                ? montarMensagemTelefoneDuplicado($clienteComMesmoTelefone)
                : 'Já existe uma cliente cadastrada com este telefone.';
        }
    }
}

$pageTitle = 'Silvana | Cadastrar cliente';
$basePath = '../';
$activeSection = 'clientes';
?>
<?php require __DIR__ . '/../includes/head.php'; ?>
<?php require __DIR__ . '/../includes/sidebar.php'; ?>
<section class="page-header">
    <div>
        <span class="page-eyebrow">Clientes</span>
        <h1 class="page-title">Cadastrar cliente</h1>
        <p class="page-description">Preencha os campos abaixo.</p>
    </div>
    <div class="page-actions">
        <a class="btn btn--ghost" href="index.php">Voltar para clientes</a>
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
            <h2 class="section-title">Dados da cliente</h2>
            <p class="section-copy">Digite os dados e salve.</p>
        </div>
    </div>

    <form method="post" class="form-grid">
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
            <button class="btn btn--primary" type="submit">Salvar cliente</button>
            <a class="btn btn--secondary" href="index.php">Cancelar</a>
        </div>
    </form>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
