<?php
/**
 * Camada: Core. Dono: Guilherme. Só Guilherme altera este arquivo.
 * Responsabilidade: criar e reaproveitar a conexão PDO com o Supabase.
 * Usar pooler na porta 6543, sslmode=require e PDO::ATTR_EMULATE_PREPARES.
 * Veja INSTRUCOES_GUILHERME.md, seção 4.
 */
class Conexao
{
    /** TODO(Guilherme): criar a conexão uma vez por requisição, com ERRMODE_EXCEPTION e FETCH_ASSOC. */
    public static function pdo(): PDO
    {
        throw new RuntimeException('Não implementado: Conexao::pdo');
    }
}
