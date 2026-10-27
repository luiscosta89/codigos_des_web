<?php
declare(strict_types=1);

const LIMITE_UPLOAD = 2_000_000;
const LIMITE_DESCRICAO = 5000;

function situacoes_evento(): array { return ['Rascunho', 'Publicado', 'Finalizado']; }

function validar_evento(array $dados): array
{
    $erros = [];
    $titulo = trim((string) ($dados['titulo'] ?? ''));
    $descricao = trim((string) ($dados['descricao'] ?? ''));
    $data = (string) ($dados['data_evento'] ?? '');
    $local = trim((string) ($dados['local_evento'] ?? ''));
    $situacao = (string) ($dados['situacao'] ?? '');
    if ($titulo === '') $erros[] = 'Informe o título.';
    elseif (mb_strlen($titulo) > 120) $erros[] = 'O título deve ter no máximo 120 caracteres.';
    if ($descricao === '') $erros[] = 'Informe uma descrição.';
    elseif (mb_strlen($descricao) > LIMITE_DESCRICAO) $erros[] = 'A descrição deve ter no máximo 5000 caracteres.';
    $dataObj = DateTimeImmutable::createFromFormat('!Y-m-d', $data);
    if (!$dataObj || $dataObj->format('Y-m-d') !== $data) $erros[] = 'Informe uma data válida.';
    if ($local === '') $erros[] = 'Informe o local.';
    elseif (mb_strlen($local) > 120) $erros[] = 'O local deve ter no máximo 120 caracteres.';
    if (!in_array($situacao, situacoes_evento(), true)) $erros[] = 'Situação inválida.';
    return $erros;
}

function id_positivo(mixed $valor): ?int
{
    if (is_int($valor)) return $valor > 0 ? $valor : null;
    if (!is_string($valor) || !preg_match('/^[1-9][0-9]*$/D', $valor)) return null;
    $id = filter_var($valor, FILTER_VALIDATE_INT);
    return is_int($id) && $id > 0 ? $id : null;
}

