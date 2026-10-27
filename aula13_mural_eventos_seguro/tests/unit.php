<?php
declare(strict_types=1);

$falhas = [];
$testes = 0;
function testar(string $nome, callable $teste): void {
    global $falhas, $testes;
    $testes++;
    try { $teste(); echo "OK  $nome\n"; }
    catch (Throwable $e) { $falhas[] = "$nome: {$e->getMessage()}"; echo "FALHA $nome: {$e->getMessage()}\n"; }
}
function afirmar(bool $condicao, string $mensagem = 'asserção falhou'): void {
    if (!$condicao) { throw new RuntimeException($mensagem); }
}
function igual(mixed $esperado, mixed $obtido, string $mensagem = ''): void {
    afirmar($esperado === $obtido, $mensagem ?: 'esperado ' . var_export($esperado, true) . ', obtido ' . var_export($obtido, true));
}

require __DIR__ . '/../publicar/includes/funcoes.php';

testar('token CSRF usa 32 bytes, permanece estável e valida com comparação segura', function (): void {
    $_SESSION = [];
    $primeiro = csrf_token();
    igual(64, strlen($primeiro));
    igual($primeiro, csrf_token());
    afirmar(validar_csrf($primeiro));
    afirmar(!validar_csrf(str_repeat('0', 64)));
    afirmar(!validar_csrf(null));
});

testar('validação de evento limita descrição e campos textuais', function (): void {
    $base = ['titulo'=>'Evento', 'descricao'=>'Texto', 'data_evento'=>'2026-11-12', 'local_evento'=>'Sala', 'situacao'=>'Publicado'];
    igual([], validar_evento($base));
    $base['descricao'] = str_repeat('a', 5001);
    afirmar(in_array('A descrição deve ter no máximo 5000 caracteres.', validar_evento($base), true));
});

testar('IDs aceitos são somente inteiros positivos canônicos', function (): void {
    igual(12, id_positivo('12'));
    igual(null, id_positivo('0'));
    igual(null, id_positivo('-1'));
    igual(null, id_positivo('12x'));
    igual(null, id_positivo(['12']));
});

testar('metadados de upload rejeitam estrutura não escalar e arquivo não enviado', function (): void {
    $arquivo = ['name'=>['x.png'], 'tmp_name'=>'/x', 'error'=>UPLOAD_ERR_OK, 'size'=>10];
    $r = validar_upload($arquivo, fn(string $p): bool => true);
    afirmar($r['erros'] !== []);
    $arquivo = ['name'=>'x.png', 'tmp_name'=>'/x', 'error'=>UPLOAD_ERR_OK, 'size'=>10];
    $r = validar_upload($arquivo, fn(string $p): bool => false);
    afirmar(in_array('O arquivo recebido não é um upload HTTP válido.', $r['erros'], true));
});

testar('upload válido resulta em MIME controlado e nome opaco .bin', function (): void {
    $tmp = tempnam(sys_get_temp_dir(), 'png');
    file_put_contents($tmp, base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII='));
    try {
        $arquivo = ['name'=>'cartaz.PNG', 'tmp_name'=>$tmp, 'error'=>UPLOAD_ERR_OK, 'size'=>filesize($tmp)];
        $r = validar_upload($arquivo, fn(string $p): bool => true);
        igual([], $r['erros']);
        igual('image/png', $r['mime']);
        $nome = criar_nome_upload();
        afirmar((bool) preg_match('/^[a-f0-9]{32}\\.bin$/', $nome), 'nome não é aleatório/opaco: ' . $nome);
    } finally { @unlink($tmp); }
});

testar('imagem acima de 6000 pixels é rejeitada', function (): void {
    $tmp = tempnam(sys_get_temp_dir(), 'png');
    $png = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=');
    // Altera largura do IHDR para 6001; Fileinfo continua identificando PNG.
    $png = substr_replace($png, pack('N', 6001), 16, 4);
    file_put_contents($tmp, $png);
    try {
        $arquivo = ['name'=>'grande.png', 'tmp_name'=>$tmp, 'error'=>UPLOAD_ERR_OK, 'size'=>filesize($tmp)];
        $r = validar_upload($arquivo, fn(string $p): bool => true);
        afirmar($r['erros'] !== []);
    } finally { @unlink($tmp); }
});

testar('cabeçalhos de segurança centrais incluem política exigida', function (): void {
    $h = implode("\n", cabecalhos_seguranca());
    afirmar(str_contains($h, "Content-Security-Policy: default-src 'self'"));
    afirmar(str_contains($h, 'X-Content-Type-Options: nosniff'));
    afirmar(str_contains($h, 'Referrer-Policy: strict-origin-when-cross-origin'));
    afirmar(str_contains($h, 'X-Frame-Options: DENY'));
    afirmar(str_contains($h, 'Cache-Control: no-store'));
});

testar('configuração recusa placeholders de forma controlada', function (): void {
    $config = ['usuario'=>'SEU_USUARIO', 'senha'=>'SUA_SENHA', 'banco'=>'SEU_BANCO'];
    try { validar_configuracao($config); }
    catch (RuntimeException $e) { afirmar(str_contains($e->getMessage(), 'includes/config.php')); return; }
    throw new RuntimeException('placeholder foi aceito');
});

testar('URL base preserva raiz e subpasta', function (): void {
    igual('', detectar_base_url('/login.php'));
    igual('/mural', detectar_base_url('/mural/login.php'));
    igual('/mural', detectar_base_url('/mural/eventos/salvar.php'));
});

testar('timeout de sessão vence somente depois de 30 minutos', function (): void {
    afirmar(!sessao_expirada(1000, 2800));
    afirmar(sessao_expirada(1000, 2801));
});

testar('config.php conserva placeholders e autodetecta subpasta', function (): void {
    putenv('DB_USER'); putenv('DB_PASS'); putenv('DB_NAME'); putenv('APP_BASE');
    $_SERVER['SCRIPT_NAME'] = '/disciplina/mural/eventos/salvar.php';
    $config = require __DIR__ . '/../publicar/includes/config.php';
    igual('SEU_USUARIO', $config['usuario']); igual('SUA_SENHA', $config['senha']); igual('SEU_BANCO', $config['banco']);
    igual('/disciplina/mural', $config['base_url']);
});

echo "\n$testes testes, " . count($falhas) . " falhas.\n";
if ($falhas) { exit(1); }
