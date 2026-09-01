//Códigos de teste par a segunda aula de JS

var username = null;
var acessoLiberado = false;

if(username == null){
  username = 'John Doe'; // Bloco executado se verdadeiro
} else if (username == 'admin'){
  acessoLiberado = true; // Ramificação alternativa
} else {
  acessoLiberado = false; // O caminho padrão
}

// O JS avalia a "falsidade" diretamente:
let address = '';

if(!address){
  console.log('Endereço não informado');
}

let x = "0";

switch (x) {
  case 0:
    text = "Off";
    break;
  case 1:
    return "On";
  default:
    text = "Nenhum valor encontrado";
}

// Ternário: Refatorando if/else em 1 linha
//let status = (idade < 18) ? 'Negado' : 'Permitido';

// Nullish Coalescing Operator (??)
let config = { timeout: 0 };

// Errado (||): 0 é falsy, usará 1000
let t1 = config.timeout || 1000;

// Certo (??): 0 é considerado válido, usará 0
let t2 = config.timeout ?? 1000;

let count = 0;

while (count < 3) {
  console.log(count); // Executa 0, 1, 2
  count++;
}

// O bloco do...while roda pelo menos uma vez, mesmo que a condição seja falsa
let i = 10;

do {
  console.log('Executado!');
} while (i < 5);

// O loop clássico para contadores
let sum = 0;

for (let i = 0; i <= 100; i += 10) {
  sum += i;
}

// Percorrendo arrays (modo manual)
let arr = [1, 2, 3, 4, 5];

for (let i = 0; i < arr.length; i++) {
  console.log(arr[i]);
}

// Percorrendo arrays (modo moderno)
for (let value of arr) {
  console.log(value);
}

let primes = [2, 3, 5, 7, 11];
let sumPrimes = 0;

// Elegante, legível, sem índices [i]
for (let prime of primes) {
  sumPrimes += prime;
}

// Funciona perfeitamente com strings também
let name = 'John Doe';

for (let char of name) {
  console.log(char);
}

let book = {
  title: '1984',
  edition: 1,
  author: 'George Orwell'
};

for (let prop in book) {
  // prop armazena a chave
  // book[prop] armazena o valor
  console.log(`${prop}: ${book[prop]}`);
}

for (let i = 0; i < 10; i++) {
  if (i % 2 == 0) {
    continue; // Pula para a próxima iteração
  }
  if (i == 7) {
    console.log('Alvo encontrado!');
    break; // Interrompe o loop
  }
  console.log(i); // Imprime apenas números ímpares menores que 7
}

// Criação com colchetes
//let primes = [2, 3, 5, 7, 11];

// Indexação baseada em zero
console.log(primes[0]); //2
console.log(primes.length); //5

// Acesso dinâmico ao último elemento
console.log(primes[primes.length - 1]); //11

let queue = ['John', 'Mary', 'Peter'];

// Adiciona no final
queue.push('Jane');
queue.pop(); // Remove do final

// Adiciona no início
queue.unshift('Paul');
queue.shift(); // Remove do início

let nums = [1, 2, 3, 4, 5];

let evens = nums.filter(n => n % 2 === 0); // [2, 4]

let squares = nums.map(n => n * n); // [1, 4, 9, 16, 25]

//let sum = nums.reduce((acc, n) => acc + n, 0); // 15

let user = {
  id: 101,
  role: 'admin',
};

// Notação de ponto (Estática)
console.log(user.role); // 'admin'

// Notação de colchetes (Dinâmica)
let key = 'id';
console.log(user[key]); // 101

// Alteração dinâmica e remoção
user.isActive = true; // Adiciona uma nova propriedade
delete user.role; // Remove a propriedade 'role'

let users = [
  { id: 1, name: 'Alice', active: true },
  { id: 2, name: 'Bob', active: false },
  { id: 3, name: 'Charlie', active: true }
];

// Acesso combinado: array + objeto
let secondUserName = users[1].name; // 'Bob'

// Declaração de função tradicional (Sofre Hoisting)
function calculateTax(price, tax = 0.15) {
  return price + (price * tax);
}

// Expressão de função (Não sofre Hoisting)
const logError = function(msg) {
  console.error('[ERRO]: ' + msg);
  // Retorna undefined implicitamente
};

console.log(logError()); // [ERRO]:undefined

// Exemplo de Escopo e Visibilidade
let globalVar = 'Acessível em todo lugar';

function processData() {
  let localVar = 'Acessível apenas dentro da função';
  console.log(globalVar); // Acessível
}

processData();
// console.log(localVar); // Erro: localVar não está definido

// Arrow Function com retorno implícito
const double = n => n * 2;

// Rest Parameter (Empacota)
const sumAll = (...numbers) => {
  return numbers.reduce((a, b) => a + b, 0);
};

// Spread Operator (Desempacota)
const arr1 = [1, 2, 3];
const arr2 = [4, 5,,6];
const combined = [...arr1, ...arr2]; // [1, 2, 3, 4, 5, 6]

const users = [
  { id: 1, name: 'Alice', score: 85 },
  { id: 2, name: 'Bob', score: 40 },
  { id: 3, name: 'Charlie'}
];

const approvedNames = users
  .filter(user => (user.score ?? 0) >= 70) // Filtra aprovados
  .map(user => user.name.toUpperCase()); // Extrai nomes

console.log(approvedNames); // ['ALICE']

