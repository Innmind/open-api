<?php
declare(strict_types = 1);

namespace Innmind\OpenAPI\SecurityScheme\ApiKey;

/**
 * @psalm-immutable
 */
enum In
{
    case query;
    case header;
    case cookie;
}
