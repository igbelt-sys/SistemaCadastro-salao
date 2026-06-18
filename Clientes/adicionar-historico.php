<?php

require_once __DIR__ . '/_funcoes.php';

// historico mexe em dado entao so aceita post
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    irPara('index.php?msg=' . urlencode('Método inválido para adicionar histórico.'));
}

$pdo = conectar();
// tudo vem do formulario da tela da cliente
$clienteId = pegarId($_POST['cliente_id'] ?? null);
$servicoId = pegarId($_POST['servico_id'] ?? null);
$dataHistorico = trim($_POST['data_historico'] ?? '');
$observacao = trim($_POST['observacao'] ?? '');

if ($clienteId <= 0) {
    irPara('index.php?msg=' . urlencode('Cliente inválido.'));
}

// garante que a cliente existe antes de salvar o historico
if (!buscarCliente($pdo, $clienteId)) {
    irPara('index.php?msg=' . urlencode('Cliente não encontrado.'));
}

// se escolheu servico ele precisa existir de verdade
if ($servicoId > 0) {
    $stmtServico = $pdo->prepare('SELECT id FROM servicos WHERE id = :id LIMIT 1');
    $stmtServico->execute([':id' => $servicoId]);

    if (!$stmtServico->fetch()) {
        irPara('visualizar-cliente.php?id=' . $clienteId . '&msg=' . urlencode('Serviço não encontrado.'));
    }
}

// data e observacao sao obrigatorias
if ($dataHistorico === '' || $observacao === '') {
    irPara('visualizar-cliente.php?id=' . $clienteId . '&msg=' . urlencode('Preencha data e observação.'));
}

// confere se a data realmente esta no formato do input
$dataValida = DateTime::createFromFormat('Y-m-d', $dataHistorico);
if (!$dataValida || $dataValida->format('Y-m-d') !== $dataHistorico) {
    irPara('visualizar-cliente.php?id=' . $clienteId . '&msg=' . urlencode('Data inválida.'));
}

// servico pode ficar vazio e nesse caso vai null
$stmt = $pdo->prepare(
    'INSERT INTO historico_clientes (cliente_id, servico_id, data_historico, observacao)
     VALUES (:cliente_id, :servico_id, :data_historico, :observacao)'
);
$stmt->execute([
    ':cliente_id' => $clienteId,
    ':servico_id' => $servicoId ?: null,
    ':data_historico' => $dataHistorico,
    ':observacao' => $observacao,
]);

irPara('visualizar-cliente.php?id=' . $clienteId . '&msg=' . urlencode('Histórico adicionado com sucesso.'));
