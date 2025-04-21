<?php

declare(strict_types=1);

namespace La\FrontController;

class BaseController
{
    /**
     * Данные из запроса
     * @var RequestDataInterface
     */
    public RequestDataInterface $request {
        get {
            return $this->request;
        }
    }

    /**
     * Возвращает представление контроллера
     * @var ViewInterface
     */
    public ViewInterface $view {
        get {
            return $this->view;
        }
    }

    /**
     * Возвращает макет контроллера
     * @var LayoutInterface
     */
    public LayoutInterface $layout {
        get {
            return $this->layout;
        }
    }
}