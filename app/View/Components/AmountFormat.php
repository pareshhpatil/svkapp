<?php

namespace App\View\Components;

use Illuminate\View\Component;

class AmountFormat extends Component
{
    public $amount;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($amount)
    {
        //
        $this->amount = $amount;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        if($this->amount < 0) {
            $this->amount = '('.str_replace('-','',number_format($this->amount,2)).')';
        } else {
            $this->amount = number_format($this->amount, 2);
        }
        return view('components.amount-format');
    }
}
