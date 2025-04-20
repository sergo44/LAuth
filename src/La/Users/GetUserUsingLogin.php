<?php

declare(strict_types=1);

namespace La\Users;

use La\ApplicationError;
use La\DefaultPostgre;
use PDO;

class GetUserUsingLogin implements GetUserInterface
{
    /**
     * Конструктор класса
     * @param string $login
     * @param PDO|null $pdo
     * @throws ApplicationError
     */
    public function __construct(
        protected string $login,
        protected ?PDO $pdo = null
    ) {
        if (!isset($this->pdo)) {
            $this->pdo = DefaultPostgre::getInstance();
        }
    }

    /**
     * Возвращает пользователя по логину
     * @inheritDoc
     */
    public function getUser(): ?User
    {
        $stmt = $this->pdo->prepare(/** @lang PostgreSQL */"
            SELECT * 
            FROM users
            WHERE login = :login
        ");

        $stmt->execute(array(
            ":login" => $this->login
        ));

        return $stmt->fetchObject(User::class) ?: null;
    }
}