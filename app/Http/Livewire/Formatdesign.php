<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Formatdesign extends Component
{
    public $colors;
    public $name;
    public $metadata;
    public $table_heders;
    public $tax_heders;
    public $info;
    public $listeners = ['changeColor'];

    public function mount($colors, $name,$info, $metadata, $table_heders,$tax_heders)
    {

        $this->colors = $colors;
        $this->name = $name;
        $this->metadata = $metadata;
        $this->info = $info;
        $this->table_heders = $table_heders;
        $this->tax_heders = $tax_heders;

    }

    public function render()
    {
        return view('livewire.formatdesign');
    }

    public function changeColor($color)
    {
        // $this->emit('changeColor', $color);
        $this->colors = $color;
    }
}
