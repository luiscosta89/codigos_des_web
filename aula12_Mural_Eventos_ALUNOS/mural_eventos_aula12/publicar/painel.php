<?php
declare(strict_types=1);
require __DIR__ . '/includes/bootstrap.php';
require __DIR__ . '/includes/autenticacao.php';
exigir_login();

$eventos = listar_eventos($mysqli);
$flash = consumir_flash();
$tituloPagina = 'Painel de eventos';
require __DIR__ . '/includes/cabecalho.php';
?>
<section class="hero">
  <div>
    <span class="eyebrow">CRUD + AUTENTICAÇÃO + UPLOAD</span>
    <h1>Painel de eventos</h1>
    <p>Cadastre atividades acadêmicas e publique um cartaz em PDF ou imagem.</p>
  </div>
  <a class="btn primary" href="<?= escapar(url('/eventos/formulario.php')) ?>">+ Novo evento</a>
</section>

<?php if ($flash): ?><div class="alert <?= escapar($flash['tipo']) ?>"><?= escapar($flash['mensagem']) ?></div><?php endif; ?>

<section class="stats">
  <article><strong><?= count($eventos) ?></strong><span>eventos cadastrados</span></article>
  <article><strong><?= count(array_filter($eventos, fn($e) => $e['situacao'] === 'Publicado')) ?></strong><span>publicados</span></article>
  <article><strong><?= count(array_filter($eventos, fn($e) => $e['cartaz'])) ?></strong><span>com cartaz</span></article>
</section>

<section class="event-grid">
<?php foreach ($eventos as $evento): ?>
  <article class="event-card">
    <div class="event-top">
      <span class="badge <?= escapar(classe_situacao($evento['situacao'])) ?>"><?= escapar($evento['situacao']) ?></span>
      <time datetime="<?= escapar($evento['data_evento']) ?>"><?= escapar(date('d/m/Y', strtotime($evento['data_evento']))) ?></time>
    </div>
    <h2><?= escapar($evento['titulo']) ?></h2>
    <p><?= escapar($evento['descricao']) ?></p>
    <?php if (cartaz_eh_imagem($evento['cartaz'])): ?>
      <a class="cartaz-preview" href="<?= escapar(url('/uploads/' . rawurlencode($evento['cartaz']))) ?>" target="_blank" title="Abrir cartaz em tamanho original">
        <img src="<?= escapar(url('/uploads/' . rawurlencode($evento['cartaz']))) ?>" alt="Cartaz do evento <?= escapar($evento['titulo']) ?>">
      </a>
    <?php endif; ?>
    <div class="location">📍 <?= escapar($evento['local_evento']) ?></div>
    <div class="card-actions">
      <?php if ($evento['cartaz']): ?><a href="<?= escapar(url('/uploads/' . rawurlencode($evento['cartaz']))) ?>" target="_blank"><?= cartaz_eh_imagem($evento['cartaz']) ? 'Ampliar cartaz' : 'Abrir cartaz PDF' ?></a><?php endif; ?>
      <a href="<?= escapar(url('/eventos/formulario.php?id=' . $evento['id'])) ?>">Editar</a>
      <form method="post" action="<?= escapar(url('/eventos/excluir.php')) ?>" data-confirm="Excluir este evento?">
        <input type="hidden" name="id" value="<?= (int) $evento['id'] ?>">
        <button class="link danger" type="submit">Excluir</button>
      </form>
    </div>
  </article>
<?php endforeach; ?>
</section>
<?php if (!$eventos): ?><div class="empty">Nenhum evento cadastrado.</div><?php endif; ?>
<?php require __DIR__ . '/includes/rodape.php'; ?>
