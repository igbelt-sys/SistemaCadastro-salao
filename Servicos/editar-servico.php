<?php
declare(strict_types=1);

require_once __DIR__ . '/_funcoes.php';

$pdo = conectar();
$id = pegarId($_GET['id'] ?? $_POST['id'] ?? null);
$erros = [];

if ($id <= 0) {
    irPara('index.php?msg=' . urlencode('Servico invalido.'));
}

$servico = buscarServico($pdo, $id);
if ($servico === null) {
    irPara('index.php?msg=' . urlencode('Servico nao encontrado.'));
}

$nome = (string) $servico['nome'];
$descricao = (string) ($servico['descricao'] ?? '');
$valorBase = number_format((float) $servico['valor_base'], 2, '.', '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim((string) ($_POST['nome'] ?? ''));
    $descricao = trim((string) ($_POST['descricao'] ?? ''));
    $valorBase = normalizarValor((string) ($_POST['valor_base'] ?? ''));
    $valorValidado = filter_var($valorBase, FILTER_VALIDATE_FLOAT);

    if ($nome === '') {
        $erros[] = 'O nome do servico e obrigatorio.';
    }

    if ($valorValidado === false || $valorValidado < 0) {
        $erros[] = 'Informe um valor base valido.';
    }

    if (empty($erros)) {
        $sql = 'UPDATE servicos
                SET nome = :nome, descricao = :descricao, valor_base = :valor_base
                WHERE id = :id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':nome', $nome, PDO::PARAM_STR);
        if ($descricao === '') {
            $stmt->bindValue(':descricao', null, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':descricao', $descricao, PDO::PARAM_STR);
        }
        $stmt->bindValue(':valor_base', number_format((float) $valorValidado, 2, '.', ''), PDO::PARAM_STR);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        irPara('visualizar-servico.php?id=' . $id . '&msg=' . urlencode('Servico atualizado com sucesso.'));
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar servico</title>
</head>
<body>
    <h1>Editar servico</h1>

    <p>
        <a href="index.php">Voltar para servicos</a> |
        <a href="visualizar-servico.php?id=<?= $id ?>">Visualizar servico</a>
    </p>

    <?php if (!empty($erros)): ?>
        <ul>
            <?php foreach ($erros as $erro): ?>
                <li><?= escapar($erro) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="post">
        <input type="hidden" name="id" value="<?= $id ?>">

        <p>
            <label for="nome">Nome:</label><br>
            <input type="text" name="nome" id="nome" value="<?= escapar($nome) ?>" required>
        </p>

        <p>
            <label for="descricao">Descricao:</label><br>
            <textarea name="descricao" id="descricao" rows="5" cols="50"><?= escapar($descricao) ?></textarea>
        </p>

        <p>
            <label for="valor_base">Valor base:</label><br>
            <input type="number" name="valor_base" id="valor_base" step="0.01" min="0" value="<?= escapar($valorBase) ?>" required>
        </p>

        <p>
            <button type="submit">Atualizar servico</button>
        </p>
    </form>
</body>
</html>
