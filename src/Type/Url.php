<?php
declare(strict_types = 1);

namespace Innmind\OpenAPI\Type;

use Innmind\OpenAPI\Type;
use Innmind\Validation\Constraint;
use Innmind\Url\Url as Model;

/**
 * @psalm-immutable
 * @implements Type<Model>
 */
final class Url implements Type
{
    private Str $type;

    private function __construct(Str $type)
    {
        $this->type = $type;
    }

    /**
     * @psalm-pure
     */
    public static function of(string $title = null, string $description = null): self
    {
        return new self(Str::of(
            $title,
            $description,
        ));
    }

    public function example(string $example): self
    {
        return new self($this->type->example($example));
    }

    public function nullable(): Nullable
    {
        return Nullable::of($this);
    }

    public function constraint(): Constraint
    {
        // TODO add the type transformation
        return $this->type->constraint();
    }

    public function toArray(): array
    {
        $type = $this->type->toArray();
        $type['format'] = 'uri';

        return $type;
    }
}
