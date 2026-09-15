function normalizar(texto) {
  return texto
    .normalize('NFD')
    .replace(/[\u0300-\u036f]/g, '')
    .toLocaleLowerCase('pt-BR');
}

export function filtrarProdutos(produtos, busca) {
  const termo = normalizar(busca.trim());
  if (!termo) return produtos;
  return produtos.filter(({ nome }) => normalizar(nome).includes(termo));
}

export function reordenarProdutos(produtos) {
  if (produtos.length < 2) return [...produtos];
  return [produtos.at(-1), ...produtos.slice(0, -1)];
}
