<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/conexao.php';

function escapar(string $texto): string
{
    return htmlspecialchars($texto, ENT_QUOTES, 'UTF-8');
}

function pegarId($valor): int
{
    $id = filter_var($valor, FILTER_VALIDATE_INT, [
        'options' => ['min_range' => 1],
    ]);

    return $id === false ? 0 : (int) $id;
}

function normalizarTelefone(string $telefone): string
{
    $telefoneNormalizado = preg_replace('/\D+/', '', $telefone);

    return $telefoneNormalizado ?? '';
}

function buscarClientePorTelefone(PDO $pdo, string $telefone, ?int $ignorarId = null): ?array
{
    $telefoneNormalizado = normalizarTelefone($telefone);
    if ($telefoneNormalizado === '') {
        return null;
    }

    $sql = 'SELECT id, nome, telefone
            FROM clientes
            WHERE regexp_replace(COALESCE(telefone, \'\'), \'[^0-9]\', \'\', \'g\') = :telefone';

    if ($ignorarId !== null) {
        $sql .= ' AND id <> :id';
    }

    $sql .= ' ORDER BY id ASC
              LIMIT 1';

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':telefone', $telefoneNormalizado, PDO::PARAM_STR);

    if ($ignorarId !== null) {
        $stmt->bindValue(':id', $ignorarId, PDO::PARAM_INT);
    }

    $stmt->execute();

    $cliente = $stmt->fetch();

    return $cliente ?: null;
}

function buscarCliente(PDO $pdo, int $id): ?array
{
    $sql = 'SELECT id, nome, telefone, observacoes, criado_em
            FROM clientes
            WHERE id = :id
            LIMIT 1';

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    $cliente = $stmt->fetch();

    return $cliente ?: null;
}

function montarMensagemTelefoneDuplicado(array $cliente): string
{
    return 'Ja existe uma cliente cadastrada com este telefone: '
        . (string) $cliente['nome']
        . ' (ID '
        . (int) $cliente['id']
        . ').';
}

function ehViolacaoTelefoneDuplicado(PDOException $erro): bool
{
    $mensagem = (string) ($erro->errorInfo[2] ?? $erro->getMessage());

    return $erro->getCode() === '23505'
        && str_contains($mensagem, 'ux_clientes_telefone_normalizado');
}

function formatarDataHora(?string $valor): string
{
    if ($valor === null) {
        return '';
    }

    $valor = trim($valor);
    if ($valor === '') {
        return '';
    }

    try {
        $timezone = new DateTimeZone(APP_TIMEZONE);
        $dataHora = new DateTimeImmutable($valor, $timezone);

        return $dataHora->setTimezone($timezone)->format('d/m/Y H:i:s');
    } catch (Exception) {
        return $valor;
    }
}

function irPara(string $url): void
{
    header('Location: ' . $url);
    exit;
}
