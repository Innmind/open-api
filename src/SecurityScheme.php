<?php
declare(strict_types = 1);

namespace Innmind\OpenAPI;

use Innmind\OpenAPI\SecurityScheme\{
    ApiKey,
    Http,
};

/**
 * @psalm-immutable
 */
interface SecurityScheme extends \UnitEnum
{
    /**
     * @return non-empty-string
     */
    public function name(): string;
    public function type(): ApiKey|Http;
    public function description(): ?string;
}
