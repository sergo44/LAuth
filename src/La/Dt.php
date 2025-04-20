<?php

declare(strict_types=1);

namespace La;

use DateTime;
use DateTimeZone;

/**
 * Класс для работы с датами и временем
 *
 * @package La\Core
 */
class Dt
{
    /**
     * Возвращает текущее время
     * @param string $tz
     * @return DateTime
     */
    public static function now(string $tz = "UTC"): DateTime
    {
        try {
            return new DateTime("now", new DateTimeZone($tz));
        } catch (\DateMalformedStringException | \DateInvalidTimeZoneException $e) {
            trigger_error($e->getMessage(), E_USER_WARNING);
            return new DateTime("now");
        }
    }
}