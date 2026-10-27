<?php
declare(strict_types=1);
require __DIR__ . '/../includes/bootstrap.php'; require __DIR__ . '/../includes/autenticacao.php'; exigir_login();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); header('Allow: POST'); exit('Método não permitido.'); }
exigir_csrf($_POST['csrf_token'] ?? null);
$idBruto = $_POST['id'] ?? ''; $id = $idBruto === '' ? null : id_positivo($idBruto);
if ($idBruto !== '' && $id === null) { http_response_code(404); exit('Evento não encontrado.'); }
$atual = $id ? buscar_evento($mysqli, $id, usuario_id()) : null;
if ($id && !$atual) { http_response_code(404); exit('Evento não encontrado.'); }
$dados = [
 'titulo'=>trim(is_string($_POST['titulo']??null)?$_POST['titulo']:''),
 'descricao'=>trim(is_string($_POST['descricao']??null)?$_POST['descricao']:''),
 'data_evento'=>is_string($_POST['data_evento']??null)?$_POST['data_evento']:'',
 'local_evento'=>trim(is_string($_POST['local_evento']??null)?$_POST['local_evento']:''),
 'situacao'=>is_string($_POST['situacao']??null)?$_POST['situacao']:'',
 'cartaz'=>$atual['cartaz']??null, 'cartaz_mime'=>$atual['cartaz_mime']??null,
];
$erros = validar_evento($dados); $arquivo = $_FILES['cartaz'] ?? null; $novoNome=null; $destinoNovo=null;
$diretorioUploads = __DIR__ . '/../uploads';
if ($arquivo !== null) {
    if (!is_array($arquivo)) $erros[]='Estrutura de upload inválida.';
    else {
        $codigo = isset($arquivo['error']) && is_scalar($arquivo['error']) ? (int)$arquivo['error'] : -1;
        if ($codigo !== UPLOAD_ERR_NO_FILE) {
            $validacao = validar_upload($arquivo); $erros = array_merge($erros, $validacao['erros']);
            if (!$validacao['erros']) {
                if (!garantir_diretorio_uploads($diretorioUploads)) $erros[]='A pasta uploads não permite gravação pelo PHP.';
                else { $novoNome=criar_nome_upload(); $destinoNovo=$diretorioUploads.'/'.$novoNome; $dados['cartaz']=$novoNome; $dados['cartaz_mime']=$validacao['mime']; }
            }
        }
    }
}
if ($erros) {
    $_SESSION['erros_formulario']=$erros; $_SESSION['dados_formulario']=$dados;
    header('Location: '.url('/eventos/formulario.php'.($id?'?id='.$id:''))); exit;
}
$movido=false; $antigoOriginal=null; $antigoTemporario=null;
try {
    if ($novoNome !== null) {
        if (!move_uploaded_file((string)$arquivo['tmp_name'], (string)$destinoNovo)) throw new RuntimeException('Falha ao mover upload.');
        $movido=true;
        if (!empty($atual['cartaz']) && preg_match('/^[a-f0-9]{32}\.bin$/D',(string)$atual['cartaz'])) {
            $antigoOriginal=$diretorioUploads.'/'.$atual['cartaz'];
            if (is_file($antigoOriginal)) {
                $antigoTemporario=$antigoOriginal.'.remover-'.bin2hex(random_bytes(4));
                if (!rename($antigoOriginal,$antigoTemporario)) throw new RuntimeException('Falha ao preparar substituição do cartaz.');
            }
        }
    }
    $mysqli->begin_transaction();
    if ($id) atualizar_evento($mysqli,$id,usuario_id(),$dados); else criar_evento($mysqli,usuario_id(),$dados);
    $mysqli->commit();
    if ($antigoTemporario && is_file($antigoTemporario) && !unlink($antigoTemporario)) error_log('Falha controlada ao limpar cartaz substituído.');
    definir_flash('success',$id?'Evento atualizado com sucesso.':'Evento cadastrado com sucesso.');
} catch (Throwable $erro) {
    try { $mysqli->rollback(); } catch (Throwable) {}
    if ($antigoTemporario && is_file($antigoTemporario) && $antigoOriginal) @rename($antigoTemporario,$antigoOriginal);
    if ($movido && is_file((string)$destinoNovo)) @unlink((string)$destinoNovo);
    error_log('Falha controlada ao salvar evento.');
    $_SESSION['erros_formulario']=['Não foi possível salvar o evento. Tente novamente.']; $_SESSION['dados_formulario']=$dados;
    header('Location: '.url('/eventos/formulario.php'.($id?'?id='.$id:''))); exit;
}
header('Location: '.url('/painel.php')); exit;
