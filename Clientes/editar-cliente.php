<?php
declare(strict_types=1);

require_once __DIR__ . '/_funcoes.php';

$pdo = conectar();
$id = pegarId($_GET['id'] ?? $_POST['id'] ?? null);
$erros = [];

if ($id <= 0) {
    irPara('index.php?msg=' . urlencode('Cliente invalido.'));
}

$cliente = buscarCliente($pdo, $id);
if ($cliente === null) {
    irPara('index.php?msg=' . urlencode('Cliente nao encontrado.'));
}

$nome = (string) $cliente['nome'];
$telefone = (string) ($cliente['telefone'] ?? '');
$observacoes = (string) ($cliente['observacoes'] ?? '');
$telefoneOriginal = $telefone;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim((string) ($_POST['nome'] ?? ''));
    $telefone = trim((string) ($_POST['telefone'] ?? ''));
    $observacoes = trim((string) ($_POST['observacoes'] ?? ''));

    if ($nome === '') {
        $erros[] = 'O nome da cliente e obrigatorio.';
    }

    if (normalizarTelefone($telefone) !== normalizarTelefone($telefoneOriginal)) {
        $clienteComMesmoTelefone = buscarClientePorTelefone($pdo, $telefone, $id);
        if ($clienteComMesmoTelefone !== null) {
            $erros[] = montarMensagemTelefoneDuplicado($clienteComMesmoTelefone);
        }
    }

    if (empty($erros)) {
        $sql = 'UPDATE clientes
                SET nome = :nome, telefone = :telefone, observacoes = :observacoes
                WHERE id = :id';
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
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            irPara('visualizar-cliente.php?id=' . $id . '&msg=' . urlencode('Cliente atualizado com sucesso.'));
        } catch (PDOException $erro) {
            if (!ehViolacaoTelefoneDuplicado($erro)) {
                throw $erro;
            }

            $clienteComMesmoTelefone = buscarClientePorTelefone($pdo, $telefone, $id);
            if ($clienteComMesmoTelefone !== null) {
                $erros[] = montarMensagemTelefoneDuplicado($clienteComMesmoTelefone);
            } else {
                $erros[] = 'Ja existe uma cliente cadastrada com este telefone.';
            }
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
        <span class="page-eyebrow">Atualizacao de cadastro</span>
        <h1 class="page-title">Editar cliente</h1>
        <p class="page-description">Ajuste os dados da cliente mantendo os mesmos campos e o mesmo processamento do sistema.</p>
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
            <p class="section-copy">Os valores j&aacute; carregados continuam sendo exibidos com seguran&ccedil;a usando a fun&ccedil;&atilde;o de escape existente.</p>
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
            <label for="observacoes">Observacoes</label>
            <textarea name="observacoes" id="observacoes" rows="5" cols="50"><?= escapar($observacoes) ?></textarea>
        </div>

        <div class="form-actions field--full">
            <button class="btn btn--primary" type="submit">Atualizar cliente</button>
            <a class="btn btn--secondary" href="visualizar-cliente.php?id=<?= $id ?>">Cancelar</a>
        </div>
    </form>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
