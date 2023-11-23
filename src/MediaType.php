<?php
declare(strict_types = 1);

namespace Innmind\OpenAPI;

/**
 * @psalm-immutable
 */
enum MediaType
{
    case json;
    case form;
    case multipart;

    /**
     * @return non-empty-string
     */
    public function toString(): string
    {
        return match ($this) {
            self::json => 'application/json',
            self::form => 'application/x-www-form-urlencoded',
            self::multipart => 'multipart/form-data',
        };
    }
}
