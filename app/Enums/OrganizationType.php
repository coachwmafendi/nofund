<?php

namespace App\Enums;

enum OrganizationType: string
{
    case MOSQUE = 'mosque';
    case NGO = 'ngo';
    case CHARITY = 'charity';
    case COMMUNITY = 'community';
    case INDIVIDUAL = 'individual';
}