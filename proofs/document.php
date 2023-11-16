<?php
declare(strict_types = 1);

use Innmind\OpenAPI\{
    OpenAPI,
    Path,
    Operation,
    Response,
    Request,
    Type\Shape,
    Type\Str,
    Type\Password,
};
use Innmind\Http\Response\StatusCode;
use Innmind\UrlTemplate\Template;
use Innmind\MediaType\MediaType;
use Innmind\Immutable\Sequence;
use Innmind\BlackBox\Set;
use Fixtures\Innmind\OpenAPI\{
    Tags,
    Security,
    Response as Responses,
    Schemas,
};
use Fixtures\Innmind\Url\Url;

return static function() {
    yield proof(
        'Document::toArray()',
        given(
            Set\Strings::atLeast(1),
            Set\Strings::atLeast(1),
            Set\Strings::any(),
            Url::any(),
            Set\Strings::any(),
        ),
        static function(
            $assert,
            $title,
            $version,
            $description,
            $server,
            $serverDescription,
        ) {
            $document = OpenAPI::v3
                ->info($title, $version, $description)
                ->server($server, $serverDescription)
                ->tags(...Tags::cases())
                ->securitySchemes(...Security::cases())
                ->responses(Responses::apiUnavailable)
                ->schemas(Schemas::login)
                ->paths(Sequence::of(
                    Path::of(
                        Template::of('/api/login'),
                        Operation::post()
                            ->tags(Tags::foo)
                            ->disableSecurity()
                            ->requests(Request::of(
                                MediaType::of('application/json'),
                                Shape::of()
                                    ->property('username', Str::of())
                                    ->property('password', Password::of())
                                    ->require('username')
                                    ->require('password'),
                            ))
                            ->responses(
                                Response::of(StatusCode::ok)->sends(
                                    MediaType::of('application/json'),
                                    Schemas::login,
                                ),
                                Response::of(StatusCode::serviceUnavailable)->references(
                                    Responses::apiUnavailable,
                                ),
                            ),
                    ),
                ))
                ->toArray();

            $assert->same(
                [
                    'openapi' => '3.1.0',
                    'info' => [
                        'title' => $title,
                        'version' => $version,
                        'description' => $description,
                    ],
                    'servers' => [[
                        'url' => $server->toString(),
                        'description' => $serverDescription,
                    ]],
                    'tags' => [
                        ['name' => 'foo', 'description' => 'foo description'],
                        ['name' => 'bar'],
                    ],
                    'paths' => [
                        '/api/login' => [
                            'post' => [
                                'tags' => ['foo'],
                                'security' => [],
                                'requestBody' => [
                                    'required' => true,
                                    'content' => [
                                        'application/json' => [
                                            'type' => 'object',
                                            'required' => ['username', 'password'],
                                            'properties' => [
                                                'username' => [
                                                    'type' => 'string',
                                                ],
                                                'password' => [
                                                    'type' => 'string',
                                                    'format' => 'password',
                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                                'responses' => [
                                    '200' => [
                                        'content' => [
                                            'application/json' => [
                                                'schema' => [
                                                    '$ref' => '#/components/schemas/login',
                                                ],
                                            ],
                                        ],
                                    ],
                                    '503' => [
                                        '$ref' => '#/components/responses/apiUnavailable',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'components' => [
                        'securitySchemes' => [
                            'foo' => [
                                'type' => 'http',
                                'scheme' => 'bearer',
                            ],
                            'bar' => [
                                'type' => 'apiKey',
                                'name' => 'x-api-key',
                                'in' => 'header',
                                'description' => 'bar description',
                            ],
                        ],
                        'responses' => [
                            'apiUnavailable' => [
                                'content' => [
                                    'application/json' => [
                                        'schema' => [
                                            'type' => 'object',
                                            'required' => ['message'],
                                            'properties' => [
                                                'message' => [
                                                    'type' => 'string',
                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                                'description' => 'Api is under maintenance',
                            ],
                        ],
                        'schemas' => [
                            'login' => [
                                'type' => 'object',
                                'required' => ['token'],
                                'properties' => [
                                    'token' => [
                                        'type' => 'string',
                                        'example' => 'j.w.t',
                                    ],
                                ],
                                'description' => 'JWT generated for the user',
                            ],
                        ],
                    ],
                ],
                $document,
            );
        },
    );
};
