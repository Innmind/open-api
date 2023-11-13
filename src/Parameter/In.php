<?php
declare(strict_types = 1);

namespace Innmind\OpenAPI\Parameter;

/**
 * @psalm-immutable
 */
enum In
{
    case query;
    case header;
    case path;
    case cookie;
}
