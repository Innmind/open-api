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
 * @template T
 * @implements Type<?T>
 */
final class Nullable implements Type
{
    private Type $type;

    private function __construct(Type $type)
    {
        $this->type = $type;
    }

    /**
     * @psalm-pure
     */
    public static function of(Type $type): self
    {
        return new self($type);
    }

    public function nullable(): self
    {
        return $this;
    }

    public function constraint(Clock $clock): Constraint
    {
        return $this->type->constraint($clock)->or(Is::null());
    }

    public function toArray(): array
    {
        $type = $this->type->toArray();
        $type['nullable'] = true;

        return $type;
    }
}
