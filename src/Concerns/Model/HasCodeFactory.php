<?php

namespace Savannabits\Saas\Concerns\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Savannabits\Saas\Models\CodeFactory;

trait HasCodeFactory
{
    public static function getCodeColumnName(): string
    {
        return 'code';
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
            if (! $model->getAttribute(static::getCodeColumnName())) {
                $model->{static::getCodeColumnName()} = Str::uuid()->toString();
            }
        });
        static::created(function(Model $model) {
            if (Str::isUuid($model->getAttribute(static::getCodeColumnName()))) {
                $model = $model::withoutGlobalScopes()->where('id','=', $model->getAttribute('id'))->firstOrFail();
                $model->updateQuietly([static::getCodeColumnName() => $model->calculated_code]);
            }
        });
    }
    public function getCalculatedCodeAttribute(): string
    {
        $code = Str::of($this->code_id)->padLeft($this->getCodePadLength() ?: 2,$this->getCodePadString() ?: '0');
        if (!$this->shouldOmitPrefix()) {
            $code = $code->prepend($this->code_prefix)->upper();
        }
        return $code->toString();
    }
}
