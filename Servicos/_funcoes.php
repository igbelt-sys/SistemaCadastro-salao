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

function buscarServico(PDO $pdo, int $id): ?array
{
    // deixa a busca pronta num lugar so para as telas reaproveitarem
    $stmt = $pdo->prepare(
        'SELECT id, nome, descricao, valor_base
         FROM servicos
         WHERE id = :id
         LIMIT 1'
    );
    $stmt->execute([':id' => $id]);

    return $stmt->fetch() ?: null;
}

function irPara(string $url): void
{
    // centraliza o redirecionamento e corta o resto da execucao
    header('Location: ' . $url);
    exit;
}

function normalizarValor(string $valor): string
{
    // troca virgula por ponto para o valor entrar num formato so
    return str_replace(',', '.', trim($valor));
}
