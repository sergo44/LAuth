<?php

declare(strict_types=1);

namespace La\FrontController;

interface RequestDataInterface
{
    /**
     * Возвращает входные данные запроса
     * @return array
     */
    public function getData(): array;

    /**
     * Возвращает входные данные запроса по ключу
     * @param string $key
     * @return string|null
     */
    public function getString(string $key): ?string;
}