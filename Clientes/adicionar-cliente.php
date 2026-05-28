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

    if (empty($erros)) {
        $sql = 'INSERT INTO clientes (nome, telefone, observacoes)
                VALUES (:nome, :telefone, :observacoes)
                RETURNING id';
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
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar cliente</title>
</head>
<body>
    <h1>Cadastrar cliente</h1>

    <p><a href="index.php">Voltar para clientes</a></p>

    <?php if (!empty($erros)): ?>
        <ul>
            <?php foreach ($erros as $erro): ?>
                <li><?= escapar($erro) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="post">
        <p>
            <label for="nome">Nome:</label><br>
            <input type="text" name="nome" id="nome" value="<?= escapar($nome) ?>" required>
        </p>

        <p>
            <label for="telefone">Telefone:</label><br>
            <input type="text" name="telefone" id="telefone" value="<?= escapar($telefone) ?>">
        </p>

        <p>
            <label for="observacoes">Observacoes:</label><br>
            <textarea name="observacoes" id="observacoes" rows="5" cols="50"><?= escapar($observacoes) ?></textarea>
        </p>

        <p>
            <button type="submit">Salvar cliente</button>
        </p>
    </form>
</body>
</html>
