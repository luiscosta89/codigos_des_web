<?php
declare(strict_types=1);

function conectar(array $config): mysqli
{
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $mysqli = new mysqli(
        $config['host'],
        $config['usuario'],
        $config['senha'],
        $config['banco'],
        $config['porta']
    );
    $mysqli->set_charset('utf8mb4');
    return $mysqli;
}
