<?php
declare(strict_types = 1);

namespace Innmind\OpenAPI\SecurityScheme\Http;

/**
 * @psalm-immutable
 */
enum Scheme
{
    case bearer;
}
