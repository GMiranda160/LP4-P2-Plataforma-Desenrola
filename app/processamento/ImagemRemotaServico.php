<?php

declare(strict_types=1);

/**
 * Camada: Processamento. Dono: Guilherme. Só Guilherme altera este arquivo.
 * Responsabilidade: baixar uma imagem da web a partir de um link colado no montador.
 *
 * O navegador não consegue ler a imagem de outro site (CORS), então o servidor baixa por ele.
 * Como o link vem da pessoa, cada pedido é tratado como hostil (SSRF):
 *   - só http e https, nas portas 80 e 443, sem usuário e senha no link;
 *   - o nome do site é resolvido aqui e TODOS os IPs precisam ser públicos (nada de
 *     localhost, rede interna, metadados da nuvem); o cURL é preso a esse IP (CURLOPT_RESOLVE),
 *     para o DNS não trocar o endereço entre a checagem e o download;
 *   - redirecionamentos são seguidos à mão, até 3, repetindo a checagem a cada salto;
 *   - no máximo 10 MB e 10 segundos; o conteúdo precisa ser PNG, JPEG ou WebP de verdade.
 */
class ImagemRemotaServico
{
    private const TAMANHO_MAXIMO = 10 * 1024 * 1024;
    private const TIMEOUT = 10;
    private const REDIRECIONAMENTOS = 3;
    private const TIPOS = ['image/png', 'image/jpeg', 'image/webp'];

    /**
     * Devolve ['conteudo' => bytes, 'tipo' => mime].
     * Lança InvalidArgumentException com mensagem amigável se o link não servir.
     */
    public function baixar(string $url): array
    {
        $url = trim($url);

        for ($salto = 0; $salto <= self::REDIRECIONAMENTOS; $salto++) {
            [$host, $porta, $ip] = $this->validar($url);
            $resposta = $this->pedir($url, $host, $porta, $ip);

            if ($resposta['status'] >= 300 && $resposta['status'] < 400 && $resposta['local'] !== '') {
                $url = $this->resolverRelativo($url, $resposta['local']);
                continue;
            }

            if ($resposta['status'] !== 200) {
                throw new InvalidArgumentException('O site não entregou a imagem (erro ' . $resposta['status'] . ').');
            }

            $info = @getimagesizefromstring($resposta['corpo']);
            $tipo = is_array($info) ? (string) ($info['mime'] ?? '') : '';

            if (!in_array($tipo, self::TIPOS, true)) {
                throw new InvalidArgumentException(
                    'Esse link não é de uma imagem PNG, JPEG ou WebP. Clique com o botão direito na imagem e use "Copiar endereço da imagem".'
                );
            }

            return ['conteudo' => $resposta['corpo'], 'tipo' => $tipo];
        }

        throw new InvalidArgumentException('O link redireciona demais. Tente o endereço direto da imagem.');
    }

    /** Confere o link e devolve [host, porta, ip público já resolvido]. */
    private function validar(string $url): array
    {
        $partes = parse_url($url);
        $esquema = strtolower((string) ($partes['scheme'] ?? ''));
        $host = strtolower((string) ($partes['host'] ?? ''));

        if (!in_array($esquema, ['http', 'https'], true) || $host === '' || isset($partes['user']) || isset($partes['pass'])) {
            throw new InvalidArgumentException('Cole um link que comece com http:// ou https://.');
        }

        $porta = (int) ($partes['port'] ?? ($esquema === 'https' ? 443 : 80));

        if (!in_array($porta, [80, 443], true)) {
            throw new InvalidArgumentException('Esse link usa uma porta que não é permitida.');
        }

        $host = trim($host, '[]');
        $ips = filter_var($host, FILTER_VALIDATE_IP) ? [$host] : $this->resolver($host);

        if ($ips === []) {
            throw new InvalidArgumentException('Não encontrei esse site. Confira o link.');
        }

        foreach ($ips as $ip) {
            if (!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                throw new InvalidArgumentException('Esse endereço não é permitido.');
            }
        }

        return [$host, $porta, $ips[0]];
    }

    /** IPs (v4 e v6) do nome do site. */
    private function resolver(string $host): array
    {
        $registros = @dns_get_record($host, DNS_A | DNS_AAAA) ?: [];
        $ips = [];

        foreach ($registros as $r) {
            if (isset($r['ip'])) {
                $ips[] = $r['ip'];
            } elseif (isset($r['ipv6'])) {
                $ips[] = $r['ipv6'];
            }
        }

        return $ips;
    }

    /** Um pedido GET, preso ao IP validado, sem seguir redirecionamento e com teto de tamanho. */
    private function pedir(string $url, string $host, int $porta, string $ip): array
    {
        $corpo = '';
        $local = '';
        $estourou = false;
        $curl = curl_init($url);

        if ($curl === false) {
            throw new InvalidArgumentException('Não consegui abrir o link agora. Tente de novo.');
        }

        $ipCurl = str_contains($ip, ':') ? '[' . $ip . ']' : $ip;

        curl_setopt_array($curl, [
            CURLOPT_RESOLVE => [$host . ':' . $porta . ':' . $ipCurl],
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_PROTOCOLS => CURLPROTO_HTTP | CURLPROTO_HTTPS,
            CURLOPT_TIMEOUT => self::TIMEOUT,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (compatible; Desenrola/1.0)',
            CURLOPT_HTTPHEADER => ['Accept: image/png,image/jpeg,image/webp,image/*;q=0.8'],
            CURLOPT_HEADERFUNCTION => function ($c, string $linha) use (&$local): int {
                if (stripos($linha, 'location:') === 0) {
                    $local = trim(substr($linha, 9));
                }

                return strlen($linha);
            },
            CURLOPT_WRITEFUNCTION => function ($c, string $pedaco) use (&$corpo, &$estourou): int {
                if (strlen($corpo) + strlen($pedaco) > self::TAMANHO_MAXIMO) {
                    $estourou = true;

                    return 0; // interrompe o download
                }

                $corpo .= $pedaco;

                return strlen($pedaco);
            },
        ]);

        $cacert = RAIZ . '/cacert.pem';

        if (is_file($cacert)) {
            curl_setopt($curl, CURLOPT_CAINFO, $cacert);
        }

        $ok = curl_exec($curl);
        $status = (int) curl_getinfo($curl, CURLINFO_RESPONSE_CODE);

        if ($estourou) {
            throw new InvalidArgumentException('A imagem desse link passa de 10 MB.');
        }

        if ($ok === false) {
            throw new InvalidArgumentException('Não consegui baixar a imagem desse link. Confira o endereço.');
        }

        return ['status' => $status, 'local' => $local, 'corpo' => $corpo];
    }

    /** Monta o endereço absoluto de um Location relativo. */
    private function resolverRelativo(string $base, string $local): string
    {
        if (preg_match('#^https?://#i', $local)) {
            return $local;
        }

        $p = parse_url($base);
        $origem = $p['scheme'] . '://' . $p['host'] . (isset($p['port']) ? ':' . $p['port'] : '');

        if (str_starts_with($local, '//')) {
            return $p['scheme'] . ':' . $local;
        }

        if (str_starts_with($local, '/')) {
            return $origem . $local;
        }

        $pasta = preg_replace('#/[^/]*$#', '/', (string) ($p['path'] ?? '/'));

        return $origem . $pasta . $local;
    }
}
