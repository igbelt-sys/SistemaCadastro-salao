<?php
declare(strict_types=1);

const DB_HOST = '127.0.0.1';
const DB_PORT = '5432';
const DB_NAME = 'sistemasalao';
const DB_USER = 'postgres';
const DB_PASS = 'postgres';
const APP_TIMEZONE = 'America/Sao_Paulo';

date_default_timezone_set(APP_TIMEZONE);

function conectar(): PDO
{
    // a conexao fica guardada aqui para a mesma pagina nao abrir varias iguais sem necessidade
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    // se existir senha no ambiente ela ganha prioridade e ajuda a tirar segredo do codigo
    $senha = getenv('DB_PASS') !== false ? getenv('DB_PASS') : DB_PASS;

    // o dsn e o endereco que o pdo usa para achar o banco certo
    $dsn = 'pgsql:host=' . DB_HOST
        . ';port=' . DB_PORT
        . ';dbname=' . DB_NAME;

    try {
        // essas opcoes deixam os erros mais claros e a leitura das consultas mais pratica
        $pdo = new PDO($dsn, DB_USER, $senha, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
        // ja alinha o fuso do banco com o da aplicacao para data nao nascer torta
        $pdo->exec("SET TIME ZONE '" . APP_TIMEZONE . "'");
    } catch (PDOException $erro) {
        // sem banco nao tem como a pagina seguir entao o fluxo para aqui mesmo
        exit('Erro ao conectar com o banco de dados: ' . $erro->getMessage());
    }

    return $pdo;
}
