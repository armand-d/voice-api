<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;

class UserController
{
    public function __invoke()
    {
        dd('ok');
        return 'ok';
    }
}