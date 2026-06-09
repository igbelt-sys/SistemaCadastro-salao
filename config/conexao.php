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
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $senha = getenv('DB_PASS') !== false ? getenv('DB_PASS') : DB_PASS;

    $dsn = 'pgsql:host=' . DB_HOST
        . ';port=' . DB_PORT
        . ';dbname=' . DB_NAME;

    try {
        $pdo = new PDO($dsn, DB_USER, $senha, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
        $pdo->exec("SET TIME ZONE '" . APP_TIMEZONE . "'");
    } catch (PDOException $erro) {
        exit('Erro ao conectar com o banco de dados: ' . $erro->getMessage());
    }

    return $pdo;
}
