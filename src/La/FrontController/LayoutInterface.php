<?php

declare(strict_types=1);

namespace La\FrontController;

/**
 * Интерфейс описывает доступ к макетам
 *
 * @package La\Core
 */
interface LayoutInterface
{

    /**
     * Рендер представления в макете
     * @param ViewInterface $view
     * @return void
     */
    public function render(ViewInterface $view): void;
}