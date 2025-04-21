<?php

declare(strict_types=1);

namespace La\Users\SignIn\Controllers;

use La\FrontController\BaseController;
use La\FrontController\ControllerInterface;
use La\FrontController\LayoutInterface;
use La\FrontController\RequestDataInterface;
use La\HttpError401Exception;
use La\Users\Auth;
use La\Users\SignIn\Views\CheckJsonView;

class CheckJsonController extends BaseController implements ControllerInterface
{
    /**
     * Конструктор класса
     * @param RequestDataInterface $request
     * @param CheckJsonView $view
     * @param LayoutInterface $layout
     */
    public function __construct(RequestDataInterface $request, CheckJsonView $view, LayoutInterface $layout)
    {
        $this->request = $request;
        $this->view = $view;
        $this->layout = $layout;
    }

    /**
     * Метод проверки авторизации
     * @return $this
     */
    public function index(): self
    {

        try {

            $this->view->user = Auth::get();

        } catch (HttpError401Exception $e) {
            $this->view->result->addError($e->getMessage());
        }

        return $this;
    }
}