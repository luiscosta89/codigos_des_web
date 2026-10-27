<?php
declare(strict_types=1);
require __DIR__ . '/includes/bootstrap.php'; require __DIR__ . '/includes/autenticacao.php'; exigir_login();
if ($_SERVER['REQUEST_METHOD'] !== 'GET') { http_response_code(405); header('Allow: GET'); exit('Método não permitido.'); }
$id=id_positivo($_GET['id']??null); $evento=$id?buscar_evento($mysqli,$id,usuario_id()):null;
if(!$evento || !$evento['cartaz'] || !in_array($evento['cartaz_mime'],['image/png','image/jpeg','application/pdf'],true)
   || !preg_match('/^[a-f0-9]{32}\.bin$/D',(string)$evento['cartaz'])) { http_response_code(404); exit('Cartaz não encontrado.'); }
$arquivo=__DIR__.'/uploads/'.$evento['cartaz']; if(!is_file($arquivo)){http_response_code(404);exit('Cartaz não encontrado.');}
header('Content-Type: '.$evento['cartaz_mime']); header('Content-Length: '.filesize($arquivo));
if($evento['cartaz_mime']==='application/pdf') header('Content-Disposition: attachment; filename="cartaz-evento-'.$id.'.pdf"');
else header('Content-Disposition: inline; filename="cartaz-evento-'.$id.($evento['cartaz_mime']==='image/png'?'.png':'.jpg').'"');
readfile($arquivo); exit;
