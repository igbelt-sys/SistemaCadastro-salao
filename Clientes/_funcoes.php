<?php

require_once __DIR__ . '/../config/conexao.php';

function escapar(string $texto): string
{
    // limpa o texto antes de mandar para a tela
    return htmlspecialchars($texto, ENT_QUOTES, 'UTF-8');
}

function pegarId($valor): int
{
    // so aceita id inteiro maior que zero
    $id = filter_var($valor, FILTER_VALIDATE_INT, [
        'options' => ['min_range' => 1],
    ]);

    return $id ? (int) $id : 0;
}

function valorOuNulo(string $valor): ?string
{
    // quando vier vazio salva como null no banco
    return $valor === '' ? null : $valor;
}

function normalizarTelefone(string $telefone): string
{
    // tira tudo que nao for numero para comparar sem ligar para mascara
    return preg_replace('/\D+/', '', $telefone) ?: '';
}

function buscarClientePorTelefone(PDO $pdo, string $telefone, ?int $ignorarId = null): ?array
{
    // se nao sobrar numero nao tem telefone para comparar
    $telefone = normalizarTelefone($telefone);

    if ($telefone === '') {
        return null;
    }

    $sql = 'SELECT id, nome, telefone
            FROM clientes
            WHERE regexp_replace(COALESCE(telefone, \'\'), \'[^0-9]\', \'\', \'g\') = :telefone';
    $params = [':telefone' => $telefone];

    // na edicao ignora o proprio cadastro para nao acusar duplicado errado
    if ($ignorarId !== null) {
        $sql .= ' AND id <> :id';
        $params[':id'] = $ignorarId;
    }

    $sql .= ' ORDER BY id ASC LIMIT 1';

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetch() ?: null;
}

function buscarCliente(PDO $pdo, int $id): ?array
{
    // deixa a busca pronta num lugar so para reaproveitar nas telas
    $stmt = $pdo->prepare(
        'SELECT id, nome, telefone, observacoes, criado_em
         FROM clientes
         WHERE id = :id
         LIMIT 1'
    );
    $stmt->execute([':id' => $id]);

    return $stmt->fetch() ?: null;
}

function montarMensagemTelefoneDuplicado(array $cliente): string
{
    // monta uma mensagem mais clara para quem esta usando o sistema
    return 'Já existe uma cliente cadastrada com este telefone: '
        . $cliente['nome']
        . ' (ID '
        . $cliente['id']
        . ').';
}

function ehViolacaoTelefoneDuplicado(PDOException $erro): bool
{
    // separa o erro de telefone duplicado dos outros erros do banco
    $mensagem = $erro->errorInfo[2] ?? $erro->getMessage();

    return $erro->getCode() === '23505'
        && str_contains($mensagem, 'ux_clientes_telefone_normalizado');
}

function formatarDataHora(?string $valor): string
{
    // se vier vazio devolve vazio mesmo
    if (!$valor || trim($valor) === '') {
        return '';
    }

    try {
        // tenta formatar a data e se nao der devolve do jeito que veio
        $timezone = new DateTimeZone(APP_TIMEZONE);
        $dataHora = new DateTimeImmutable($valor, $timezone);

        return $dataHora->setTimezone($timezone)->format('d/m/Y H:i:s');
    } catch (Exception) {
        return $valor;
    }
}

function irPara(string $url): void
{
    // centraliza o redirecionamento e corta o resto da execucao
    header('Location: ' . $url);
    exit;
}
