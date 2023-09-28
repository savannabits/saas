<?php

namespace Savannabits\Saas\Concerns\Policy;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Savannabits\Saas\Helpers\Framework;
use Savannabits\Saas\Support\Armor;

trait InheritsStandardPolicy
{
    abstract public function getResourceClass(): string;

    public function getSuffix(): string
    {
        return Armor::new()->getPermissionIdentifier($this->getResourceClass());
    }

    public function makeSuffixFromModel(Model | string $model): string
    {
        if (is_string($model)) {
            $class = $model::getModel()->getMorphClass();
        } else {
            $class = $model->getMorphClass();
        }

        return \Str::of(\Str::of($class)->explode('\\')->last() ?? '')
            ->snake('::')->toString();
    }

    public function viewAny(User $user): bool
    {
        return $user->can("view_any_{$this->getSuffix()}");
    }

    public function view(User $user, Model $model): bool
    {
        return $user->can("view_{$this->getSuffix()}");
    }

    public function create(User $user): bool
    {
        return $user->can("create_{$this->getSuffix()}");
    }

    public function update(User $user, Model $model): bool
    {
        return $user->can("update_{$this->getSuffix()}") && (! Framework::model_has_doc_status($model) || $model->isDraft());
    }

    public function deleteAny(User $user): bool
    {
        return $user->can("delete_any_{$this->getSuffix()}");
    }

    public function delete(User $user, Model $model)
    {
        return $user->can("delete_{$this->getSuffix()}") && (! Framework::model_has_doc_status($model) || $model->isDraft());
    }

    public function submit(User $user, Model $model): bool
    {
        return $user->can($this->perm('submit')) && Framework::model_has_doc_status($model) && $model->isDraft();
    }

    public function cancel(User $user, Model $model): bool
    {
        return $user->can($this->perm('cancel')) && Framework::model_has_doc_status($model) && $model->isSubmitted();
    }

    public function reverse(User $user, Model $model): bool
    {
        return $user->can($this->perm('reverse')) && Framework::model_has_doc_status($model) && $model->isSubmitted();
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, Model $model): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user)
    {
        return $user->can("delete_any_{$this->getSuffix()}");
    }

    public function forceDelete(User $user, Model $model)
    {
        return $user->can("delete_{$this->getSuffix()}") && (! Framework::model_has_doc_status($model) || $model->isDraft());
    }

    public function perm(string $prefix): string
    {
        return "{$prefix}_{$this->getSuffix()}";
    }
}