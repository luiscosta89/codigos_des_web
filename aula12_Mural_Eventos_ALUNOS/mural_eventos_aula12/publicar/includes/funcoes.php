<?php
declare(strict_types=1);

function situacoes_evento(): array
{
    return ['Rascunho', 'Publicado', 'Finalizado'];
}

function validar_evento(array $dados): array
{
    $erros = [];
    $titulo = trim((string) ($dados['titulo'] ?? ''));
    $descricao = trim((string) ($dados['descricao'] ?? ''));
    $data = (string) ($dados['data_evento'] ?? '');
    $local = trim((string) ($dados['local_evento'] ?? ''));
    $situacao = (string) ($dados['situacao'] ?? '');

    if ($titulo === '') {
        $erros[] = 'Informe o título.';
    } elseif (mb_strlen($titulo) > 120) {
        $erros[] = 'O título deve ter no máximo 120 caracteres.';
    }
    if ($descricao === '') {
        $erros[] = 'Informe uma descrição.';
    }

    $dataObj = DateTimeImmutable::createFromFormat('!Y-m-d', $data);
    if (!$dataObj || $dataObj->format('Y-m-d') !== $data) {
        $erros[] = 'Informe uma data válida.';
    }
    if ($local === '') {
        $erros[] = 'Informe o local.';
    } elseif (mb_strlen($local) > 120) {
        $erros[] = 'O local deve ter no máximo 120 caracteres.';
    }
    if (!in_array($situacao, situacoes_evento(), true)) {
        $erros[] = 'Situação inválida.';
    }

    return $erros;
}

function extensao_upload(string $nomeOriginal): ?string
{
    $extensao = strtolower(pathinfo($nomeOriginal, PATHINFO_EXTENSION));
    return in_array($extensao, ['pdf', 'png', 'jpg', 'jpeg'], true) ? $extensao : null;
}

function criar_nome_upload(string $nomeOriginal, string $token): ?string
{
    $extensao = extensao_upload($nomeOriginal);
    return $extensao === null ? null : $token . '.' . $extensao;
}

function mime_upload_permitido(string $caminhoTemporario, string $extensao): bool
{
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($caminhoTemporario);
    if ($extensao === 'pdf') {
        return $mime === 'application/pdf';
    }
    if (in_array($extensao, ['jpg', 'jpeg'], true)) {
        return $mime === 'image/jpeg' && @getimagesize($caminhoTemporario) !== false;
    }
    if ($extensao === 'png') {
        return $mime === 'image/png' && @getimagesize($caminhoTemporario) !== false;
    }
    return false;
}

function garantir_diretorio_uploads(string $diretorio): bool
{
    if (!is_dir($diretorio) && !@mkdir($diretorio, 0775, true) && !is_dir($diretorio)) {
        return false;
    }
    return is_writable($diretorio);
}

function cartaz_eh_imagem(?string $nomeArquivo): bool
{
    if (!$nomeArquivo) {
        return false;
    }
    return in_array(strtolower(pathinfo($nomeArquivo, PATHINFO_EXTENSION)), ['png', 'jpg', 'jpeg'], true);
}

function escapar(?string $valor): string
{
    return htmlspecialchars($valor ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function url(string $caminho = ''): string
{
    global $config;
    return $config['base_url'] . '/' . ltrim($caminho, '/');
}

function definir_flash(string $tipo, string $mensagem): void
{
    $_SESSION['flash'] = ['tipo' => $tipo, 'mensagem' => $mensagem];
}

function consumir_flash(): ?array
{
    $flash = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $flash;
}

function classe_situacao(string $situacao): string
{
    return match ($situacao) {
        'Publicado' => 'success',
        'Finalizado' => 'neutral',
        default => 'warning',
    };
}
