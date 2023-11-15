<?php
declare(strict_types = 1);

namespace Innmind\OpenAPI;

use Innmind\Http\Method;
use Innmind\Immutable\{
    Sequence,
    Set,
};

final class Operation
{
    private Method $method;
    /** @var ?non-empty-string */
    private ?string $id;
    private ?string $summary;
    private ?string $description;
    /** @var Set<Tag> */
    private Set $tags;
    private bool $disableSecurity;
    /** @var Sequence<Parameter> */
    private Sequence $parameters;
    /** @var Sequence<Request> */
    private Sequence $requests;
    /** @var Sequence<Response> */
    private Sequence $responses;

    /**
     * @psalm-mutation-free
     *
     * @param ?non-empty-string $id
     * @param Set<Tag> $tags
     * @param Sequence<Parameter> $parameters
     * @param Sequence<Request> $requests
     * @param Sequence<Response> $responses
     */
    private function __construct(
        Method $method,
        ?string $summary,
        ?string $description,
        ?string $id,
        Set $tags,
        bool $disableSecurity,
        Sequence $parameters,
        Sequence $requests,
        Sequence $responses,
    ) {
        $this->method = $method;
        $this->id = $id;
        $this->summary = $summary;
        $this->description = $description;
        $this->tags = $tags;
        $this->disableSecurity = $disableSecurity;
        $this->parameters = $parameters;
        $this->requests = $requests;
        $this->responses = $responses;
    }

    /**
     * @psalm-pure
     *
     * @param ?non-empty-string $id
     */
    public static function get(
        string $summary = null,
        string $description = null,
        string $id = null,
    ): self {
        return new self(
            Method::get,
            $summary,
            $description,
            $id,
            Set::of(),
            false,
            Sequence::of(),
            Sequence::of(),
            Sequence::of(),
        );
    }

    /**
     * @psalm-pure
     *
     * @param ?non-empty-string $id
     */
    public static function put(
        string $summary = null,
        string $description = null,
        string $id = null,
    ): self {
        return new self(
            Method::put,
            $summary,
            $description,
            $id,
            Set::of(),
            false,
            Sequence::of(),
            Sequence::of(),
            Sequence::of(),
        );
    }

    /**
     * @psalm-pure
     *
     * @param ?non-empty-string $id
     */
    public static function post(
        string $summary = null,
        string $description = null,
        string $id = null,
    ): self {
        return new self(
            Method::post,
            $summary,
            $description,
            $id,
            Set::of(),
            false,
            Sequence::of(),
            Sequence::of(),
            Sequence::of(),
        );
    }

    /**
     * @psalm-pure
     *
     * @param ?non-empty-string $id
     */
    public static function delete(
        string $summary = null,
        string $description = null,
        string $id = null,
    ): self {
        return new self(
            Method::delete,
            $summary,
            $description,
            $id,
            Set::of(),
            false,
            Sequence::of(),
            Sequence::of(),
            Sequence::of(),
        );
    }

    /**
     * @psalm-pure
     *
     * @param ?non-empty-string $id
     */
    public static function options(
        string $summary = null,
        string $description = null,
        string $id = null,
    ): self {
        return new self(
            Method::options,
            $summary,
            $description,
            $id,
            Set::of(),
            false,
            Sequence::of(),
            Sequence::of(),
            Sequence::of(),
        );
    }

    /**
     * @psalm-pure
     *
     * @param ?non-empty-string $id
     */
    public static function head(
        string $summary = null,
        string $description = null,
        string $id = null,
    ): self {
        return new self(
            Method::head,
            $summary,
            $description,
            $id,
            Set::of(),
            false,
            Sequence::of(),
            Sequence::of(),
            Sequence::of(),
        );
    }

    /**
     * @psalm-pure
     *
     * @param ?non-empty-string $id
     */
    public static function patch(
        string $summary = null,
        string $description = null,
        string $id = null,
    ): self {
        return new self(
            Method::patch,
            $summary,
            $description,
            $id,
            Set::of(),
            false,
            Sequence::of(),
            Sequence::of(),
            Sequence::of(),
        );
    }

    /**
     * @psalm-pure
     *
     * @param ?non-empty-string $id
     */
    public static function trace(
        string $summary = null,
        string $description = null,
        string $id = null,
    ): self {
        return new self(
            Method::trace,
            $summary,
            $description,
            $id,
            Set::of(),
            false,
            Sequence::of(),
            Sequence::of(),
            Sequence::of(),
        );
    }

    /**
     * @psalm-mutation-free
     */
    public function tags(Tag $tag, Tag ...$tags): self
    {
        return new self(
            $this->method,
            $this->summary,
            $this->description,
            $this->id,
            $this->tags->merge(Set::of($tag, ...$tags)),
            $this->disableSecurity,
            $this->parameters,
            $this->requests,
            $this->responses,
        );
    }

    /**
     * @psalm-mutation-free
     */
    public function disableSecurity(): self
    {
        return new self(
            $this->method,
            $this->summary,
            $this->description,
            $this->id,
            $this->tags,
            true,
            $this->parameters,
            $this->requests,
            $this->responses,
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
            $this->method,
            $this->summary,
            $this->description,
            $this->id,
            $this->tags,
            $this->disableSecurity,
            $this->parameters->append(Sequence::of($parameter, ...$parameters)),
            $this->requests,
            $this->responses,
        );
    }

    /**
     * @psalm-mutation-free
     */
    public function requests(
        Request $request,
        Request ...$requests,
    ): self {
        return new self(
            $this->method,
            $this->summary,
            $this->description,
            $this->id,
            $this->tags,
            $this->disableSecurity,
            $this->parameters,
            $this->requests->append(Sequence::of($request, ...$requests)),
            $this->responses,
        );
    }

    /**
     * @psalm-mutation-free
     */
    public function responses(
        Response $response,
        Response ...$responses,
    ): self {
        return new self(
            $this->method,
            $this->summary,
            $this->description,
            $this->id,
            $this->tags,
            $this->disableSecurity,
            $this->parameters,
            $this->requests,
            $this->responses->append(Sequence::of($response, ...$responses)),
        );
    }

    public function toArray(): array
    {
        $operation = [];

        if (\is_string($this->summary)) {
            $operation['summary'] = $this->summary;
        }

        if (\is_string($this->description)) {
            $operation['description'] = $this->description;
        }

        if (\is_string($this->id)) {
            $operation['operationId'] = $this->id;
        }

        if (!$this->tags->empty()) {
            $operation['tags'] = $this
                ->tags
                ->map(static fn($tag) => $tag->name())
                ->toList();
        }

        if ($this->disableSecurity) {
            $operation['security'] = [];
        }

        if (!$this->parameters->empty()) {
            $operation['parameters'] = $this
                ->parameters
                ->map(static fn($parameter) => $parameter->toArray())
                ->toList();
        }

        if (!$this->requests->empty()) {
            $operation['requestBody'] = \array_merge(
                ...$this
                    ->requests
                    ->map(static fn($request) => $request->toArray())
                    ->toList(),
            );
            $operation['requestBody']['required'] = true;
        }

        if (!$this->responses->empty()) {
            $operation['responses'] = \array_merge(
                ...$this
                    ->responses
                    ->map(static fn($response) => $response->toArray())
                    ->toList(),
            );
        }

        return [$this->method->name => $operation];
    }
}
