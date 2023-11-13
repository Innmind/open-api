<?php
declare(strict_types = 1);

namespace Innmind\OpenAPI;

/**
 * @psalm-immutable
 */
final class Info
{
    /** @var non-empty-string */
    private string $title;
    /** @var non-empty-string */
    private string $version;
    private ?string $description;

    /**
     * @param non-empty-string $title
     * @param non-empty-string $version
     */
    private function __construct(
        string $title,
        string $version,
        ?string $description,
    ) {
        $this->title = $title;
        $this->version = $version;
        $this->description = $description;
    }

    /**
     * @psalm-pure
     *
     * @param non-empty-string $title
     * @param non-empty-string $version
     */
    public static function of(
        string $title,
        string $version,
        string $description = null,
    ): self {
        return new self($title, $version, $description);
    }
}
