<?php
declare(strict_types = 1);

namespace Innmind\OpenAPI\Type;

use Innmind\OpenAPI\Type;
use Innmind\TimeContinuum\Clock;
use Innmind\Validation\{
    Constraint,
    Failure,
    Of,
};
use Innmind\Url\Url as Model;
use Innmind\Immutable\Validation;

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

    public function constraint(Clock $clock): Constraint
    {
        return $this->type->constraint($clock)->and(Of::callable(
            static fn(string $string) => Model::maybe($string)->match(
                static fn($url) => Validation::success($url),
                static fn() => Validation::fail(Failure::of('String is not a valid url')),
            ),
        ));
    }

    public function toArray(): array
    {
        $type = $this->type->toArray();
        $type['format'] = 'uri';

        return $type;
    }
}
