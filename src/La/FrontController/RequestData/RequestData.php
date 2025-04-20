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
    public function getString(string $key): ?string
    {
        return $_REQUEST[$key] ?? null;
    }
}