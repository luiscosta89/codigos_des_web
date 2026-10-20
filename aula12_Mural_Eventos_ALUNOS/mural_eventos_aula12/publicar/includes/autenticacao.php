<?php
declare(strict_types=1);

function exigir_login(): void
{
    if (!isset($_SESSION['usuario_id'])) {
        header('Location: ' . url('/login.php'));
        exit;
    }
}
