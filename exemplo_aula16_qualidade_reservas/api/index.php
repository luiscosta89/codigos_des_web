<?php
declare(strict_types=1);

require_once __DIR__ . '/src/http.php';
require_once __DIR__ . '/src/database.php';
require_once __DIR__ . '/src/validation.php';
require_once __DIR__ . '/src/repository.php';

header('X-Content-Type-Options: nosniff');

try {
    $config = require __DIR__ . '/config.php';
    $timezone = new DateTimeZone((string) ($config['timezone'] ?? 'America/Sao_Paulo'));
    date_default_timezone_set($timezone->getName());
    $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
    if (!in_array($method, ['GET', 'POST'], true)) {
        header('Allow: GET, POST');
        errorResponse(405, 'method_not_allowed', 'Método não permitido.');
    }
    if ($method === 'POST') {
        $providedKey = $_SERVER['HTTP_X_API_KEY'] ?? '';
        if ($providedKey === '' || !hash_equals((string) $config['api_key'], $providedKey)) {
            errorResponse(401, 'unauthorized', 'Chave de API ausente ou inválida.');
        }
        if (!isJsonContentType($_SERVER['CONTENT_TYPE'] ?? null)) {
            errorResponse(415, 'unsupported_media_type', 'Use Content-Type: application/json.');
        }
    }

    $db = openDatabase($config['db']);
    if ($method === 'GET') {
        jsonResponse(200, ['equipamentos' => listEquipment($db), 'reservas' => listReservations($db)]);
    }

    $raw = file_get_contents('php://input');
    $input = json_decode($raw ?: '', true);
    if (!is_array($input)) { errorResponse(400, 'invalid_json', 'Envie um objeto JSON válido.'); }
    $required = ['equipamento_id', 'solicitante', 'inicio', 'fim'];
    foreach ($required as $field) {
        if (!array_key_exists($field, $input)) { errorResponse(422, 'validation_error', "Campo obrigatório: {$field}."); }
    }
    if (!is_int($input['equipamento_id']) || $input['equipamento_id'] < 1) { errorResponse(422, 'validation_error', 'equipamento_id deve ser inteiro positivo.'); }
    if (!is_string($input['solicitante']) || trim($input['solicitante']) === '' || mb_strlen(trim($input['solicitante'])) > 120) { errorResponse(422, 'validation_error', 'solicitante deve ter entre 1 e 120 caracteres.'); }
    if (!is_string($input['inicio']) || !is_string($input['fim'])) { errorResponse(422, 'validation_error', 'inicio e fim devem ser textos.'); }
    $periodErrors = validateReservationPeriod($input['inicio'], $input['fim'], new DateTimeImmutable('now', $timezone));
    if ($periodErrors !== []) { errorResponse(422, 'validation_error', $periodErrors[0]); }
    $input['solicitante'] = trim($input['solicitante']);
    jsonResponse(201, ['reserva' => createReservationInDatabase($db, $input)]);
} catch (DomainException $error) {
    errorResponse(409, 'reservation_conflict', $error->getMessage());
} catch (Throwable $error) {
    error_log('Aula16 API: ' . $error->getMessage());
    errorResponse(500, 'internal_error', 'Não foi possível concluir a operação.');
}
