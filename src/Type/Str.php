<?php
declare(strict_types = 1);

namespace Innmind\OpenAPI\Type;

use Innmind\OpenAPI\Type;
use Innmind\TimeContinuum\Clock;
use Innmind\Validation\{
    Constraint,
    Is,
};
use Innmind\Immutable\{
    RegExp,
    Str as S,
};

/**
 * @psalm-immutable
 * @implements Type<string>
 */
final class Str implements Type
{
    private ?string $title;
    private ?string $description;
    private ?string $example;
    private ?RegExp $pattern;

    private function __construct(
        ?string $title,
        ?string $description,
        ?string $example,
        ?RegExp $pattern,
    ) {
        $this->title = $title;
        $this->description = $description;
        $this->example = $example;
        $this->pattern = $pattern;
    }

    /**
     * @psalm-pure
     */
    public static function of(string $title = null, string $description = null): self
    {
        return new self(
            $title,
            $description,
            null,
            null,
        );
    }

    public function example(string $example): self
    {
        return new self(
            $this->title,
            $this->description,
            $example,
            $this->pattern,
        );
    }

    public function nullable(): Nullable
    {
        return Nullable::of($this);
    }

    public function map(callable $map): Type
    {
        return Map::of($this, $map);
    }

    public function restrictVia(RegExp $pattern): self
    {
        return new self(
            $this->title,
            $this->description,
            $this->example,
            $pattern,
        );
    }

    public function constraint(Clock $clock): Constraint
    {
        return Is::string();
    }

    public function toArray(): array
    {
        $type = ['type' => 'string'];

        if (\is_string($this->title)) {
            $type['title'] = $this->title;
        }

        if (\is_string($this->description)) {
            $type['description'] = $this->description;
        }

        if (\is_string($this->example)) {
            $type['example'] = $this->example;
        }

        if ($this->pattern) {
            $pattern = S::of($this->pattern->toString());
            $delimiter = $pattern->take(1)->toString();
            $pattern = match ($pattern->endsWith($delimiter)) {
                true => $pattern->dropEnd(1),
                false => S::of($delimiter)->join(
                    $pattern
                        ->split($delimiter)
                        ->map(static fn($part) => $part->toString()),
                ),
            };
            $type['pattern'] = $pattern->toString();
        }

        return $type;
    }
}
