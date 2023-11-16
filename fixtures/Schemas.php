<?php
declare(strict_types = 1);

namespace Fixtures\Innmind\OpenAPI;

use Innmind\OpenAPI\{
    Schema,
    Type\Shape,
    Type\Sequence,
    Type\Str,
    Type\Uuid,
    Type\Password,
    Type\Url,
    Type\Date,
    Type\DateTime,
    Type\File,
    Type\Integer,
    Type\Number,
};

enum Schemas implements Schema
{
    case login;

    public function name(): string
    {
        return $this->name;
    }

    public function type(): Shape|Sequence|Str|Uuid|Password|Url|Date|DateTime|File|Integer|Number
    {
        return Shape::of(description: 'JWT generated for the user')
            ->property('token', Str::of()->example('j.w.t'))
            ->require('token');
    }
}
