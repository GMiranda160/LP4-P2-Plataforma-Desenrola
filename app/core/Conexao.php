<?php

declare(strict_types=1);

/**
 * Camada: Core. Dono: Guilherme. Só Guilherme altera este arquivo.
 * Responsabilidade: criar e devolver uma única conexão PDO com o banco.
 * Nenhuma outra classe abre conexão por conta própria.
 */
class Conexao
{
    private static ?PDO $pdo = null;

    /**
     * Devolve a conexão com o PostgreSQL do Supabase, criando na primeira chamada.
     * Lê as credenciais das variáveis de ambiente: DB_HOST, DB_PORT, DB_NAME,
     * DB_USER e DB_PASS. No Transaction pooler do Supabase, a porta é 6543 e o
     * usuário tem o formato postgres.<ref-do-projeto>.
     */
    public static function pdo(): PDO
    {
        if (self::$pdo === null) {
            $host = getenv('DB_HOST');
            $user = getenv('DB_USER');
            $pass = getenv('DB_PASS');

            if (!$host || !$user || $pass === false) {
                throw new RuntimeException('Banco não configurado: defina DB_HOST, DB_USER e DB_PASS no ambiente ou no .env.');
            }

            $dsn = sprintf(
                'pgsql:host=%s;port=%s;dbname=%s;sslmode=require',
                $host,
                getenv('DB_PORT') ?: '6543',
                getenv('DB_NAME') ?: 'postgres'
            );

            self::$pdo = new PDO($dsn, $user, (string) $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => true,
                PDO::ATTR_TIMEOUT => 8,
            ]);
        }

        return self::$pdo;
    }
}
