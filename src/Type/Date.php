<?php
declare(strict_types = 1);

namespace Innmind\OpenAPI\Type;

/**
 * @psalm-immutable
 */
final class Date
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

    public function nullable(): self
    {
        return new self($this->type->nullable());
    }
}
