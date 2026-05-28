<?php
declare(strict_types=1);

require_once __DIR__ . '/_funcoes.php';

$pdo = conectar();
$nome = '';
$descricao = '';
$marca = '';
$quantidade = '0';
$erros = [];

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
        $sql = 'INSERT INTO produtos (nome, descricao, marca, quantidade)
                VALUES (:nome, :descricao, :marca, :quantidade)
                RETURNING id';
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
        $stmt->execute();

        $id = (int) $stmt->fetchColumn();
        irPara('visualizar-produto.php?id=' . $id . '&msg=' . urlencode('Produto cadastrado com sucesso.'));
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar produto</title>
</head>
<body>
    <h1>Cadastrar produto</h1>

    <p><a href="index.php">Voltar para produtos</a></p>

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
            <label for="descricao">Descricao:</label><br>
            <textarea name="descricao" id="descricao" rows="5" cols="50"><?= escapar($descricao) ?></textarea>
        </p>

        <p>
            <label for="marca">Marca:</label><br>
            <input type="text" name="marca" id="marca" value="<?= escapar($marca) ?>">
        </p>

        <p>
            <label for="quantidade">Quantidade:</label><br>
            <input type="number" name="quantidade" id="quantidade" min="0" value="<?= escapar($quantidade) ?>">
        </p>

        <p>
            <button type="submit">Salvar produto</button>
        </p>
    </form>
</body>
</html>
