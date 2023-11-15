<?php
declare(strict_types = 1);

namespace Innmind\OpenAPI\Response;

interface Reference extends \UnitEnum
{
    /**
     * @return non-empty-string
     */
    public function name(): string;
    public function definition(): Definition;
}
