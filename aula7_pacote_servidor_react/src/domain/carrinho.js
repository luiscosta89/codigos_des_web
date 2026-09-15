export function adicionarAoCarrinho(itensAtuais, produto) {
  return [...itensAtuais, produto];
}

export function removerDoCarrinho(itensAtuais, indiceSelecionado) {
  return itensAtuais.filter((_, indice) => indice !== indiceSelecionado);
}

export function calcularTotal(itens) {
  return itens.reduce((total, item) => total + item.preco, 0);
}
