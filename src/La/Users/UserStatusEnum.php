<?php

declare(strict_types=1);

namespace La\Users;

enum UserStatusEnum
{
    /**
     * Статус - валидация e-mail адреса
     */
    case Validation;
    /**
     * Статус - подтвержденный пользователь
     */
    case Confirmed;
    /**
     * Статус - забаненный пользователь
     */
    case Banned;
}
