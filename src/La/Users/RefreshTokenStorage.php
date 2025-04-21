<?php

declare(strict_types=1);

namespace La\Users;

use La\ApplicationError;
use La\DefaultPostgre;
use PDO;

class RefreshTokenStorage
{
    /**
     * Токен для сохранения
     * @param RefreshToken $refresh_token
     * @param PDO|null $pdo
     * @throws ApplicationError
     */
    public function __construct(
        protected RefreshToken $refresh_token,
        protected ?PDO $pdo = null
    )
    {
        if (!isset($this->pdo)) {
            $this->pdo = DefaultPostgre::getInstance();
        }
    }

    /**
     * Добавление refresh-токена в базу данных
     * @return $this
     */
    public function add(): self
    {

        $this->pdo->prepare(/** @lang PostgreSQL */"
            INSERT INTO refresh_tokens
            (
                token_uuid, 
                user_uuid, 
                created_datetime_utc, 
                expiration_datetime_utc
            ) 
            VALUES 
            (
                :token_uuid,
                :user_uuid,
                :created_datetime_utc,
                :expiration_datetime_utc
            ) 
        ")->execute(array(
            ":token_uuid" => $this->refresh_token->token_uuid,
            ":user_uuid" => $this->refresh_token->user_uuid,
            ":created_datetime_utc" => $this->refresh_token->created_datetime_utc,
            ":expiration_datetime_utc" => $this->refresh_token->expiration_datetime_utc
        ));

        return $this;
    }
}