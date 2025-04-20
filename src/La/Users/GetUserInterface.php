<?php

declare(strict_types=1);

namespace La\Users;

interface GetUserInterface
{
    /**
     * Возвращает пользователя
     * @return User|null
     */
    public function getUser(): ?User;
}