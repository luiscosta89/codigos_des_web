import { describe, expect, it } from 'vitest';
import { filtrarProdutos, reordenarProdutos } from '../src/domain/produtos.js';

const produtos = [
  { id: 1, nome: 'Teclado Mecânico', preco: 199.9 },
  { id: 2, nome: 'Mouse Sem Fio', preco: 89.9 },
  { id: 3, nome: 'Headset Gamer', preco: 249.9 },
];

describe('produtos', () => {
  it('filtra por nome sem diferenciar maiúsculas e acentos', () => {
    expect(filtrarProdutos(produtos, 'mecanico')).toEqual([produtos[0]]);
  });

  it('mantém todos os produtos quando a busca está vazia', () => {
    expect(filtrarProdutos(produtos, '')).toEqual(produtos);
  });

  it('reordena sem alterar os produtos nem seus identificadores', () => {
    const reordenados = reordenarProdutos(produtos);
    expect(reordenados.map(({ id }) => id)).toEqual([3, 1, 2]);
    expect(produtos.map(({ id }) => id)).toEqual([1, 2, 3]);
  });
});
