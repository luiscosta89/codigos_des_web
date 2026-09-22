<?php
declare(strict_types=1);

require __DIR__ . '/includes/funcoes.php';
require __DIR__ . '/includes/repositorio_produtos.php';

$busca = normalizarBusca($_GET['busca'] ?? null);
$produtos = [];
$erro = null;

try {
    require __DIR__ . '/includes/conexao.php';
    $produtos = buscarProdutos($mysqli, $busca);
} catch (Throwable $excecao) {
    $erro = $excecao->getMessage();
}
?>
<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Catálogo TechStore</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<header class="topo">
  <div class="container topo-conteudo">
    <a class="marca" href="./">Tech<span>Store</span></a>
    <span class="aula">PHP + MariaDB</span>
  </div>
</header>

<main class="container">
  <section class="apresentacao">
    <div>
      <p class="etiqueta">PROJETO DA AULA 8</p>
      <h1>Catálogo TechStore</h1>
      <p>Produtos carregados do MariaDB por uma aplicação PHP simples.</p>
    </div>
    <div class="fluxo" aria-label="Fluxo da aplicação">
      <span>GET</span><b>→</b><span>PHP</span><b>→</b><span>MySQLi</span><b>→</b><span>SQL</span>
    </div>
  </section>

  <form class="busca" method="get" action="index.php">
    <label for="busca">Buscar por produto ou categoria</label>
    <div class="busca-controles">
      <input id="busca" name="busca" maxlength="80" value="<?= escapar($busca) ?>" placeholder="Ex.: mouse ou áudio">
      <button type="submit">Buscar</button>
      <?php if ($busca !== ''): ?>
        <a href="index.php">Limpar</a>
      <?php endif; ?>
    </div>
  </form>

  <?php if ($erro !== null): ?>
    <section class="mensagem erro">
      <h2>Configuração pendente</h2>
      <p><?= escapar($erro) ?></p>
      <ol>
        <li>Importe os arquivos da pasta <strong>banco/</strong> pelo phpMyAdmin.</li>
        <li>Edite <strong>publicar/config.php</strong> com seus dados.</li>
        <li>Atualize esta página.</li>
      </ol>
    </section>
  <?php else: ?>
    <div class="resultado-cabecalho">
      <h2><?= $busca === '' ? 'Todos os produtos' : 'Resultado para “' . escapar($busca) . '”' ?></h2>
      <span><?= count($produtos) ?> produto(s)</span>
    </div>

    <?php if ($produtos === []): ?>
      <section class="mensagem"><p>Nenhum produto encontrado.</p></section>
    <?php else: ?>
      <section class="grade-produtos">
        <?php foreach ($produtos as $produto): ?>
          <article class="produto">
            <div class="produto-topo">
              <span class="categoria"><?= escapar((string) $produto['categoria']) ?></span>
              <span class="estoque estoque-<?= (int) $produto['estoque'] === 0 ? 'zero' : 'ok' ?>">
                <?= escapar(statusEstoque((int) $produto['estoque'])) ?>
              </span>
            </div>
            <h3><?= escapar((string) $produto['nome']) ?></h3>
            <p><?= escapar((string) $produto['descricao']) ?></p>
            <div class="produto-rodape">
              <strong><?= formatarPreco((string) $produto['preco']) ?></strong>
              <small><?= (int) $produto['estoque'] ?> em estoque</small>
            </div>
          </article>
        <?php endforeach; ?>
      </section>
    <?php endif; ?>
  <?php endif; ?>
</main>

<footer><div class="container">TechStore didática · consulta somente leitura</div></footer>
</body>
</html>
