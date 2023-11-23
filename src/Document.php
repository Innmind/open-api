<?php
declare(strict_types = 1);

namespace Innmind\OpenAPI;

use Innmind\Router\Route;
use Innmind\Url\Url;
use Innmind\Immutable\{
    Sequence,
    Set,
};

final class Document
{
    private OpenAPI $version;
    private ?Info $info;
    /** @var Sequence<array{Url, ?string}> */
    private Sequence $servers;
    /** @var Set<Tag> */
    private Set $tags;
    /** @var Set<SecurityScheme> */
    private Set $securitySchemes;
    /** @var Set<Response\Reference> */
    private Set $responses;
    /** @var Set<Schema> */
    private Set $schemas;
    /** @var Sequence<Path> */
    private Sequence $paths;

    /**
     * @psalm-mutation-free
     *
     * @param Sequence<array{Url, ?string}> $servers
     * @param Set<Tag> $tags
     * @param Set<SecurityScheme> $securitySchemes
     * @param Set<Response\Reference> $responses
     * @param Set<Schema> $schemas
     * @param Sequence<Path> $paths
     */
    private function __construct(
        OpenAPI $version,
        ?Info $info,
        Sequence $servers,
        Set $tags,
        Set $securitySchemes,
        Set $responses,
        Set $schemas,
        Sequence $paths,
    ) {
        $this->version = $version;
        $this->info = $info;
        $this->servers = $servers;
        $this->tags = $tags;
        $this->securitySchemes = $securitySchemes;
        $this->responses = $responses;
        $this->schemas = $schemas;
        $this->paths = $paths;
    }

    /**
     * @psalm-pure
     */
    public static function of(OpenAPI $version): self
    {
        // Use a lazy Sequence for paths to allow not keep all of them in memory
        // when no longer used as it can become quite large
        return new self(
            $version,
            null,
            Sequence::of(),
            Set::of(),
            Set::of(),
            Set::of(),
            Set::of(),
            Sequence::lazyStartingWith(),
        );
    }

    /**
     * @psalm-mutation-free
     *
     * @param non-empty-string $title
     * @param non-empty-string $version
     */
    public function info(
        string $title,
        string $version,
        string $description = null,
    ): self {
        return new self(
            $this->version,
            Info::of($title, $version, $description),
            $this->servers,
            $this->tags,
            $this->securitySchemes,
            $this->responses,
            $this->schemas,
            $this->paths,
        );
    }

    /**
     * @psalm-mutation-free
     */
    public function server(Url $server, string $description = null): self
    {
        return new self(
            $this->version,
            $this->info,
            ($this->servers)([$server, $description]),
            $this->tags,
            $this->securitySchemes,
            $this->responses,
            $this->schemas,
            $this->paths,
        );
    }

    /**
     * @psalm-mutation-free
     */
    public function tags(Tag $tag, Tag ...$tags): self
    {
        return new self(
            $this->version,
            $this->info,
            $this->servers,
            $this->tags->merge(Set::of($tag, ...$tags)),
            $this->securitySchemes,
            $this->responses,
            $this->schemas,
            $this->paths,
        );
    }

    /**
     * @psalm-mutation-free
     */
    public function securitySchemes(
        SecurityScheme $scheme,
        SecurityScheme ...$schemes,
    ): self {
        return new self(
            $this->version,
            $this->info,
            $this->servers,
            $this->tags,
            $this->securitySchemes->merge(Set::of($scheme, ...$schemes)),
            $this->responses,
            $this->schemas,
            $this->paths,
        );
    }

    /**
     * @psalm-mutation-free
     */
    public function responses(
        Response\Reference $response,
        Response\Reference ...$responses,
    ): self {
        return new self(
            $this->version,
            $this->info,
            $this->servers,
            $this->tags,
            $this->securitySchemes,
            $this->responses->merge(Set::of($response, ...$responses)),
            $this->schemas,
            $this->paths,
        );
    }

    /**
     * @psalm-mutation-free
     */
    public function schemas(
        Schema $schema,
        Schema ...$schemas,
    ): self {
        return new self(
            $this->version,
            $this->info,
            $this->servers,
            $this->tags,
            $this->securitySchemes,
            $this->responses,
            $this->schemas->merge(Set::of($schema, ...$schemas)),
            $this->paths,
        );
    }

    /**
     * @psalm-mutation-free
     *
     * @param Sequence<Path> $paths
     */
    public function paths(Sequence $paths): self
    {
        return new self(
            $this->version,
            $this->info,
            $this->servers,
            $this->tags,
            $this->securitySchemes,
            $this->responses,
            $this->schemas,
            $this->paths->append($paths),
        );
    }

    /**
     * @return Sequence<Route>
     */
    public function routes(): Sequence
    {
        return $this->paths->flatMap(static fn($path) => $path->routes());
    }

    /**
     * @return array{
     *     openapi: non-empty-string,
     *     info?: array{
     *         title: non-empty-string,
     *         version: non-empty-string,
     *         description?: string,
     *     },
     *     servers?: list<array{url: string, description?: string}>,
     *     tags?: list<array{name: non-empty-string, description?: string}>,
     *     paths: array<non-empty-string, array>,
     *     components?: array{
     *         securitySchemes?: array<non-empty-string, array>,
     *         responses?: array<non-empty-string, array>,
     *         schemas?: array<non-empty-string, array>,
     *     },
     * }
     */
    public function toArray(): array
    {
        $document = ['openapi' => $this->version->toString()];

        if ($this->info) {
            $document['info'] = $this->info->toArray();
        }

        if (!$this->servers->empty()) {
            $document['servers'] = $this
                ->servers
                ->map(static fn($pair) => match ($pair[1]) {
                    null => ['url' => $pair[0]->toString()],
                    default => [
                        'url' => $pair[0]->toString(),
                        'description' => $pair[1],
                    ],
                })
                ->toList();
        }

        if (!$this->tags->empty()) {
            $document['tags'] = $this
                ->tags
                ->map(static fn($tag) => match ($tag->description()) {
                    null => ['name' => $tag->name()],
                    default => [
                        'name' => $tag->name(),
                        'description' => $tag->description(),
                    ],
                })
                ->toList();
        }

        $document['paths'] = \array_merge(
            ...$this
                ->paths
                ->map(static fn($path) => $path->toArray())
                ->toList(),
        );

        if (!$this->securitySchemes->empty()) {
            $document['components'] = [];
            $document['components']['securitySchemes'] = \array_merge(
                ...$this
                    ->securitySchemes
                    ->map(static fn($scheme) => match ($scheme->description()) {
                        null => [$scheme->name() => $scheme->type()->toArray()],
                        default => [$scheme->name() => \array_merge(
                            $scheme->type()->toArray(),
                            ['description' => $scheme->description()],
                        )],
                    })
                    ->toList(),
            );
        }

        if (!$this->responses->empty()) {
            $document['components'] ??= [];
            $document['components']['responses'] = \array_merge(
                ...$this
                    ->responses
                    ->map(static fn($response) => [
                        $response->name() => $response->definition()->toArray(),
                    ])
                    ->toList(),
            );
        }

        if (!$this->schemas->empty()) {
            $document['components'] ??= [];
            $document['components']['schemas'] = \array_merge(
                ...$this
                    ->schemas
                    ->map(static fn($schema) => [
                        $schema->name() => $schema->type()->toArray(),
                    ])
                    ->toList(),
            );
        }

        return $document;
    }
}
