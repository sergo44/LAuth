<?php

declare(strict_types=1);

namespace La\Users;

use La\ApplicationError;
use La\FrontController\ControllerInterface;
use La\FrontController\FileRoute;
use La\FrontController\RequestData\RequestData;
use La\FrontController\RouterInterface;
use La\Layouts\JsonLayout;
use La\Users;

/**
 * Класс для маршрутизации запросов к API пользователей
 *
 * @package La\Users
 */
class Routes extends FileRoute implements RouterInterface
{
    /**
     * @inheritDoc
     * @throws ApplicationError
     */
    public function tryRoute(): ?ControllerInterface
    {
        if (preg_match("#^/?Api/Users/SignUp/?$#", $this->dispatcher->path)) {
            return new SignUp\Controllers\SignUpController(
                new RequestData(),
                new Users\SignUp\Views\SignUpJsonView(),
                new JsonLayout()
            )->index();
        }

        if (preg_match("#^/?Api/Users/SignIn/?$#", $this->dispatcher->path)) {
            return new SignIn\Controllers\SignInController(
                new RequestData(),
                new Users\SignIn\Views\SignInJsonView(),
                new JsonLayout()
            )->index();
        }

        return null;
    }
}