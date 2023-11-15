<?php
declare(strict_types = 1);

namespace Innmind\OpenAPI\Type;

use Innmind\Immutable\{
    RegExp,
    Str as S,
};

/**
 * @psalm-immutable
 */
final class Str
{
    private ?string $title;
    private ?string $description;
    private ?string $example;
    private bool $nullable;
    private ?RegExp $pattern;

    private function __construct(
        ?string $title,
        ?string $description,
        ?string $example,
        bool $nullable,
        ?RegExp $pattern,
    ) {
        $this->title = $title;
        $this->description = $description;
        $this->example = $example;
        $this->nullable = $nullable;
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
            false,
            null,
        );
    }

    public function example(string $example): self
    {
        return new self(
            $this->title,
            $this->description,
            $example,
            $this->nullable,
            $this->pattern,
        );
    }

    public function nullable(): self
    {
        return new self(
            $this->title,
            $this->description,
            $this->example,
            true,
            $this->pattern,
        );
    }

    public function restrictVia(RegExp $pattern): self
    {
        return new self(
            $this->title,
            $this->description,
            $this->example,
            $this->nullable,
            $pattern,
        );
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

        if ($this->nullable) {
            $type['nullable'] = $this->nullable;
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
