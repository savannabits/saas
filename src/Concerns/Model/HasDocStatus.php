<?php

namespace Savannabits\Saas\Concerns\Model;

use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Savannabits\Saas\Contracts\DocStatus;
use Savannabits\Saas\Models\DocumentCancellation;

trait HasDocStatus
{
    public static function bootHasDocStatus()
    {
        static::addGlobalScope('not-cancelled', function (Builder $builder) {
            $builder->where('doc_status', '!=', DocStatus::CANCELLED)
                ->whereNot('is_cancelled', '=', true);
        });
        static::creating(function (Model $model) {
            if (! $model->doc_status) {
                $model->doc_status = DocStatus::DRAFT;
            }
        });
        static::updating(function (Model $model) {
            abort_unless($model->isDraft(), 403, 'You can only update documents which are in DRAFT mode.');
        });

        static::deleting(function (Model $model) {
            abort_unless($model->isDraft(), 403, 'The Document cannot be deleted because it was already submitted.');
        });
    }

    protected function initializeHasDocStatus()
    {
        $this->casts['is_active'] = 'bool';
        $this->casts['is_cancelled'] = 'bool';
    }

    public function scopeWhereDraft(Builder $builder): Builder
    {
        return $builder->where('doc_status', '=', DocStatus::DRAFT);
    }

    public function scopeWhereSubmitted(Builder $builder): Builder
    {
        return $builder->where('doc_status', '=', DocStatus::SUBMITTED);
    }

    public function scopeWhereCancelled(Builder $builder): Builder
    {
        return $builder->where('doc_status', '=', DocStatus::CANCELLED)
            ->orWhere('is_cancelled', '=', true);
    }

    public function scopeWithCancelled(Builder $builder): Builder
    {
        return $builder->withoutGlobalScope('not-cancelled');
    }

    public function scopeOnlyCancelled(Builder $builder): Builder
    {
        return $builder->withoutGlobalScope('not-cancelled')->whereCancelled();
    }

    public function isDraft(): bool
    {
        return $this->doc_status === DocStatus::DRAFT;
    }

    public function isSubmitted(): bool
    {
        return $this->doc_status === DocStatus::SUBMITTED;
    }

    public function isCancelled(): bool
    {
        return $this->is_cancelled || $this->doc_status === DocStatus::CANCELLED;
    }

    public function isNotCancelled(): bool
    {
        return ! $this->isCancelled();
    }

    public function submit($onlyIfDraft = true): static
    {
        if ($onlyIfDraft && ! $this->isDraft()) {
            return $this;
        }
        abort_unless($this->isDraft(), 403, 'Only Draft Documents can be Submitted.');
        $this->submitting();
        $this->doc_status = DocStatus::SUBMITTED;
        $this->submitted_by = Filament::auth()->id();
        $this->submitted_at = now();
        $this->saveQuietly();
        $this->submitted();

        return $this;
    }

    public function cancel(?string $reason = ''): static
    {
        abort_unless($this->isSubmitted(), 403, 'Only Submitted Documents can be Cancelled.');
        \DB::transaction(function () use ($reason) {
            $this->canceling($reason);
            $this->doc_status = DocStatus::CANCELLED;
            $this->is_cancelled = true;
            $this->cancelled_by = \Auth::id();
            $this->cancelled_at = now();
            $this->saveQuietly();
            // Create a Doc Cancellation log:
            $log = DocumentCancellation::create([
                'reason' => $reason,
                'document_code' => $this->code,
                'document_type' => $this->getMorphClass(),
                'document_id' => $this->id,
            ]);
            $log->submit();
            $this->cancelled($reason);
        });

        return $this;
    }

    public function submitting()
    {
        // Hook your logic here
    }

    public function submitted()
    {
        // Hook your logic here
    }

    public function canceling(?string $reason = '')
    {
        // Hook your logic here
    }

    public function cancelled(?string $reason = '')
    {
        // Hook your logic here.
    }
}
