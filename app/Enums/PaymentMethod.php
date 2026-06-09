<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case CARD = 'card';
    case BANK_TRANSFER = 'bank_transfer';
    case EWALLET = 'ewallet';
    case CASH = 'cash';
}