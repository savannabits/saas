<?php

namespace Savannabits\Saas\Filament\Pages;

use App\Models\Team;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Tenancy\RegisterTenant;
use Illuminate\Auth\Access\AuthorizationException;
use function Filament\authorize;

class RegisterTeam extends RegisterTenant
{
    protected static string $view = 'filament-panels::pages.tenancy.register-tenant';
    protected static ?string $slug = 'select-team';
    public static function getLabel(): string
    {
        return trans('savannabits-saas::saas.tenancy.select_team');
    }

    public function form(Form $form): Form
    {
        $isAllowed = authorize('create', Filament::getTenantModel())->allowed();
        if ($isAllowed) {
            $teams = Team::query()->whereIsActive(true)->pluck('name', 'code');

        } else {
            $teams = auth()->user()->teams()->whereIsActive(true)->pluck('name', 'code');
        }
        return $form
            ->schema([
                TextInput::make('new_team')
                    ->label('Create a New team')
                    ->placeholder('Enter the Team Name')
                    ->readOnly(authorize('create', Filament::getTenantModel())->denied())
                    ->visible(!count($teams) && $isAllowed),
                Select::make('team_code')
                    ->options($teams)->visible(count($teams)),
                // ...
            ]);
    }

    protected function handleRegistration(array $data): Team
    {
        $collect = collect($data);
        if ($code = $collect->get('team_code')) {
            return Team::whereCode($code)->firstOrFail();
        }
        if ($name = $collect->get('new_team')) {
            $team = Team::create([
                'name' => $name,
            ]);
            $team->members()->attach(auth()->user());
            return $team;
        }
        abort(500,'Team could not be found');
    }

    public static function canView(): bool
    {
        try {
            return \auth()->user()->teams()->count() > 0 || authorize('create', Filament::getTenantModel())->allowed();
        } catch (AuthorizationException $exception) {
            return $exception->toResponse()->allowed();
        }
    }

    public function getRegisterFormAction(): Action
    {
        return Action::make('register')
            ->label('Login With Team')
            ->submit('register');
    }
}
