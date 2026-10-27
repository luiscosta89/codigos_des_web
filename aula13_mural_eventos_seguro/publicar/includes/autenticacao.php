<?php
declare(strict_types=1);

function exigir_login(): void
{
    if (!isset($_SESSION['usuario_id']) || id_positivo($_SESSION['usuario_id']) === null) {
        header('Location: ' . url('/login.php'));
        exit;
    }
}

function usuario_id(): int
{
    return id_positivo($_SESSION['usuario_id'] ?? null) ?? throw new RuntimeException('Sessão sem usuário válido.');
}
