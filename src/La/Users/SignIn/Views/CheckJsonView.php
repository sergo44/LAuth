<?php

declare(strict_types=1);

namespace La\Users\SignIn\Views;

use La\FrontController\ViewInterface;
use La\Users\User;
use La\Views\JsonView;

class CheckJsonView extends JsonView implements ViewInterface, \JsonSerializable
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
            "payload" => array(
                "user" => $this->user
            )
        );
    }
}