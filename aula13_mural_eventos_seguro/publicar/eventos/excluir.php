<?php
declare(strict_types=1);
require __DIR__ . '/../includes/bootstrap.php'; require __DIR__ . '/../includes/autenticacao.php'; exigir_login();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); header('Allow: POST'); exit('Método não permitido.'); }
exigir_csrf($_POST['csrf_token'] ?? null);
$id=id_positivo($_POST['id']??null); $evento=$id?buscar_evento($mysqli,$id,usuario_id()):null;
if(!$evento){http_response_code(404);exit('Evento não encontrado.');}
$arquivo=null; $temporario=null;
if($evento['cartaz'] && preg_match('/^[a-f0-9]{32}\.bin$/D',(string)$evento['cartaz'])) $arquivo=__DIR__.'/../uploads/'.$evento['cartaz'];
$mysqli->begin_transaction();
try {
    if($arquivo && is_file($arquivo)) {
        $temporario=$arquivo.'.remover-'.bin2hex(random_bytes(4));
        if(!rename($arquivo,$temporario)) throw new RuntimeException('Falha ao preparar remoção do cartaz.');
    }
    if(!excluir_evento($mysqli,$id,usuario_id())) throw new RuntimeException('Evento não encontrado.');
    $mysqli->commit();
    if($temporario && is_file($temporario) && !unlink($temporario)) error_log('Falha controlada ao limpar cartaz excluído.');
} catch(Throwable $erro) {
    $mysqli->rollback();
    if($temporario && is_file($temporario) && $arquivo) @rename($temporario,$arquivo);
    throw $erro;
}
definir_flash('success','Evento excluído.'); header('Location: '.url('/painel.php')); exit;
