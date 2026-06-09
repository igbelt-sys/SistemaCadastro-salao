<?php
declare(strict_types=1);

require_once __DIR__ . '/_funcoes.php';

$pdo = conectar();
$nome = '';
$telefone = '';
$observacoes = '';
$erros = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim((string) ($_POST['nome'] ?? ''));
    $telefone = trim((string) ($_POST['telefone'] ?? ''));
    $observacoes = trim((string) ($_POST['observacoes'] ?? ''));

    if ($nome === '') {
        $erros[] = 'O nome da cliente e obrigatorio.';
    }

    $clienteComMesmoTelefone = buscarClientePorTelefone($pdo, $telefone);
    if ($clienteComMesmoTelefone !== null) {
        $erros[] = montarMensagemTelefoneDuplicado($clienteComMesmoTelefone);
    }

    if (empty($erros)) {
        $sql = 'INSERT INTO clientes (nome, telefone, observacoes)
                VALUES (:nome, :telefone, :observacoes)
                RETURNING id';
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':nome', $nome, PDO::PARAM_STR);
            if ($telefone === '') {
                $stmt->bindValue(':telefone', null, PDO::PARAM_NULL);
            } else {
                $stmt->bindValue(':telefone', $telefone, PDO::PARAM_STR);
            }
            if ($observacoes === '') {
                $stmt->bindValue(':observacoes', null, PDO::PARAM_NULL);
            } else {
                $stmt->bindValue(':observacoes', $observacoes, PDO::PARAM_STR);
            }
            $stmt->execute();

            $id = (int) $stmt->fetchColumn();
            irPara('visualizar-cliente.php?id=' . $id . '&msg=' . urlencode('Cliente cadastrado com sucesso.'));
        } catch (PDOException $erro) {
            if (!ehViolacaoTelefoneDuplicado($erro)) {
                throw $erro;
            }

            $clienteComMesmoTelefone = buscarClientePorTelefone($pdo, $telefone);
            if ($clienteComMesmoTelefone !== null) {
                $erros[] = montarMensagemTelefoneDuplicado($clienteComMesmoTelefone);
            } else {
                $erros[] = 'Ja existe uma cliente cadastrada com este telefone.';
            }
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
        <span class="page-eyebrow">Novo cadastro</span>
        <h1 class="page-title">Cadastrar cliente</h1>
        <p class="page-description">Preencha os dados principais da cliente sem alterar o fluxo atual do sistema.</p>
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
            <p class="section-copy">Os campos abaixo mant&ecirc;m o mesmo envio por POST j&aacute; utilizado pela aplica&ccedil;&atilde;o.</p>
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
            <label for="observacoes">Observacoes</label>
            <textarea name="observacoes" id="observacoes" rows="5" cols="50"><?= escapar($observacoes) ?></textarea>
        </div>

        <div class="form-actions field--full">
            <button class="btn btn--primary" type="submit">Salvar cliente</button>
            <a class="btn btn--secondary" href="index.php">Cancelar</a>
        </div>
    </form>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
