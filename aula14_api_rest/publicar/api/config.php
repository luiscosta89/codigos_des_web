<?php

declare(strict_types=1);

return [
    'db' => [
        'host' => 'localhost',
        'porta' => 3306,
        'usuario' => 'admin',
        'senha' => 'luis8234',
        'banco' => 'prof',
    ],

    'api_key' => '292807ceecf61bd23d7475981ddf4a43b385f7e7be7977a973262ffe85650085',

    // O console está no mesmo servidor, portanto pode ficar vazio.
    'cors_origens' => [],

    // Detecta automaticamente /desweb/alunos/prof/aula14_api_rest.
    'base_url' => null,
];
