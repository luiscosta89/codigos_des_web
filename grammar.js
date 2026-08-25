// // Case-sensitivity em ação
// let online = true;
// let Online = false; // Variáveis diferente!

// // O JavaScript ignora espaços em branco
// let a   =   1;
// let b=2;

// // Código legível (Boas práticas)
// let usuarioLogado = true; // Variável com nome descritivo

// // O motor lê as quebras de linha como ';'
// let x = 0 // ; inserido automaticamente
// let y = 1 // ; inserido automaticamente

// // O PERIGO DA OMISSÃO
// let a = 0
// [a, a+1].forEach(console.log)
// // Erro! O JS lê: let a = 0[a, a+1]...

// // Solução Defensiva
// let b = 0;
// [b, b+1].forEach(console.log) // OK

// // Ruim: Explica o óbvio
// let status = false; // define o status como falso

// /*
//   Bom: Explica o contexto/regra de negócio
//   O usuário começa bloqueado (false) até que a
//   API confirme o pagamento no sistema.
// */
// let acessoPermitido = false;

// Declarando o contêiner (variável)
// let dado;

// dado = 42; // O valor é Number.

// dado = 'Agora sou texto!'; // O valor mudou para String! NENHUM ERRO.

// console.log(dado);

//let: Projetado para mudar
// let idade = 25;
// idade = 26; // Permitido (Mutável)

// // const: Projetado para não mudar
// const dataDeNascimento = '1998-01-01';
// dataDeNascimento = '2000-01-01'; // ERRO! (Imutável)

// // Código anterior ao ES6 (2015)
// var sistemaAntigo = 'Legado';
// var contador = 0;

// contador = 1; // Mutável como o let

// // O comportamento mágico do var
// console.log(nome); // undefined (não é erro!)
// var nome = 'Alice';

// // Como o JS secretamente lê o código acima:
// // var nome;
// // console.log(nome);
// // nome = 'Alice';

// // let e const te protegem disso:
// console.log(idade); // ERRO! (não definido)
// let idade = 30;

// let logado = true;

// if (logado) {
//   let segredo = 'Senha123';
//   var publico = 'Promoção!';
// }

// console.log(publico); // OK
// console.log(segredo); // ERRO! (não definido)

// // Os 7 blocos fundamentais:
// 1. Number // 42, 3.14, -0.5, NaN, Infinity
// 2. String // 'Olá', "Mundo", `Template String`
// 3. Boolean // true, false
// 4. Null // null
// 5. Undefined // undefined
// 6. Symbol // Symbol('id')
// 7. BigInt // 9007199254740991n

// // Numbers
// let inteiro = 10;
// let decimal = 3.14;;
// let falha = 'Texto' / 2; // NaN (Not a Number)

// // Strings e Template Literals (moderno)
// let linguagem = 'JavaScript';
// let versao = 6;

// // Concatenação tradicional
// let old = linguagem + ' ES' + versao;

// // Interpolação moderna (Template Literals)
// let moderna = `${linguagem} ES${versao}`;
// console.log(moderna);

// // Booleans (Interruptores de lógica)
// let portaAberta = true;
// let sistemaTravado = false;

// // Symbols (Identificadores únicos)
// let id1 = Symbol('usuario');
// let id2 = Symbol('usuario');

// // Apesar de descritivos iguais, são únicos!
// console.log(id1 === id2);

// // Undefined (O JS diz: "Não sei o que é isso!")
// let usuario;
// console.log(usuario); // undefined

// // Null (O programador diz: "Não há valor aqui!")
// let valor = null;
// console.log(valor); // null

// // Cuidado com o bug histórico do JS:
// console.log(typeof undefined); // undefined
// console.log(typeof null); // object (bug histórico)

// // Usando o typeof para verificar o tipo de dado
// console.log(typeof 42); // number
// console.log(typeof 'Olá'); // string
// console.log(typeof true); // boolean
// console.log(typeof Symbol('id')); // symbol

// // Útil para validações de segurança:
// let idade = '25';
// if (typeof idade === 'string') {
//   console.log('Atenção: a idade é uma string!');
// }

// // Aritmética Básica
// let x = 10 % 3; // Resto da divisão (modulus)
// let y = 2 ** 3; // Exponenciação (potência)

// // Operadores de Atribuição (Atalhos)
// let pontos = 50;
// pontos += 10; // pontos = pontos + 10
// pontos -= 5;  // pontos = pontos - 5
// pontos++; // pontos = pontos + 1

// // O Perigo da Comparação Fraca (==)
// // O JS tenta converter os tipos (Coerção)
// console.log('1' == 1); // true (string e number são iguais após coerção)
// console.log('0' === false); // false (string e number são diferentes)

// // A Segurança da Comparação Forte (===)
// // Avalia o Tipo e o Valor. Sem conversão oculta.
// console.log('1' === 1); // false (string e number são diferentes
// console.log(0 === false); // false (number e boolean são diferentes)
// console.log('JS' === 'JS'); // true (mesmo tipo e mesmo valor)

let emailValido = true;
let senhaValida = true;
let bloqueadoPorTentativas = false;

// AND (&&) - Todas as condições devem ser verdadeiras
let podeLogar = emailValido && senhaValida;

// NOT (!) - Inverte o valor lógico
let acessoLiberado = podeLogar && !bloqueadoPorTentativas;

// OR (||) - Pelo menos uma condição deve ser verdadeira
let isVIP = false;
let passeLivre = isVIP || acessoLiberado;