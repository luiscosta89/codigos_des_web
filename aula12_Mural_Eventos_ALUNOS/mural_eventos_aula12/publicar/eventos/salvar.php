<?php
declare(strict_types=1);
require __DIR__ . '/../includes/bootstrap.php';
require __DIR__ . '/../includes/autenticacao.php';
exigir_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Método não permitido.');
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT) ?: 0;
$dados = [
    'titulo' => trim((string) ($_POST['titulo'] ?? '')),
    'descricao' => trim((string) ($_POST['descricao'] ?? '')),
    'data_evento' => (string) ($_POST['data_evento'] ?? ''),
    'local_evento' => trim((string) ($_POST['local_evento'] ?? '')),
    'situacao' => (string) ($_POST['situacao'] ?? ''),
    'cartaz' => null,
];
$atual = $id ? buscar_evento($mysqli, $id) : null;
if ($id && !$atual) {
    http_response_code(404);
    exit('Evento não encontrado.');
}
$dados['cartaz'] = $atual['cartaz'] ?? null;
$erros = validar_evento($dados);

$arquivo = $_FILES['cartaz'] ?? null;
$novoNome = null;
$destinoNovo = null;
$diretorioUploads = __DIR__ . '/../uploads';
if ($arquivo && $arquivo['error'] !== UPLOAD_ERR_NO_FILE) {
    if ($arquivo['error'] !== UPLOAD_ERR_OK) {
        $erros[] = 'Falha ao receber o cartaz. Código do upload: ' . $arquivo['error'] . '.';
    } elseif ($arquivo['size'] > 2_000_000) {
        $erros[] = 'O cartaz deve ter no máximo 2 MB.';
    } else {
        $extensao = extensao_upload($arquivo['name']);
        if ($extensao === null) {
            $erros[] = 'Envie um arquivo PDF, PNG, JPG ou JPEG.';
        } elseif (!mime_upload_permitido($arquivo['tmp_name'], $extensao)) {
            $erros[] = 'O conteúdo do arquivo não corresponde ao formato permitido.';
        } elseif (!garantir_diretorio_uploads($diretorioUploads)) {
            $erros[] = 'A pasta uploads não existe ou não permite gravação pelo PHP.';
        } else {
            $novoNome = criar_nome_upload($arquivo['name'], bin2hex(random_bytes(8)));
            $destinoNovo = $diretorioUploads . '/' . $novoNome;
        }
    }
}

if ($erros) {
    $_SESSION['erros_formulario'] = $erros;
    $_SESSION['dados_formulario'] = $dados;
    header('Location: ' . url('/eventos/formulario.php' . ($id ? '?id=' . $id : '')));
    exit;
}

$arquivoMovido = false;
try {
    if ($novoNome !== null) {
        if (!move_uploaded_file($arquivo['tmp_name'], $destinoNovo)) {
            throw new RuntimeException('Não foi possível salvar o cartaz.');
        }
        $arquivoMovido = true;
        $dados['cartaz'] = $novoNome;
    }

    if ($id) {
        atualizar_evento($mysqli, $id, $dados);
        if ($novoNome !== null && !empty($atual['cartaz']) && $atual['cartaz'] !== $novoNome) {
            $cartazAntigo = __DIR__ . '/../uploads/' . basename($atual['cartaz']);
            if (is_file($cartazAntigo)) {
                unlink($cartazAntigo);
            }
        }
        definir_flash('success', 'Evento atualizado com sucesso.');
    } else {
        criar_evento($mysqli, $dados);
        definir_flash('success', 'Evento cadastrado com sucesso.');
    }
} catch (Throwable $erro) {
    if ($arquivoMovido && is_file($destinoNovo)) {
        unlink($destinoNovo);
    }
    error_log('Falha ao salvar evento: ' . $erro->getMessage());
    $_SESSION['erros_formulario'] = ['Não foi possível salvar o evento. Tente novamente.'];
    $_SESSION['dados_formulario'] = $dados;
    header('Location: ' . url('/eventos/formulario.php' . ($id ? '?id=' . $id : '')));
    exit;
}

header('Location: ' . url('/painel.php'));
exit;
