<?php
declare(strict_types=1);

function parseSqlDate(string $value): ?DateTimeImmutable
{
    $date = DateTimeImmutable::createFromFormat('!Y-m-d H:i:s', $value);
    $errors = DateTimeImmutable::getLastErrors();
    if ($date === false || ($errors !== false && ($errors['warning_count'] > 0 || $errors['error_count'] > 0))) {
        return null;
    }
    return $date;
}

function validateReservationPeriod(string $start, string $end, DateTimeImmutable $now): array
{
    $startDate = parseSqlDate($start);
    $endDate = parseSqlDate($end);
    if ($startDate === null || $endDate === null) {
        return ['inicio e fim devem usar YYYY-MM-DD HH:MM:SS'];
    }
    if ($endDate <= $startDate) {
        return ['fim deve ser posterior ao início'];
    }
    if ($startDate < $now) {
        return ['inicio não pode estar no passado'];
    }
    return [];
}

function periodsOverlap(string $start, string $end, string $existingStart, string $existingEnd): bool
{
    return $start < $existingEnd && $end > $existingStart;
}