function csrf_token(): string
{
    if (!isset($_SESSION['csrf_token']) || !is_string($_SESSION['csrf_token']) || strlen($_SESSION['csrf_token']) !== 64) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function validar_csrf(mixed $token): bool
{
    return is_string($token) && isset($_SESSION['csrf_token']) && is_string($_SESSION['csrf_token'])
        && hash_equals($_SESSION['csrf_token'], $token);
}

function exigir_csrf(mixed $token): void
{
    if (!validar_csrf($token)) {
        http_response_code(403);
        exit('Requisição recusada. Atualize a página e tente novamente.');
    }
}

function validar_upload(array $arquivo, ?callable $verificarUpload = null): array
{
    $resultado = ['erros' => [], 'mime' => null];
    foreach (['name', 'tmp_name', 'error', 'size'] as $campo) {
        if (!array_key_exists($campo, $arquivo) || !is_scalar($arquivo[$campo])) {
            $resultado['erros'][] = 'Estrutura de upload inválida.';
            return $resultado;
        }
    }
    $erro = filter_var($arquivo['error'], FILTER_VALIDATE_INT);
    $tamanho = filter_var($arquivo['size'], FILTER_VALIDATE_INT);
    $temporario = (string) $arquivo['tmp_name'];
    $nome = (string) $arquivo['name'];
    if ($erro !== UPLOAD_ERR_OK) { $resultado['erros'][] = 'Falha ao receber o cartaz.'; return $resultado; }
    $verificarUpload ??= 'is_uploaded_file';
    if (!$verificarUpload($temporario)) { $resultado['erros'][] = 'O arquivo recebido não é um upload HTTP válido.'; return $resultado; }
    if (!is_int($tamanho) || $tamanho < 1 || $tamanho > LIMITE_UPLOAD) { $resultado['erros'][] = 'O cartaz deve ter no máximo 2 MB.'; return $resultado; }
    $extensao = strtolower(pathinfo($nome, PATHINFO_EXTENSION));
    $permitidos = ['pdf'=>'application/pdf', 'png'=>'image/png', 'jpg'=>'image/jpeg', 'jpeg'=>'image/jpeg'];
    if (!isset($permitidos[$extensao])) { $resultado['erros'][] = 'Envie um arquivo PDF, PNG, JPG ou JPEG.'; return $resultado; }
    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($temporario);
    if (!is_string($mime) || $mime !== $permitidos[$extensao]) { $resultado['erros'][] = 'O conteúdo do arquivo não corresponde ao formato permitido.'; return $resultado; }
    if (str_starts_with($mime, 'image/')) {
        $dimensoes = @getimagesize($temporario);
        if ($dimensoes === false || $dimensoes[0] < 1 || $dimensoes[1] < 1 || $dimensoes[0] > 6000 || $dimensoes[1] > 6000) {
            $resultado['erros'][] = 'A imagem deve ter dimensões de até 6000 × 6000 pixels.';
            return $resultado;
        }
    }
    $resultado['mime'] = $mime;
    return $resultado;
}

function criar_nome_upload(): string { return bin2hex(random_bytes(16)) . '.bin'; }

function garantir_diretorio_uploads(string $diretorio): bool
{
    if (!is_dir($diretorio) && !@mkdir($diretorio, 0775, true) && !is_dir($diretorio)) return false;
    return is_writable($diretorio);
}

function cartaz_eh_imagem(?string $mime): bool { return is_string($mime) && str_starts_with($mime, 'image/'); }
function escapar(mixed $valor): string { return htmlspecialchars(is_scalar($valor) ? (string) $valor : '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }

function detectar_base_url(string $scriptName): string
{
    $diretorio = rtrim(str_replace('\\', '/', dirname($scriptName)), '/');
    if (str_ends_with($diretorio, '/eventos')) $diretorio = substr($diretorio, 0, -strlen('/eventos'));
    return $diretorio === '.' || $diretorio === '/' ? '' : $diretorio;
}

function validar_configuracao(array $config): void
{
    foreach (['usuario'=>'SEU_USUARIO', 'senha'=>'SUA_SENHA', 'banco'=>'SEU_BANCO'] as $campo => $placeholder) {
        if (!isset($config[$campo]) || $config[$campo] === '' || $config[$campo] === $placeholder) {
            throw new RuntimeException('Configure o banco em includes/config.php antes de usar a aplicação.');
        }
    }
}

function url(string $caminho = ''): string { global $config; return rtrim((string) $config['base_url'], '/') . '/' . ltrim($caminho, '/'); }

function cabecalhos_seguranca(): array
{
    return [
        "Content-Security-Policy: default-src 'self'; script-src 'self'; style-src 'self'; img-src 'self'; base-uri 'self'; form-action 'self'; frame-ancestors 'none'; object-src 'none'",
        'X-Content-Type-Options: nosniff', 'Referrer-Policy: strict-origin-when-cross-origin',
        'X-Frame-Options: DENY', 'Cache-Control: no-store',
    ];
}
function enviar_cabecalhos_seguranca(): void { foreach (cabecalhos_seguranca() as $cabecalho) header($cabecalho); }

function sessao_expirada(int $ultimaAtividade, int $agora): bool
{
    return $agora - $ultimaAtividade > 1800;
}

function encerrar_sessao(): void
{
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', [
            'expires' => time() - 42000,
            'path' => $p['path'],
            'domain' => $p['domain'],
            'secure' => $p['secure'],
            'httponly' => $p['httponly'],
            'samesite' => $p['samesite'] ?? 'Lax',
        ]);
    }
    session_destroy();
}

function definir_flash(string $tipo, string $mensagem): void { $_SESSION['flash'] = ['tipo'=>$tipo, 'mensagem'=>$mensagem]; }
function consumir_flash(): ?array { $flash = $_SESSION['flash'] ?? null; unset($_SESSION['flash']); return is_array($flash) ? $flash : null; }
function classe_situacao(string $situacao): string { return match ($situacao) { 'Publicado'=>'success', 'Finalizado'=>'neutral', default=>'warning' }; }
