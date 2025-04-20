<?php

declare(strict_types=1);

namespace La\Users;

use JsonSerializable;

class User implements JsonSerializable
{
    /**
     * UUID пользователя
     * @var string
     */
    public string $uuid;
    /**
     * Логин пользователя
     * @var string
     */
    public string $login;
    /**
     * Хеш пароля
     * @var string
     */
    public string $password_hash;
    /**
     * E-mail
     * @var string
     */
    public string $email;
    /**
     * Статус регистрации (Validation, Confirmed, Banned)
     * @var string
     */
    public string $status;
    /**
     * Дата регистрации
     * @var string
     */
    public string $sign_up_datetime_utc;
    /**
     * Дата последнего входа
     * @var string|null
     */
    public ?string $sign_in_datetime_utc = null;

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return array(
            "uuid" => $this->uuid,
            "login" => $this->login,
            "email" => $this->email,
            "status" => $this->status,
            "sign_up_datetime_utc" => $this->sign_up_datetime_utc,
            "sign_in_datetime_utc" => $this->sign_in_datetime_utc
        );
    }
}