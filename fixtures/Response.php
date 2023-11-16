<?php
declare(strict_types = 1);

namespace Fixtures\Innmind\OpenAPI;

use Innmind\OpenAPI\{
    Response\Definition,
    Response\Reference,
    Type\Shape,
    Type\Str,
};
use Innmind\MediaType\MediaType;

enum Response implements Reference
{
    case apiUnavailable;

    public function name(): string
    {
        return $this->name;
    }

    public function definition(): Definition
    {
        return Definition::of(
            MediaType::of('application/json'),
            Shape::of()
                ->property('message', Str::of())
                ->require('message'),
            'Api is under maintenance',
        );
    }
}
