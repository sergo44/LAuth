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

    /**
     * Возвращает пользователя
     * @return User|null
     */
    public function getUser(): ?User
    {
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

        return $stmt->fetchObject(User::class) ?: null;
    }
}