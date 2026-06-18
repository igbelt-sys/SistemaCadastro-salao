<?php

const DB_HOST = '127.0.0.1';
const DB_PORT = '5432';
const DB_NAME = 'sistemasalao';
const DB_USER = 'postgres';
const DB_PASS = 'postgres';
const APP_TIMEZONE = 'America/Sao_Paulo';

date_default_timezone_set(APP_TIMEZONE);

function conectar(): PDO
{
    // guarda a conexao para a pagina nao abrir outra igual sem precisar
    static $pdo = null;

    if ($pdo) {
        return $pdo;
    }

    // monta o endereco do banco usando as configuracoes do projeto
    $dsn = 'pgsql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME;

    try {
        // essas opcoes deixam os erros mais claros e a leitura mais pratica
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);

        // ajusta o fuso para data e hora ficarem no mesmo padrao do sistema
        $pdo->exec("SET TIME ZONE '" . APP_TIMEZONE . "'");

        return $pdo;
    } catch (PDOException $erro) {
        // sem conexao nao da para o sistema seguir
        exit('Erro ao conectar com o banco de dados: ' . $erro->getMessage());
    }
}
