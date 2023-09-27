<?php

namespace Savannabits\Saas\Macros;

use Illuminate\Database\Schema\Blueprint;
use Kalnoy\Nestedset\NestedSet;

class UuidNestedSet extends NestedSet
{
    public static function columns(Blueprint $table): void
    {
        $table->unsignedInteger(self::LFT)->default(0);
        $table->unsignedInteger(self::RGT)->default(0);
        $table->foreignUuid(self::PARENT_ID)->nullable();

        $table->index(static::getDefaultColumns());
    }
}
