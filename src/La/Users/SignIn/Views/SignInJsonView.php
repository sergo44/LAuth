<?php

declare(strict_types=1);

namespace La\Users\SignIn\Views;

use La\FrontController\ViewInterface;
use La\Users\AccessToken;
use La\Users\User;
use La\Views\JsonView;

class SignInJsonView extends JsonView implements ViewInterface, \JsonSerializable
{
    /**
     * Авторизованный пользователь
     * @var User|null
     */
    public ?User $user = null;

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return array(
            "result" => $this->result,
            "payload" => array (
                "user" => $this->user,
                "access_token" => $this->user ? new AccessToken($this->user?->uuid) : null
            )
        );
    }
}