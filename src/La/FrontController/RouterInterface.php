<?php

declare(strict_types=1);

namespace La\FrontController;

/**
 * Интерфейс маршрутизатора
 *
 * @package La\Core
 */
interface RouterInterface
{
    /**
     * Возвращает родительский диспетчер Front-контроллера
     * @var Dispatcher
     */
    public Dispatcher $dispatcher {
        get;
    }

    /**
     * Пробуем маршрутизировать запрос
     * @return ControllerInterface|null
     */
    public function tryRoute(): ?ControllerInterface;
}