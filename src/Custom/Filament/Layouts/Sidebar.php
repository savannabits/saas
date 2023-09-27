<?php

namespace Savannabits\Saas\Custom\Filament\Layouts;

use Filament\Forms\Components\Grid;
use Filament\Forms\Form;

class Sidebar
{
    public function __construct(
        public Form $form,
    ) {
    }

    public static function make(Form $form): static
    {
        return new static(form: $form);
    }

    public function schema(array $mainComponents, array $sidebarComponents): Form
    {
        return $this->form->schema([
            Grid::make(['sm' => 3])->schema([
                Grid::make()->schema($mainComponents)->columnSpan(['sm' => 2]),
                Grid::make()->schema($sidebarComponents)->columnSpan(['sm' => 1]),
            ]),
        ]);
    }
}
