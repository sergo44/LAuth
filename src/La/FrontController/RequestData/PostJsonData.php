<?php

declare(strict_types=1);

namespace La\FrontController\RequestData;

use La\FrontController\RequestDataInterface;

/**
 * Класс для работы с данными запроса
 *
 * @package La\Core
 */
class PostJsonData implements RequestDataInterface
{
    /**
     * Данные запроса
     * @var array
     */
    protected array $data = array();

    /**
     * Конструктор класса
     */
    public function __construct()
    {
        $input = file_get_contents('php://input');
        if (json_validate($input)) {
            $this->data = json_decode($input, true);
        }
    }

    /**
     * @inheritdoc
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @inheritDoc
     */
    public function getString(string $key, ?int $max_length = null): ?string
    {
        return
            isset($this->data[$key]) && !is_array($this->data[$key])
                ? (isset($max_length) ? mb_substr($this->data[$key], 0, $max_length) : $this->data[$key])
                : null;
    }
}