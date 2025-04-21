<?php

declare(strict_types=1);

namespace La\FrontController\RequestData;

use La\FrontController\RequestDataInterface;

/**
 * Класс для работы с данными запроса
 *
 * @package La\Core
 */
class RequestData implements RequestDataInterface
{

    /**
     * @inheritdoc
     */
    public function getData(): array
    {
        return $_REQUEST;
    }

    /**
     * @inheritDoc
     */
    public function getString(string $key, ?int $max_length = null): ?string
    {
        return
            isset($_REQUEST[$key]) && !is_array($_REQUEST[$key])
                ? (isset($max_length) ? mb_substr($_REQUEST[$key], 0, $max_length) : $_REQUEST[$key])
                : null;
    }
}