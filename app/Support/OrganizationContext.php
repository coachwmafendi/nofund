<?php

namespace App\Support;

class OrganizationContext
{
    private static ?string $organizationId = null;

    public static function setId(?string $id): void
    {
        static::$organizationId = $id;
    }

    public static function getId(): ?string
    {
        return static::$organizationId;
    }
}