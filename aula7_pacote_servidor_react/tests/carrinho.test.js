import { describe, expect, it } from 'vitest';
import { adicionarAoCarrinho, calcularTotal, removerDoCarrinho } from '../src/domain/carrinho.js';

describe('carrinho', () => {
  it('adiciona um produto sem alterar o array original', () => {
    const original = [{ id: 1, nome: 'Teclado', preco: 199.9 }];
    const mouse = { id: 2, nome: 'Mouse', preco: 89.9 };
    const atualizado = adicionarAoCarrinho(original, mouse);

    expect(atualizado).toEqual([...original, mouse]);
    expect(atualizado).not.toBe(original);
    expect(original).toHaveLength(1);
  });

  it('calcula o total dos itens', () => {
    expect(calcularTotal([
      { id: 1, preco: 199.9 },
      { id: 2, preco: 89.9 },
    ])).toBeCloseTo(289.8);
  });

  it('remove somente a ocorrência selecionada', () => {
    const teclado = { id: 1, nome: 'Teclado', preco: 199.9 };
    const itens = [teclado, teclado, { id: 2, nome: 'Mouse', preco: 89.9 }];
    expect(removerDoCarrinho(itens, 0)).toEqual([teclado, itens[2]]);
  });
});
