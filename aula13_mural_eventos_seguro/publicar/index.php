<?php
declare(strict_types=1);
require __DIR__ . '/includes/bootstrap.php';

header('Location: ' . url(isset($_SESSION['usuario_id']) ? '/painel.php' : '/login.php'));
exit;
