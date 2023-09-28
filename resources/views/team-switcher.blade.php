@if(auth()->user()?->teams?->count())
    <x-filament::modal>
        <x-slot name="trigger">
            <x-filament::button icon="heroicon-s-chevron-double-down" icon-position="after">
                {{auth()->user()->team?->name ?? 'No Company Set'}}: SWITCH
            </x-filament::button>
        </x-slot>

        <livewire:savannabits-saas::switch-team/>
    </x-filament::modal>
@else
    <div class="font-black text-sm">
        {{ auth()->user()->team?->name ?: 'No Company Selected' }}
    </div>
@endif
