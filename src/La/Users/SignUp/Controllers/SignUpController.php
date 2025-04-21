<?php

declare(strict_types=1);

namespace La\Users\SignUp\Controllers;

use DateTimeInterface;
use La\ApplicationError;
use La\Dt;
use La\FrontController\BaseController;
use La\FrontController\ControllerException;
use La\FrontController\ControllerInterface;
use La\FrontController\LayoutInterface;
use La\FrontController\RequestDataInterface;
use La\FrontController\ViewInterface;
use La\Users\GetUserUsingLogin;
use La\Users\SignUp\Views\SignUpJsonView;
use La\Users\User;
use La\Users\UserStatusEnum;
use La\Users\UserStorage;
use Ramsey\Uuid\Uuid;

/**
 * Контроллер регистрации пользователя
 *
 * @package La\SignUp
 */
class SignUpController extends BaseController implements ControllerInterface
{
    /**
     * Конструктор класса
     * @param RequestDataInterface $request
     * @param SignUpJsonView $view
     * @param LayoutInterface $layout
     */
    public function __construct(RequestDataInterface $request, SignUpJsonView $view, LayoutInterface $layout)
    {
        $this->request = $request;
        $this->view = $view;
        $this->layout = $layout;
    }

    /**
     * Запрос на регистрацию
     * @return $this
     * @throws ApplicationError
     */
    public function index(): self
    {
        try {

            $login = trim($this->request->getString("login", 255) ?? "");
            if (!$login) {
                throw new ControllerException("Не указан логин пользователя");
            }

            $password = $this->request->getString("password", 255) ?? "";
            if (!$password) {
                throw new ControllerException("Не указан пароль пользователя");
            }

            $password_confirm = $this->request->getString("password_confirm", 255) ?? "";
            if (!$password_confirm) {
                throw new ControllerException("Не указан пароль пользователя");
            }

            if ($password !== $password_confirm) {
                throw new ControllerException("Пароль и подтверждение пароля не совпадают");
            }

            $email = $this->request->getString("email", 255);
            if (!$email) {
                throw new ControllerException("Не указан e-mail пользователя");
            }

            $exists = new GetUserUsingLogin($login)->getUser();
            if ($exists) {
                throw new ControllerException("Пользователь с таким логином уже существует");
            }

            $user = new User();
            $user->uuid = Uuid::uuid7()->toString();
            $user->login = $login;
            $user->password_hash = password_hash($password, PASSWORD_DEFAULT);
            $user->email = $email;
            $user->status = UserStatusEnum::Validation->name;
            $user->sign_up_datetime_utc = Dt::now()->format(DateTimeInterface::ATOM);

            new UserStorage($user)
                ->add()
            ;


        } catch (ControllerException $e) {
            $this->view->result->addError($e->getMessage());
        }

        return $this;
    }
}