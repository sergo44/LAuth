<?php

declare(strict_types=1);

namespace La\Users;

use La\ApplicationError;
use La\DefaultPostgre;
use PDO;

/**
 * Класс для получения пользователя по UUID
 *
 * @package La\Users
 */
class GetUserUsingUuid implements GetUserInterface
{
    /**
     * Использовать кеш Redis
     * @var bool $use_cache
     */
    protected bool $use_cache = false;
    /**
     * Конструктор класса
     * @param string $uuid
     * @param PDO|null $dbh
     * @throws ApplicationError
     */
    public function __construct(
        protected string $uuid,
        protected ?PDO $dbh = null
    ) {
        if (!isset($this->dbh)) {
            $this->dbh = DefaultPostgre::getInstance();
        }
    }

    public function setUseCache(bool $use_cache): GetUserUsingUuid
    {
        $this->use_cache = $use_cache;
        return $this;
    }


    /**
     * Возвращает пользователя
     * @return User|null
     */
    public function getUser(): ?User
    {
        if ($this->use_cache) {
            if ($user = UsersCache::get($this->uuid)) {
                return $user;
            };
        }


        $stmt = $this->dbh->prepare(/** @lang PostgreSQL */"
            SELECT
                *
            FROM
                users
            WHERE
                uuid = :uuid
        ");

        $stmt->execute(array(
            "uuid" => $this->uuid
        ));

        $user = $stmt->fetchObject(User::class) ?: null;
        if ($user && $this->use_cache) {
            UsersCache::add($user);
        }

        return $user;
    }
}