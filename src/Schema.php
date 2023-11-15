<?php
declare(strict_types = 1);

namespace Innmind\OpenAPI;

use Innmind\OpenAPI\Type\{
    Shape,
    Sequence,
    Str,
    Uuid,
    Password,
    Url,
    Date,
    DateTime,
    File,
    Integer,
    Number,
};

interface Schema extends \UnitEnum
{
    /**
     * @return non-empty-string
     */
    public function name(): string;
    public function type(): Shape|Sequence|Str|Uuid|Password|Url|Date|DateTime|File|Integer|Number;
}
