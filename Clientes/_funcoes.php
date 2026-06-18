<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/conexao.php';

function escapar(string $texto): string
{
    // isso limpa caracteres perigosos antes de jogar qualquer texto de volta na tela
    return htmlspecialchars($texto, ENT_QUOTES, 'UTF-8');
}

function pegarId($valor): int
{
    // aqui a gente so aceita numero inteiro maior que zero para nao buscar registro torto
    $id = filter_var($valor, FILTER_VALIDATE_INT, [
        'options' => ['min_range' => 1],
    ]);

    return $id === false ? 0 : (int) $id;
}

function normalizarTelefone(string $telefone): string
{
    // remove tudo que nao e numero para comparar telefone sem ligar para mascara
    $telefoneNormalizado = preg_replace('/\D+/', '', $telefone);

    return $telefoneNormalizado ?? '';
}

function buscarClientePorTelefone(PDO $pdo, string $telefone, ?int $ignorarId = null): ?array
{
    // se nao sobrar numero nenhum nem vale gastar consulta porque nao tem telefone de verdade
    $telefoneNormalizado = normalizarTelefone($telefone);
    if ($telefoneNormalizado === '') {
        return null;
    }

    // a comparacao tambem limpa o telefone salvo no banco para pegar duplicado mesmo com formatos diferentes
    $sql = 'SELECT id, nome, telefone
            FROM clientes
            WHERE regexp_replace(COALESCE(telefone, \'\'), \'[^0-9]\', \'\', \'g\') = :telefone';

    if ($ignorarId !== null) {
        // isso evita que na edicao a cliente bata com o proprio cadastro e pare o fluxo atoa
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
    // essa busca centralizada evita repetir a mesma consulta em varias telas
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
    // a mensagem ja volta montada com contexto para a pessoa entender qual cadastro bateu
    return 'Ja existe uma cliente cadastrada com este telefone: '
        . (string) $cliente['nome']
        . ' (ID '
        . (int) $cliente['id']
        . ').';
}

function ehViolacaoTelefoneDuplicado(PDOException $erro): bool
{
    // aqui a gente separa o erro de telefone duplicado dos outros erros de banco
    $mensagem = (string) ($erro->errorInfo[2] ?? $erro->getMessage());

    return $erro->getCode() === '23505'
        && str_contains($mensagem, 'ux_clientes_telefone_normalizado');
}

function formatarDataHora(?string $valor): string
{
    // se a data vier vazia devolve vazio mesmo para a tela nao inventar nada
    if ($valor === null) {
        return '';
    }

    $valor = trim($valor);
    if ($valor === '') {
        return '';
    }

    try {
        // tenta padronizar a data para um formato mais amigavel sem mudar o horario real
        $timezone = new DateTimeZone(APP_TIMEZONE);
        $dataHora = new DateTimeImmutable($valor, $timezone);

        return $dataHora->setTimezone($timezone)->format('d/m/Y H:i:s');
    } catch (Exception) {
        // se a conversao falhar pelo menos a tela ainda mostra o valor original
        return $valor;
    }
}

function irPara(string $url): void
{
    // centraliza o redirecionamento e corta a execucao para nada rodar depois
    header('Location: ' . $url);
    exit;
}
