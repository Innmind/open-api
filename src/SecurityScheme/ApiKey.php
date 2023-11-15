<?php
declare(strict_types = 1);

namespace Innmind\OpenAPI\SecurityScheme;

use Innmind\OpenAPI\SecurityScheme\ApiKey\In;

/**
 * @psalm-immutable
 */
final class ApiKey
{
    private In $in;
    /** @var non-empty-string */
    private string $name;

    /**
     * @param non-empty-string $name
     */
    private function __construct(In $in, string $name)
    {
        $this->in = $in;
        $this->name = $name;
    }

    /**
     * @psalm-pure
     *
     * @param non-empty-string $name
     */
    public static function query(string $name): self
    {
        return new self(In::query, $name);
    }

    /**
     * @psalm-pure
     *
     * @param non-empty-string $name
     */
    public static function header(string $name): self
    {
        return new self(In::header, $name);
    }

    /**
     * @psalm-pure
     *
     * @param non-empty-string $name
     */
    public static function cookie(string $name): self
    {
        return new self(In::cookie, $name);
    }

    /**
     * @return array{type: non-empty-string, name: non-empty-string, in: non-empty-string}
     */
    public function toArray(): array
    {
        return [
            'type' => 'apiKey',
            'name' => $this->name,
            'in' => $this->in->name,
        ];
    }
}
