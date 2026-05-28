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

function irPara(string $url): void
{
    header('Location: ' . $url);
    exit;
}
