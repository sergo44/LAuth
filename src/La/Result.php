<?php

declare(strict_types=1);

namespace La;

class Result
{
    /**
     * Признак успешности выполнения запроса
     * @var bool $success
     */
    protected(set) bool $success = true;
    /**
     * Признак ошибки выполнения запроса
     * @var bool
     */
    protected(set) bool $error = false;
    /**
     * Массив ошибок
     * @var Error[]
     */
    public array $errors = [];

    /**
     * Добавление ошибки
     * @param string $message
     * @param string|null $field
     * @return $this
     */
    public function addError(string $message, ?string $field = null): self
    {
        $this->success = false;
        $this->error = true;

        $this->errors[] = new Error($message, $field);
        return $this;
    }
}