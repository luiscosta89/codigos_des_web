<?php
declare(strict_types=1);

function autenticar_usuario(mysqli $mysqli, string $login, string $senha): ?array
{
    $stmt = $mysqli->prepare(
        'SELECT id, nome, login, senha FROM usuarios WHERE login = ?'
    );
    $stmt->bind_param('s', $login);
    $stmt->execute();
    $usuario = $stmt->get_result()->fetch_assoc();

    if (!$usuario || !password_verify($senha, $usuario['senha'])) {
        return null;
    }

    unset($usuario['senha']);
    return $usuario;
}
