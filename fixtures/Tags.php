<?php
declare(strict_types = 1);

namespace Fixtures\Innmind\OpenAPI;

use Innmind\OpenAPI\Tag;

enum Tags implements Tag
{
    case foo;
    case bar;

    public function name(): string
    {
        return $this->name;
    }

    public function description(): ?string
    {
        return match ($this) {
            self::foo => 'foo description',
            default => null,
        };
    }
}
