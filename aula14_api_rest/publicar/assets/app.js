const form = document.querySelector('#requisicao');
const apiUrl = new URL('api/index.php', window.location.href);

form.addEventListener('submit', async (event) => {
    event.preventDefault();
    const method = document.querySelector('#metodo').value;
    const route = document.querySelector('#rota').value.trim();
    const extraQuery = new URLSearchParams(document.querySelector('#query').value);
    const key = document.querySelector('#api-key').value;
    const url = new URL(apiUrl);
    url.searchParams.set('rota', route);
    extraQuery.forEach((value, name) => url.searchParams.append(name, value));

    const options = { method, headers: { Accept: 'application/json' } };
    if (key) {
        options.headers['X-API-Key'] = key;
    }
    if (['POST', 'PUT'].includes(method)) {
        options.headers['Content-Type'] = 'application/json';
        options.body = document.querySelector('#corpo').value;
    }

    document.querySelector('#metodo-usado').textContent = method;
    document.querySelector('#url').textContent = url.toString();
    document.querySelector('#status').textContent = 'Enviando…';
    const inicio = performance.now();

    try {
        const response = await fetch(url, options);
        const texto = await response.text();
        document.querySelector('#status').textContent = `${response.status} ${response.statusText}`;
        document.querySelector('#duracao').textContent = `${Math.round(performance.now() - inicio)} ms`;
        document.querySelector('#headers').textContent = [...response.headers]
            .map(([name, value]) => `${name}: ${value}`)
            .join('\n') || '(sem headers expostos)';
        try {
            document.querySelector('#body').textContent = texto ? JSON.stringify(JSON.parse(texto), null, 2) : '(corpo vazio)';
        } catch {
            document.querySelector('#body').textContent = texto || '(corpo vazio)';
        }
    } catch (error) {
        document.querySelector('#status').textContent = 'Falha de rede';
        document.querySelector('#duracao').textContent = `${Math.round(performance.now() - inicio)} ms`;
        document.querySelector('#headers').textContent = 'Consulte Console e Network.';
        document.querySelector('#body').textContent = error.message;
    }
});

document.querySelector('#limpar').addEventListener('click', () => {
    ['#url', '#headers', '#body'].forEach((selector) => {
        document.querySelector(selector).textContent = '—';
    });
    document.querySelector('#status').textContent = 'Aguardando requisição';
    document.querySelector('#metodo-usado').textContent = '—';
    document.querySelector('#duracao').textContent = '—';
});
