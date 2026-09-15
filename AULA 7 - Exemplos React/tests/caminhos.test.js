import { describe, expect, it } from 'vitest';
import { resolverAsset } from '../src/domain/caminhos.js';

describe('caminhos para publicação', () => {
  it('mantém os arquivos dentro da pasta em que o projeto foi publicado', () => {
    expect(
      resolverAsset('produtos.json', 'https://servidor.exemplo/alunos/maria/aula7/'),
    ).toBe('https://servidor.exemplo/alunos/maria/aula7/produtos.json');
  });

  it('resolve o exemplo HTML sem voltar para a raiz do domínio', () => {
    expect(
      resolverAsset('exemplos/dom-imperativo.html', 'https://servidor.exemplo/turma/react/'),
    ).toBe('https://servidor.exemplo/turma/react/exemplos/dom-imperativo.html');
  });
});
