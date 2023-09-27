<?php

namespace Savannabits\Saas\Concerns\Model;

use Illuminate\Database\Eloquent\Model;
use Savannabits\Saas\Models\CodeFactory;

trait HasCodeFactory
{
    abstract public function getCodePrefix(): string;

    public static function getCodeColumnName(): string
    {
        return config('vanadiphp.default_code_column_name', 'code');
    }

    public function getCodePadLength(): int
    {
        return 2;
    }

    public function getCodePadString(): string
    {
        return '0';
    }

    public function shouldOmitPrefix(): bool
    {
        return false; // Override this to change
    }

    public static function bootHasCodeFactory(): void
    {
        static::creating(function (Model $model) {
            $code = $model::getCodeColumnName() ?? 'code';
            if (! $model->{$code}) {
                $model->{$code} = CodeFactory::generate(
                    $model->getCodePrefix(),
                    $model->getCodePadLength(),
                    $model->getCodePadString(),
                    $model->shouldOmitPrefix()
                );
            }
        });
    }
}
