<?php

declare(strict_types=1);

namespace La\Users;

class RefreshToken
{
    /**
     * UUID токена
     * @var string
     */
    public string $token_uuid;
    /**
     * UUID пользователя
     * @var string
     */
    public string $user_uuid;
    /**
     * Дата и время создания токена
     * @var string
     */
    public string $created_datetime_utc;
    /**
     * Дата и время истечения срока действия токена
     * @var string
     */
    public string $expiration_datetime_utc;
}