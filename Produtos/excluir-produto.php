<?php
declare(strict_types=1);

require_once __DIR__ . '/_funcoes.php';

$pdo = conectar();
$id = pegarId($_GET['id'] ?? $_POST['id'] ?? null);

if ($id <= 0) {
    irPara('index.php?msg=' . urlencode('Produto invalido.'));
}

$produto = buscarProduto($pdo, $id);
if ($produto === null) {
    irPara('index.php?msg=' . urlencode('Produto nao encontrado.'));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare('DELETE FROM produtos WHERE id = :id');
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    irPara('index.php?msg=' . urlencode('Produto excluido com sucesso.'));
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir produto</title>
</head>
<body>
    <h1>Excluir produto</h1>

    <p>
        <a href="index.php">Voltar para produtos</a> |
        <a href="visualizar-produto.php?id=<?= $id ?>">Visualizar produto</a>
    </p>

    <p>Tem certeza que deseja excluir este produto?</p>
    <p><strong><?= escapar((string) $produto['nome']) ?></strong></p>

    <form method="post">
        <input type="hidden" name="id" value="<?= $id ?>">
        <button type="submit">Confirmar exclusao</button>
    </form>
</body>
</html>
