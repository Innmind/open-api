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
    /** @var Sequence<Parameter> */
    private Sequence $parameters;

    /**
     * @psalm-mutation-free
     *
     * @param Sequence<Operation> $operations
     * @param Sequence<Parameter> $parameters
     */
    private function __construct(
        Template $template,
        Sequence $operations,
        Sequence $parameters,
    ) {
        $this->template = $template;
        $this->operations = $operations;
        $this->parameters = $parameters;
    }

    /**
     * @psalm-pure
     */
    public static function of(
        Template $template,
        Operation $operation,
        Operation ...$operations,
    ): self {
        return new self(
            $template,
            Sequence::of($operation, ...$operations),
            Sequence::of(),
        );
    }

    /**
     * @psalm-mutation-free
     */
    public function parameters(
        Parameter $parameter,
        Parameter ...$parameters,
    ): self {
        return new self(
            $this->template,
            $this->operations,
            $this->parameters->append(Sequence::of($parameter, ...$parameters)),
        );
    }

    public function toArray(): array
    {
        $description = \array_merge(
            ...$this
                ->operations
                ->map(static fn($operation) => $operation->toArray())
                ->toList(),
        );

        if (!$this->parameters->empty()) {
            $description['parameters'] = $this
                ->parameters
                ->map(static fn($parameter) => $parameter->toArray())
                ->toList();
        }

        return [$this->template->toString() => $description];
    }
}
