<?php
declare(strict_types=1);

function isJsonContentType(?string $contentType): bool
{
    if ($contentType === null) {
        return false;
    }
    return strtolower(trim(explode(';', $contentType, 2)[0])) === 'application/json';
}

function jsonResponse(int $status, array $body): never
{
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    header('Cache-Control: no-store');
    echo json_encode($body, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

function errorResponse(int $status, string $code, string $message): never
{
    jsonResponse($status, ['error' => ['code' => $code, 'message' => $message]]);
}
