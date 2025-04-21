<?php

declare(strict_types=1);

namespace La\Users;

use La\RedisHelper;

/**
 * Класс для работы с кешем пользователей
 *
 * @package La\Users
 */
class UsersCache
{
    /**
     * Получаем пользователя из кеша
     * @param string $uuid
     * @return User|null
     */
    public static function get(string $uuid): ?User
    {
        $key = "user:$uuid";
        if (RedisHelper::getInstance()->isExists($key)) {
            return RedisHelper::getInstance()->getValue($key);
        }

        return null;
    }

    /**
     * Добавляет пользователя в кеш
     * @param User $user
     * @return void
     */
    public static function add(User $user): void
    {
        $key = sprintf("user:%s", $user->uuid);
        RedisHelper::getInstance()->setValue($key, $user);
    }
}