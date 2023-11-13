<?php
declare(strict_types = 1);

namespace Innmind\OpenAPI;

use Innmind\UrlTemplate\Template;
use Innmind\Immutable\Sequence;

final class Path
{
    private Template $template;
    /** @var Sequence<Operation> */
    private Sequence $operations;

    /**
     * @psalm-mutation-free
     *
     * @param Sequence<Operation> $operations
     */
    private function __construct(
        Template $template,
        Sequence $operations,
    ) {
        $this->template = $template;
        $this->operations = $operations;
    }

    /**
     * @psalm-pure
     */
    public static function of(
        Template $template,
        Operation $operation,
        Operation ...$operations,
    ): self {
        return new self($template, Sequence::of($operation, ...$operations));
    }
}
