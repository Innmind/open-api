<?php
declare(strict_types = 1);

namespace Innmind\OpenAPI;

use Innmind\OpenAPI\Parameter\In;

/**
 * @psalm-immutable
 */
final class Parameter
{
    private In $in;
    /** @var non-empty-string */
    private string $name;
    private ?string $description;
    private bool $required;

    /**
     * @param non-empty-string $name
     */
    private function __construct(
        In $in,
        string $name,
        ?string $description,
        bool $required,
    ) {
        $this->in = $in;
        $this->name = $name;
        $this->description = $description;
        $this->required = $required;
    }

    /**
     * @psalm-pure
     *
     * @param non-empty-string $name
     */
    public static function query(string $name, string $description = null): self
    {
        return new self(
            In::query,
            $name,
            $description,
            false,
        );
    }

    /**
     * @psalm-pure
     *
     * @param non-empty-string $name
     */
    public static function header(string $name, string $description = null): self
    {
        return new self(
            In::header,
            $name,
            $description,
            false,
        );
    }

    /**
     * @psalm-pure
     *
     * @param non-empty-string $name
     */
    public static function path(string $name, string $description = null): self
    {
        return new self(
            In::path,
            $name,
            $description,
            false,
        );
    }

    /**
     * @psalm-pure
     *
     * @param non-empty-string $name
     */
    public static function cookie(string $name, string $description = null): self
    {
        return new self(
            In::cookie,
            $name,
            $description,
            false,
        );
    }

    public function require(): self
    {
        return new self(
            $this->in,
            $this->name,
            $this->description,
            true,
        );
    }
}
