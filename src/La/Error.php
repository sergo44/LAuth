<?php

declare(strict_types=1);

namespace La;

class Error
{
    /**
     * Конструктор класса
     * @param string $message Сообщение об ошибки
     * @param ?string $field Поле, в котором произошла ошибка
     */
    public function __construct(
        public string $message,
        public ?string $field = null
    ) {

    }
}