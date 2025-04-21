<?php

declare(strict_types=1);

namespace La\Users;

use La\ApplicationError;
use La\DefaultPostgre;
use PDO;

class UserStorage
{
    /**
     * Конструктор класса
     * @param User $user
     * @param PDO|null $pdo
     * @throws ApplicationError
     */
    public function __construct(
        protected User $user,
        protected ?PDO $pdo = null
    ) {
        if (!isset($this->pdo)) {
            $this->pdo = DefaultPostgre::getInstance();
        }
    }

    /**
     * Добавление записи в базу данных
     * @return self
     */
    public function add(): self
    {
        $this->pdo->prepare(/** @lang PostgreSQL */"
            INSERT INTO users
            (
                uuid,
                login,
                password_hash,
                email,
                status,
                sign_up_datetime_utc,
                sign_in_datetime_utc
            ) 
            VALUES 
            (
                :uuid,
                :login,
                :password_hash,
                :email,
                :status,
                :sign_up_datetime_utc,
                :sign_in_datetime_utc
            )
        ")->execute(array(
            "uuid" => $this->user->uuid,
            "login" => $this->user->login,
            "password_hash" => $this->user->password_hash,
            "email" => $this->user->email,
            "status" => $this->user->status,
            "sign_up_datetime_utc" => $this->user->sign_up_datetime_utc,
            "sign_in_datetime_utc" => $this->user->sign_in_datetime_utc
        ));

        return $this;
    }

    /**
     * Обновление даты последнего входа пользователя
     * @return self
     */
    public function updateSignInTimestamp(): self
    {
        $this->pdo->prepare(/** @lang PostgreSQL */"
            UPDATE 
                users
            SET 
                sign_in_datetime_utc = :sign_in_datetime_utc
            WHERE 
                uuid = :uuid
        ")->execute(array(
            "sign_in_datetime_utc" => $this->user->sign_in_datetime_utc,
            "uuid" => $this->user->uuid
        ));

        return $this;
    }
}