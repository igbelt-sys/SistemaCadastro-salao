<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/conexao.php';

function escapar(string $texto): string
{
    // limpa o texto antes de renderizar e segura html vindo de fora
    return htmlspecialchars($texto, ENT_QUOTES, 'UTF-8');
}

function pegarId($valor): int
{
    // aqui entra so id inteiro valido porque o resto nao serve para consultar banco
    $id = filter_var($valor, FILTER_VALIDATE_INT, [
        'options' => ['min_range' => 1],
    ]);

    return $id === false ? 0 : (int) $id;
}

function buscarProduto(PDO $pdo, int $id): ?array
{
    // deixa a busca do produto pronta num lugar so e evita copia e cola de consulta
    $sql = 'SELECT id, nome, descricao, marca, quantidade
            FROM produtos
            WHERE id = :id
            LIMIT 1';

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    $produto = $stmt->fetch();

    return $produto ?: null;
}

function irPara(string $url): void
{
    // redireciona e para a pagina logo em seguida para o fluxo ficar certinho
    header('Location: ' . $url);
    exit;
}
