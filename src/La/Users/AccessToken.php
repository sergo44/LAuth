<?php

declare(strict_types=1);

namespace La\Users;

use Firebase\JWT\JWT;

class AccessToken implements \JsonSerializable
{
    /**
     * Секрет
     * @var string
     */
    protected static string $secret = "86kHIwhcE5DPGc61vf02K1abZCbuopMS";
    /**
     * Алгоритм шифрования
     * @var string
     */
    protected static string $alg = "HS256";
    /**
     * Идентификатор пользователя
     * @var string
     */
    protected string $uuid;

    /**
     * Конструктор класса
     * @param string $uuid
     */
    public function __construct(string $uuid)
    {
        $this->uuid = $uuid;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): string
    {
        return $this->getToken();
    }

    /**
     * Получаем токен
     * @return string
     */
    public function getToken(): string
    {
        return JWT::encode(
            array(
                "uuid" => $this->uuid,
                "iss" => sprintf("http%s://%s:%u", LA_USE_SSL ? "s" : "", LA_HOSTNAME, LA_SERVER_PORT),
                "sub" => sprintf("http%s://%s:%u", LA_USE_SSL ? "s" : "", LA_HOSTNAME, LA_SERVER_PORT),
                "aud" => sprintf("http%s://%s:%u", LA_USE_SSL ? "s" : "", LA_HOSTNAME, LA_SERVER_PORT),
                "exp" => time() + 60 * 60,
                "nbf" => time(),
                "iat" => time(),
                "jti" => uniqid()
            ),
            self::$secret,
            self::$alg
        );
    }
}