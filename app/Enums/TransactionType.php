<?php

namespace App\Enums;

enum TransactionType: string
{
    case DONATION = 'donation';
    case REFUND = 'refund';
    case FEE = 'fee';
    case PAYOUT = 'payout';
    case ADJUSTMENT = 'adjustment';
}