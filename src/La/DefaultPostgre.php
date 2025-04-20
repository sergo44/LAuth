<?php

declare(strict_types=1);

namespace La;

use PDO;

class DefaultPostgre
{
    /**
     * Объект PDO
     * @var PDO|null
     */
    protected static ?PDO $instance = null;

    /**
     * Блокировка создания объекта
     */
    private function __construct()
    {}

    /**
     * Блокировка клонирования объекта
     * @return void
     */
    private function __clone()
    {}

    /**
     * Объект PDO
     * @return PDO
     * @throws ApplicationError
     */
    public static function getInstance(): PDO
    {
        try {

            if (!isset(self::$instance)) {

                self::$instance = new PDO(
                    sprintf(
                        "pgsql:host=%s;dbname=%s;port=%u",
                        LA_POSTGRES_HOST,
                        LA_POSTGRES_DB,
                        LA_POSTGRES_PORT
                    ),
                    LA_POSTGRES_USER,
                    LA_POSTGRES_PASSWORD
                );
            }

        } catch (\PDOException $e) {
            throw new ApplicationError($e->getMessage());
        }

        return self::$instance;
    }
}