<?php

namespace App\Http\Livewire\Format;

use Livewire\Component;

class ModalDetail extends Component
{
    public $headerColumn;

    public function mount($columns)
    {
        $this->headerColumn = json_decode(json_encode($columns), 1);
    }

    public function render()
    {
        return view('livewire.format.modal-detail');
    }

    
}
