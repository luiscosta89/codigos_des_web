<?php
declare(strict_types=1);
require __DIR__ . '/../includes/bootstrap.php';
require __DIR__ . '/../includes/autenticacao.php';
exigir_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Método não permitido.');
}
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$evento = $id ? buscar_evento($mysqli, (int) $id) : null;
if (!$evento) {
    http_response_code(404);
    exit('Evento não encontrado.');
}
excluir_evento($mysqli, (int) $id);
if ($evento['cartaz']) {
    $arquivo = __DIR__ . '/../uploads/' . basename($evento['cartaz']);
    if (is_file($arquivo)) {
        unlink($arquivo);
    }
}
definir_flash('success', 'Evento excluído.');
header('Location: ' . url('/painel.php'));
exit;
