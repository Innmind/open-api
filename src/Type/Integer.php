<?php
declare(strict_types = 1);

namespace Innmind\OpenAPI\Type;

use Innmind\OpenAPI\Type;
use Innmind\TimeContinuum\Clock;
use Innmind\Validation\{
    Constraint,
    Is,
};

/**
 * @psalm-immutable
 * @implements Type<int>
 */
final class Integer implements Type
{
    private ?string $title;
    private ?string $description;
    private ?int $example;

    private function __construct(
        ?string $title,
        ?string $description,
        ?int $example,
    ) {
        $this->title = $title;
        $this->description = $description;
        $this->example = $example;
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
        );
    }

    public function example(int $example): self
    {
        return new self(
            $this->title,
            $this->description,
            $example,
        );
    }

    public function nullable(): Nullable
    {
        return Nullable::of($this);
    }

    public function constraint(Clock $clock): Constraint
    {
        return Is::int();
    }

    public function toArray(): array
    {
        $type = ['type' => 'integer'];

        if (\is_string($this->title)) {
            $type['title'] = $this->title;
        }

        if (\is_string($this->description)) {
            $type['description'] = $this->description;
        }

        if (\is_int($this->example)) {
            $type['example'] = $this->example;
        }

        return $type;
    }
}
