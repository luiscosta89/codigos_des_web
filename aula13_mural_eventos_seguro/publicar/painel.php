<?php
declare(strict_types=1);
require __DIR__ . '/includes/bootstrap.php';
require __DIR__ . '/includes/autenticacao.php';
exigir_login();
$busca = is_string($_GET['busca'] ?? null) ? trim($_GET['busca']) : '';
if (mb_strlen($busca) > 120) $busca = mb_substr($busca, 0, 120);
$eventos = listar_eventos($mysqli, usuario_id(), $busca);
$flash = consumir_flash(); $tituloPagina = 'Painel de eventos';
require __DIR__ . '/includes/cabecalho.php';
?>
<section class="hero"><div><span class="eyebrow">AULA 13 · CRUD PROTEGIDO</span><h1>Painel de eventos</h1>
<p>Você visualiza e altera somente os eventos da sua conta.</p></div>
<a class="btn primary" href="<?= escapar(url('/eventos/formulario.php')) ?>">+ Novo evento</a></section>
<?php if ($flash): ?><div class="alert <?= escapar($flash['tipo']) ?>"><?= escapar($flash['mensagem']) ?></div><?php endif; ?>
<form method="get" class="event-form search-form"><label class="full">Buscar nos meus eventos
<input name="busca" maxlength="120" value="<?= escapar($busca) ?>" placeholder="Título, descrição ou local"></label>
<div class="full form-actions"><button class="btn primary" type="submit">Buscar</button><a class="btn ghost" href="<?= escapar(url('/painel.php')) ?>">Limpar</a></div></form>
<section class="stats"><article><strong><?= count($eventos) ?></strong><span>eventos encontrados</span></article>
<article><strong><?= count(array_filter($eventos, fn($e) => $e['situacao'] === 'Publicado')) ?></strong><span>publicados</span></article>
<article><strong><?= count(array_filter($eventos, fn($e) => $e['cartaz'])) ?></strong><span>com cartaz</span></article></section>
<section class="event-grid">
<?php foreach ($eventos as $evento): ?>
<article class="event-card"><div class="event-top"><span class="badge <?= escapar(classe_situacao($evento['situacao'])) ?>"><?= escapar($evento['situacao']) ?></span>
<time datetime="<?= escapar($evento['data_evento']) ?>"><?= escapar(date('d/m/Y', strtotime($evento['data_evento']))) ?></time></div>
<h2><?= escapar($evento['titulo']) ?></h2><p><?= escapar($evento['descricao']) ?></p>
<?php if ($evento['cartaz'] && cartaz_eh_imagem($evento['cartaz_mime'])): ?>
<a class="cartaz-preview" href="<?= escapar(url('/cartaz.php?id=' . $evento['id'])) ?>" target="_blank" rel="noopener noreferrer" title="Abrir cartaz">
<img src="<?= escapar(url('/cartaz.php?id=' . $evento['id'])) ?>" alt="Cartaz do evento <?= escapar($evento['titulo']) ?>"></a><?php endif; ?>
<div class="location">📍 <?= escapar($evento['local_evento']) ?></div><div class="card-actions">
<?php if ($evento['cartaz']): ?><a href="<?= escapar(url('/cartaz.php?id=' . $evento['id'])) ?>" target="_blank" rel="noopener noreferrer"><?= cartaz_eh_imagem($evento['cartaz_mime']) ? 'Ampliar cartaz' : 'Baixar cartaz PDF' ?></a><?php endif; ?>
<a href="<?= escapar(url('/eventos/formulario.php?id=' . $evento['id'])) ?>">Editar</a>
<form method="post" action="<?= escapar(url('/eventos/excluir.php')) ?>" data-confirm="Excluir este evento?"><input type="hidden" name="csrf_token" value="<?= escapar(csrf_token()) ?>"><input type="hidden" name="id" value="<?= (int)$evento['id'] ?>"><button class="link danger" type="submit">Excluir</button></form>
</div></article><?php endforeach; ?></section>
<?php if (!$eventos): ?><div class="empty">Nenhum evento encontrado.</div><?php endif; ?>
<?php require __DIR__ . '/includes/rodape.php'; ?>
