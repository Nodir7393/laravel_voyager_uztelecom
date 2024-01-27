<?php

namespace App\Events;

use App\Models\User;

abstract class UserEvent
{
    /** @var User */
    private $user;

    public function __construct(User $user) {
        $this->user = $user;
    }

    public function getUser(): User {
        return $this->user;
    }
}
