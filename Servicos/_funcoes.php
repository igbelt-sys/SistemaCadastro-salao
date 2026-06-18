<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/conexao.php';

function escapar(string $texto): string
{
    // limpa o texto para a tela mostrar conteudo sem abrir brecha para html solto
    return htmlspecialchars($texto, ENT_QUOTES, 'UTF-8');
}

function pegarId($valor): int
{
    // so deixa passar id inteiro maior que zero porque e o minimo para a busca fazer sentido
    $id = filter_var($valor, FILTER_VALIDATE_INT, [
        'options' => ['min_range' => 1],
    ]);

    return $id === false ? 0 : (int) $id;
}

function buscarServico(PDO $pdo, int $id): ?array
{
    // guarda a consulta principal do servico num canto so para as telas reaproveitarem
    $sql = 'SELECT id, nome, descricao, valor_base
            FROM servicos
            WHERE id = :id
            LIMIT 1';

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    $servico = $stmt->fetch();

    return $servico ?: null;
}

function irPara(string $url): void
{
    // sair logo depois do header evita que qualquer html continue sendo montado
    header('Location: ' . $url);
    exit;
}

function normalizarValor(string $valor): string
{
    // troca virgula por ponto porque no formulario a pessoa pode digitar dos dois jeitos
    return str_replace(',', '.', trim($valor));
}
