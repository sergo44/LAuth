<?php

declare(strict_types=1);

namespace La\Users\SignIn\Controllers;

use DateMalformedStringException;
use DateTimeInterface;
use La\ApplicationError;
use La\Dt;
use La\FrontController\BaseController;
use La\FrontController\ControllerException;
use La\FrontController\ControllerInterface;
use La\FrontController\LayoutInterface;
use La\FrontController\RequestDataInterface;
use La\Users\GetUserUsingLogin;
use La\Users\RefreshToken;
use La\Users\RefreshTokenStorage;
use La\Users\SignIn\Views\SignInJsonView;
use La\Users\UserStorage;
use Ramsey\Uuid\Uuid;

/**
 * Класс авторизации пользователя
 */
class SignInController extends BaseController implements ControllerInterface
{
    /**
     * Конструктор класс
     * @param RequestDataInterface $request
     * @param SignInJsonView $view
     * @param LayoutInterface $layout
     */
    public function __construct(RequestDataInterface $request, SignInJsonView $view, LayoutInterface $layout)
    {
        $this->request = $request;
        $this->view = $view;
        $this->layout = $layout;
    }

    /**
     * Метод авторизации
     * @return $this
     * @throws ApplicationError
     * @throws DateMalformedStringException
     */
    public function index(): self
    {

        try {

            $login = $this->request->getString("login", 255);
            if (!$login) {
                throw new ControllerException("Не указан логин пользователя");
            }

            $password = $this->request->getString("password", 255);
            if (!$password) {
                throw new ControllerException("Не указан пароль пользователя");
            }

            $user = new GetUserUsingLogin($login)->getUser();
            if (!$user) {
                throw new ControllerException("Пользователь не найден");
            }

            if (!password_verify($password, $user->password_hash)) {
                throw new ControllerException("Вы указали неверный пароль");
            }

            // Обновим время входа
            $user->sign_in_datetime_utc = Dt::now()->format(DateTimeInterface::ATOM);
            new UserStorage($user)->updateSignInTimestamp();

            // Создадим refresh-токен и сохраним
            $refresh_token = new RefreshToken();
            $refresh_token->token_uuid = Uuid::uuid7()->toString();
            $refresh_token->user_uuid = $user->uuid;
            $refresh_token->created_datetime_utc = Dt::now()->format(DateTimeInterface::ATOM);
            $refresh_token->expiration_datetime_utc = Dt::now()->modify("+1 year")->format(DateTimeInterface::ATOM);

            new RefreshTokenStorage($refresh_token)->add();

            // Сохраним refresh token в HTTP-cookie
            if (!headers_sent()) {
                setcookie("_refresh_token", $refresh_token->token_uuid, array(
                    'path' => '/',
                    'secure' => LA_USE_SSL,
                    'httponly' => true,
                    'expires' => time() + 3600 * 24 * 365
                ));
            } else {
                trigger_error("Can't finish auth properly: buffer was sent before headers were sent", E_USER_WARNING);
            }

            $this->view->user = $user;


        } catch (ControllerException $e) {
            $this->view->result->addError($e->getMessage());
        }

        return $this;
    }
}