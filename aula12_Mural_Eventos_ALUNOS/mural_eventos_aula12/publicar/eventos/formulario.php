<?php
declare(strict_types=1);
require __DIR__ . '/../includes/bootstrap.php';
require __DIR__ . '/../includes/autenticacao.php';
exigir_login();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?: 0;
$evento = $id ? buscar_evento($mysqli, $id) : null;
if ($id && !$evento) {
    http_response_code(404);
    exit('Evento não encontrado.');
}
$evento ??= [
    'id' => '', 'titulo' => '', 'descricao' => '', 'data_evento' => '',
    'local_evento' => '', 'situacao' => 'Rascunho', 'cartaz' => null,
];
$erros = $_SESSION['erros_formulario'] ?? [];
$antigos = $_SESSION['dados_formulario'] ?? [];
unset($_SESSION['erros_formulario'], $_SESSION['dados_formulario']);
$evento = array_merge($evento, $antigos);
$tituloPagina = $id ? 'Editar evento' : 'Novo evento';
require __DIR__ . '/../includes/cabecalho.php';
?>
<section class="page-heading">
  <div><span class="eyebrow">CREATE / UPDATE</span><h1><?= escapar($tituloPagina) ?></h1></div>
  <a class="btn ghost" href="<?= escapar(url('/painel.php')) ?>">Voltar</a>
</section>

<?php if ($erros): ?>
<div class="alert error">
  <strong>Revise o formulário:</strong>
  <ul><?php foreach ($erros as $erro): ?><li><?= escapar($erro) ?></li><?php endforeach; ?></ul>
  <p><strong>Importante:</strong> se você havia escolhido um cartaz, selecione o arquivo novamente antes de salvar.</p>
</div>
<?php endif; ?>

<form class="event-form" method="post" enctype="multipart/form-data" action="<?= escapar(url('/eventos/salvar.php')) ?>">
  <input type="hidden" name="id" value="<?= escapar((string) $evento['id']) ?>">
  <div class="full"><label>Título<input name="titulo" maxlength="120" required value="<?= escapar($evento['titulo']) ?>"></label></div>
  <div class="full"><label>Descrição<textarea name="descricao" rows="5" required><?= escapar($evento['descricao']) ?></textarea></label></div>
  <label>Data<input type="date" name="data_evento" required value="<?= escapar($evento['data_evento']) ?>"></label>
  <label>Local<input name="local_evento" maxlength="120" required value="<?= escapar($evento['local_evento']) ?>"></label>
  <label>Situação<select name="situacao"><?php foreach (situacoes_evento() as $situacao): ?><option value="<?= escapar($situacao) ?>" <?= $evento['situacao'] === $situacao ? 'selected' : '' ?>><?= escapar($situacao) ?></option><?php endforeach; ?></select></label>
  <label>Cartaz<input type="file" name="cartaz" accept=".pdf,.png,.jpg,.jpeg"><small>PDF, PNG ou JPG; máximo de 2 MB.</small></label>
  <?php if ($evento['cartaz']): ?><div class="full current-file">Arquivo atual: <a target="_blank" href="<?= escapar(url('/uploads/' . rawurlencode($evento['cartaz']))) ?>">Abrir cartaz</a></div><?php endif; ?>
  <div class="full form-actions"><button class="btn primary">Salvar evento</button><a class="btn ghost" href="<?= escapar(url('/painel.php')) ?>">Cancelar</a></div>
</form>
<?php require __DIR__ . '/../includes/rodape.php'; ?>
