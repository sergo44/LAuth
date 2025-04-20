<?php

declare(strict_types=1);

namespace La\FrontController;

use La\Result;

/**
 * Интерфейс вида
 *
 * @package La\Core
 */
interface ViewInterface
{
    public Result $result {
        get;
    }

    /**
     * Render вида (fetch)
     * @return string
     */
    public function render(): string;
}