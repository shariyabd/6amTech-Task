<?php

namespace App\Enums;

enum RoleType: string
{
    case ADMIN = 'admin';
    case MANAGER = 'manager';

    public function id(): int
    {
        return match ($this) {
            self::ADMIN   => 1,
            self::MANAGER => 2,
        };
    }
}
