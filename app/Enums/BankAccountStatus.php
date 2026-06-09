<?php

namespace App\Enums;

enum BankAccountStatus: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
}