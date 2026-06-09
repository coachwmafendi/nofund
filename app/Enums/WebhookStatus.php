<?php

namespace App\Enums;

enum WebhookStatus: string
{
    case ACTIVE = 'active';
    case PAUSED = 'paused';
}