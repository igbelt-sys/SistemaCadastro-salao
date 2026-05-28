<?php
declare(strict_types=1);

require_once __DIR__ . '/_funcoes.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    irPara('index.php?msg=' . urlencode('Metodo invalido para adicionar historico.'));
}

$pdo = conectar();
$clienteId = pegarId($_POST['cliente_id'] ?? null);
$servicoId = pegarId($_POST['servico_id'] ?? null);
$dataHistorico = trim((string) ($_POST['data_historico'] ?? ''));
$observacao = trim((string) ($_POST['observacao'] ?? ''));

if ($clienteId <= 0) {
    irPara('index.php?msg=' . urlencode('Cliente invalido.'));
}

$cliente = buscarCliente($pdo, $clienteId);
if ($cliente === null) {
    irPara('index.php?msg=' . urlencode('Cliente nao encontrado.'));
}

if ($servicoId > 0) {
    $stmtServico = $pdo->prepare('SELECT id FROM servicos WHERE id = :id LIMIT 1');
    $stmtServico->bindValue(':id', $servicoId, PDO::PARAM_INT);
    $stmtServico->execute();

    if ($stmtServico->fetch() === false) {
        irPara('visualizar-cliente.php?id=' . $clienteId . '&msg=' . urlencode('Servico nao encontrado.'));
    }
}

if ($dataHistorico === '' || $observacao === '') {
    irPara('visualizar-cliente.php?id=' . $clienteId . '&msg=' . urlencode('Preencha data e observacao.'));
}

$dataValida = DateTime::createFromFormat('Y-m-d', $dataHistorico);
if (!$dataValida || $dataValida->format('Y-m-d') !== $dataHistorico) {
    irPara('visualizar-cliente.php?id=' . $clienteId . '&msg=' . urlencode('Data invalida.'));
}

$sql = 'INSERT INTO historico_clientes (cliente_id, servico_id, data_historico, observacao)
        VALUES (:cliente_id, :servico_id, :data_historico, :observacao)';
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':cliente_id', $clienteId, PDO::PARAM_INT);
if ($servicoId <= 0) {
    $stmt->bindValue(':servico_id', null, PDO::PARAM_NULL);
} else {
    $stmt->bindValue(':servico_id', $servicoId, PDO::PARAM_INT);
}
$stmt->bindValue(':data_historico', $dataHistorico, PDO::PARAM_STR);
$stmt->bindValue(':observacao', $observacao, PDO::PARAM_STR);
$stmt->execute();

irPara('visualizar-cliente.php?id=' . $clienteId . '&msg=' . urlencode('Historico adicionado com sucesso.'));
