<?php

namespace App\Enums;

enum BankAccountType: string
{
    case SAVINGS = 'savings';
    case CURRENT = 'current';
}