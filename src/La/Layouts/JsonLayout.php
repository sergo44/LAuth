<?php

declare(strict_types=1);

namespace La\Layouts;

use La\FrontController\LayoutInterface;
use La\FrontController\ViewInterface;

class JsonLayout implements LayoutInterface
{

    /**
     * @inheritDoc
     */
    public function render(ViewInterface $view): void
    {
        if (!headers_sent()) {
            header('Content-Type: application/json');
        }

        print $view->render();
    }
}