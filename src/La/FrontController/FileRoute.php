<?php

declare(strict_types=1);

namespace La\FrontController;

/**
 * Абстрактный класс для маршрутизации через файлы
 *
 * @package La\Core
 */
abstract class FileRoute
{
    /**
     * Диспетчер фронт контроллера
     * @var Dispatcher
     */
    public Dispatcher $dispatcher {
        get {
            return $this->dispatcher;
        }
    }

    /**
     * Конструктор класса
     * @param Dispatcher $dispatcher
     */
    public function __construct(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }
}