<?php
declare(strict_types=1);

require_once __DIR__ . '/_funcoes.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // historico mexe em dado entao so aceita post para evitar acao acidental por link
    irPara('index.php?msg=' . urlencode('Metodo invalido para adicionar historico.'));
}

$pdo = conectar();
// tudo vem do formulario da tela de detalhes entao aqui a gente limpa antes de validar
$clienteId = pegarId($_POST['cliente_id'] ?? null);
$servicoId = pegarId($_POST['servico_id'] ?? null);
$dataHistorico = trim((string) ($_POST['data_historico'] ?? ''));
$observacao = trim((string) ($_POST['observacao'] ?? ''));

if ($clienteId <= 0) {
    irPara('index.php?msg=' . urlencode('Cliente invalido.'));
}

// confere se a cliente ainda existe para nao criar historico solto no banco
$cliente = buscarCliente($pdo, $clienteId);
if ($cliente === null) {
    irPara('index.php?msg=' . urlencode('Cliente nao encontrado.'));
}

if ($servicoId > 0) {
    // quando a pessoa escolhe um servico ele precisa existir mesmo para o vinculo ficar valido
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

// essa checagem confirma que a data bate certinho com o formato do input
$dataValida = DateTime::createFromFormat('Y-m-d', $dataHistorico);
if (!$dataValida || $dataValida->format('Y-m-d') !== $dataHistorico) {
    irPara('visualizar-cliente.php?id=' . $clienteId . '&msg=' . urlencode('Data invalida.'));
}

// so depois de todas as travas o historico entra no banco
$sql = 'INSERT INTO historico_clientes (cliente_id, servico_id, data_historico, observacao)
        VALUES (:cliente_id, :servico_id, :data_historico, :observacao)';
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':cliente_id', $clienteId, PDO::PARAM_INT);
if ($servicoId <= 0) {
    // servico opcional vazio vira null para mostrar que esse atendimento nao foi vinculado
    $stmt->bindValue(':servico_id', null, PDO::PARAM_NULL);
} else {
    $stmt->bindValue(':servico_id', $servicoId, PDO::PARAM_INT);
}
$stmt->bindValue(':data_historico', $dataHistorico, PDO::PARAM_STR);
$stmt->bindValue(':observacao', $observacao, PDO::PARAM_STR);
$stmt->execute();

irPara('visualizar-cliente.php?id=' . $clienteId . '&msg=' . urlencode('Historico adicionado com sucesso.'));
