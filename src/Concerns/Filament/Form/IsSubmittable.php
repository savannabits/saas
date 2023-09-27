<?php

namespace Savannabits\Saas\Concerns\Filament\Form;

use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Form;
use Livewire\Component;

trait IsSubmittable
{
    protected function getActions(): array
    {
        return [
            $this->getSaveAsFinalFormAction(true),
            EditAction::make(),
            DeleteAction::make(),
        ];
    }

    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction()->label('Save as Draft')
                ->visible(fn ($livewire) => $livewire?->record?->isDraft()),
            $this->getSaveAsFinalFormAction()->label('Submit As Final')
                ->visible(fn ($livewire) => $livewire?->record?->isDraft() && $livewire?->record?->isNotCancelled()),
            $this->getCancelFormAction(),
        ];
    }

    protected function getSaveAsFinalFormAction(?bool $pageAction = false): Action
    {
        return Action::make('submit-as-final')
            ->label(__('Submit As Final'))
            ->color('success')
            ->requiresConfirmation()
            ->button()
            ->action(function ($data, Component $livewire) {
                $livewire->save();
                $livewire->record->submit();
            });
    }

    protected function getRedirectUrl(): ?string
    {
        return static::getResource()::getUrl('index');
    }

    public function form(Form $form): Form
    {
        $newForm = parent::form($form);
        if (! $this->getRecord()?->isDraft()) {
            $newForm = $newForm->operation('view')->disabled();
        } else {
            $newForm = $newForm->operation('edit');
        }

        return $newForm;
    }
}
