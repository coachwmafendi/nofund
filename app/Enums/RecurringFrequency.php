<?php

namespace App\Enums;

enum RecurringFrequency: string
{
    case WEEKLY = 'weekly';
    case MONTHLY = 'monthly';
    case YEARLY = 'yearly';
}