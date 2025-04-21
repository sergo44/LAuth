<?php

declare(strict_types=1);

namespace La\Users;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use La\HttpError401Exception;

class Auth
{
    /**
     * Авторизованный пользователь
     * @var User|null
     */
    protected static ?User $user = null;

    /**
     * Получение авторизованного пользователя
     * @throws HttpError401Exception
     */
    public static function get(): ?User
    {
        if (!isset(self::$user)) {

            $auth_header = $_SERVER['HTTP_AUTHORIZATION'] ?? null;

            // Авторизация по GWT
            $token = explode(" ", (string)$auth_header);

            if (sizeof($token) > 1) {
                // Проверяем авторизацию
                for ($i = 0; $i < count($token); $i = $i + 2) {

                    $token_type = $token[$i] ?? null;
                    $token_value = $token[$i + 1] ?? null;

                    if ($token_type === "Bearer" && $token_value && $token_value !== "null") {

                        try {

                            $decoded = JWT::decode($token_value, new Key(AccessToken::$secret, AccessToken::$alg));
                            self::$user = new GetUserUsingUuid($decoded->uuid)->getUser();
                            return self::$user;

                        } catch (\Exception $e) {
                            // Используем \Exception т.к. множество исключений
                            throw new HttpError401Exception($e->getMessage());
                        }
                    }
                }
            }

        }
        
        return self::$user;
    }
}