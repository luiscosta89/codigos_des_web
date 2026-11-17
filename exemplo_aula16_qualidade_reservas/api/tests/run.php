<?php
declare(strict_types=1);

require_once __DIR__ . '/../src/validation.php';
require_once __DIR__ . '/../src/http.php';

$tests = [];
function test(string $name, callable $callback): void { global $tests; $tests[] = [$name, $callback]; }
function assertSameValue(mixed $expected, mixed $actual): void {
    if ($expected !== $actual) { throw new RuntimeException('Esperado ' . var_export($expected, true) . ', recebido ' . var_export($actual, true)); }
}

test('aceita período futuro válido', function (): void {
    assertSameValue([], validateReservationPeriod('2026-11-20 09:00:00', '2026-11-20 11:00:00', new DateTimeImmutable('2026-11-19 12:00:00')));
});
test('rejeita término igual ou anterior ao início', function (): void {
    assertSameValue('fim deve ser posterior ao início', validateReservationPeriod('2026-11-20 09:00:00', '2026-11-20 09:00:00', new DateTimeImmutable('2026-11-19 12:00:00'))[0]);
});
test('rejeita datas inválidas', function (): void {
    assertSameValue('inicio e fim devem usar YYYY-MM-DD HH:MM:SS', validateReservationPeriod('amanhã', 'depois', new DateTimeImmutable())[0]);
});
test('detecta sobreposição com regra início < fim existente e fim > início existente', function (): void {
    assertSameValue(true, periodsOverlap('2026-11-20 10:00:00', '2026-11-20 12:00:00', '2026-11-20 09:00:00', '2026-11-20 11:00:00'));
    assertSameValue(false, periodsOverlap('2026-11-20 11:00:00', '2026-11-20 12:00:00', '2026-11-20 09:00:00', '2026-11-20 11:00:00'));
});
test('aceita somente Content-Type JSON no POST', function (): void {
    assertSameValue(true, isJsonContentType('application/json'));
    assertSameValue(true, isJsonContentType('application/json; charset=utf-8'));
    assertSameValue(false, isJsonContentType('text/plain'));
    assertSameValue(false, isJsonContentType(null));
});

$failures = 0;
foreach ($tests as [$name, $callback]) {
    try { $callback(); echo "PASS: {$name}\n"; }
    catch (Throwable $error) { $failures++; fwrite(STDERR, "FAIL: {$name} — {$error->getMessage()}\n"); }
}
echo count($tests) . " testes, {$failures} falhas\n";
exit($failures === 0 ? 0 : 1);
