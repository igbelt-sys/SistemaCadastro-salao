<?php
declare(strict_types=1);

require_once __DIR__ . '/_funcoes.php';

$pdo = conectar();
$id = pegarId($_GET['id'] ?? $_POST['id'] ?? null);

if ($id <= 0) {
    irPara('index.php?msg=' . urlencode('Servico invalido.'));
}

$servico = buscarServico($pdo, $id);
if ($servico === null) {
    irPara('index.php?msg=' . urlencode('Servico nao encontrado.'));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare('DELETE FROM servicos WHERE id = :id');
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    irPara('index.php?msg=' . urlencode('Servico excluido com sucesso.'));
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir servico</title>
</head>
<body>
    <h1>Excluir servico</h1>

    <p>
        <a href="index.php">Voltar para servicos</a> |
        <a href="visualizar-servico.php?id=<?= $id ?>">Visualizar servico</a>
    </p>

    <p>Tem certeza que deseja excluir este servico?</p>
    <p><strong><?= escapar((string) $servico['nome']) ?></strong></p>

    <form method="post">
        <input type="hidden" name="id" value="<?= $id ?>">
        <button type="submit">Confirmar exclusao</button>
    </form>
</body>
</html>
