<?php
declare(strict_types = 1);

namespace Innmind\OpenAPI\SecurityScheme;

use Innmind\OpenAPI\SecurityScheme\Http\Scheme;

/**
 * @psalm-immutable
 */
final class Http
{
    private Scheme $scheme;

    private function __construct(Scheme $scheme)
    {
        $this->scheme = $scheme;
    }

    /**
     * @psalm-pure
     */
    public static function bearer(): self
    {
        return new self(Scheme::bearer);
    }

    /**
     * @return array{type: non-empty-string, scheme: non-empty-string}
     */
    public function toArray(): array
    {
        return [
            'type' => 'http',
            'scheme' => $this->scheme->name,
        ];
    }
}
