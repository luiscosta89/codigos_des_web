import { afterEach, beforeEach, describe, expect, it, vi } from 'vitest';
import { cleanup, fireEvent, render, screen, waitFor, within } from '@testing-library/react';
import App from '../src/App.jsx';

const produtos = [
  { id: 1, nome: 'Teclado Mecânico', preco: 199.9, estoque: 4, categoria: 'Periféricos', icone: '⌨️' },
  { id: 2, nome: 'Mouse Sem Fio', preco: 89.9, estoque: 8, categoria: 'Periféricos', icone: '🖱️' },
  { id: 3, nome: 'Headset Gamer', preco: 249.9, estoque: 0, categoria: 'Áudio', icone: '🎧' },
];

beforeEach(() => {
  vi.stubGlobal('fetch', vi.fn().mockResolvedValue({
    ok: true,
    json: async () => produtos,
  }));
});

afterEach(() => {
  cleanup();
  vi.unstubAllGlobals();
});

describe('TechStore', () => {
  it('carrega e renderiza produtos vindos do efeito', async () => {
    render(<App />);
    expect(screen.getByText(/carregando produtos/i)).toBeInTheDocument();
    expect(await screen.findByText('Teclado Mecânico')).toBeInTheDocument();
    expect(fetch).toHaveBeenCalledWith('http://localhost:3000/produtos.json');
  });

  it('filtra produtos por um campo controlado', async () => {
    render(<App />);
    await screen.findByText('Teclado Mecânico');
    fireEvent.change(screen.getByLabelText(/buscar produto/i), { target: { value: 'mouse' } });
    expect(screen.getByText('Mouse Sem Fio')).toBeInTheDocument();
    expect(screen.queryByText('Teclado Mecânico')).not.toBeInTheDocument();
  });

  it('adiciona, contabiliza e remove itens usando estado imutável', async () => {
    render(<App />);
    await screen.findByText('Teclado Mecânico');
    const resumo = screen.getByLabelText('Resumo do carrinho');
    expect(within(resumo).getByText(/seu carrinho está vazio/i)).toBeInTheDocument();

    fireEvent.click(screen.getByRole('button', { name: /adicionar teclado mecânico/i }));
    expect(screen.getByLabelText(/itens no carrinho/i)).toHaveTextContent('1');
    expect(screen.getByTestId('cart-total')).toHaveTextContent('R$ 199,90');
    expect(screen.getByRole('status')).toHaveTextContent(/produto adicionado/i);

    fireEvent.click(screen.getByRole('button', { name: /remover teclado mecânico/i }));
    await waitFor(() => expect(within(resumo).getByText(/seu carrinho está vazio/i)).toBeInTheDocument());
  });

  it('mostra indisponibilidade e impede adicionar produto sem estoque', async () => {
    render(<App />);
    await screen.findByText('Headset Gamer');
    const botao = screen.getByRole('button', { name: /headset gamer indisponível/i });
    expect(botao).toBeDisabled();
  });
});
