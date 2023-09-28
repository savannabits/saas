<?php

namespace Savannabits\Saas\Livewire;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Savannabits\Saas\Models\User;

class SwitchTeam extends Component implements HasForms
{
    use InteractsWithForms;
    use InteractsWithFormActions;
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('team_id')
                    ->label('Select Team/Company')
                    ->options(fn() => filament()->auth()->user()->teams()->pluck('name','id'))
                    ->searchable()
                    ->default(filament()->auth()->user()->team?->id)
                    ->required(),
            ])
            ->statePath('data');
    }

    public function create(): void
    {
        $data = $this->form->getState();
        User::find(Auth::id())->updateQuietly(['team_id' => $data['team_id']]);

        $this->redirect(request()->header('Referer'));
    }

    public function render(): string
    {
        return <<<'blade'
            <div>
                    <form wire:submit="create">

                        {{ $this->form }}

                        <x-filament::button class="mt-4 w-full" icon="heroicon-o-building-office-2" type="submit">
                            SUBMIT
                        </x-filament::button>
                    </form>
                    <x-filament-actions::modals />
            </div>
blade;
    }
}
