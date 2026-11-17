<?php
declare(strict_types=1);

// Único arquivo que deve ser editado no servidor. Não publique credenciais reais no repositório.
return [
    'db' => [
        'host' => 'SEU_HOST_MARIADB',
        'port' => 3306,
        'name' => 'SUA_BASE_EXISTENTE',
        'user' => 'SEU_USUARIO',
        'password' => 'SUA_SENHA',
    ],
    // Datas de datetime-local são interpretadas neste fuso institucional.
    'timezone' => 'America/Sao_Paulo',
    // Gere no servidor: openssl rand -hex 32
    'api_key' => 'SUBSTITUA_POR_UMA_CHAVE_ALEATORIA_FORTE',
];
