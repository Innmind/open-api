<?php
declare(strict_types = 1);

namespace Fixtures\Innmind\OpenAPI;

use Innmind\OpenAPI\{
    SecurityScheme,
    SecurityScheme\ApiKey,
    SecurityScheme\Http,
};

enum Security implements SecurityScheme
{
    case foo;
    case bar;

    public function name(): string
    {
        return $this->name;
    }

    public function type(): ApiKey|Http
    {
        return match ($this) {
            self::foo => Http::bearer(),
            self::bar => ApiKey::header('x-api-key'),
        };
    }

    public function description(): ?string
    {
        return match($this) {
            self::foo => null,
            self::bar => 'bar description',
        };
    }
}
