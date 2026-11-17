<?php
declare(strict_types=1);

function openDatabase(array $config): mysqli
{
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $db = new mysqli($config['host'], $config['user'], $config['password'], $config['name'], (int) $config['port']);
    $db->set_charset('utf8mb4');
    return $db;
}
