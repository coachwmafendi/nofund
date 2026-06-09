<?php

namespace App\Enums;

enum RecurringPlanStatus: string
{
    case ACTIVE = 'active';
    case PAUSED = 'paused';
    case CANCELLED = 'cancelled';
    case EXPIRED = 'expired';
}