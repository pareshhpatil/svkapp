<?php

namespace App\Http\Livewire\Format;

use Livewire\Component;
use App\Model\InvoiceFormat;
use App\Http\Controllers\AppController;

class TaxDetail extends Component
{
    public $taxes = [];
    public $defaulttax = [];
    public $merchantTax = [];

    public function mount($defaultTax = '')
    {

        if ($defaultTax != '' && $defaultTax != 'null') {
            $this->defaulttax = json_decode($defaultTax, 1);
        }

        $app = new AppController();
        $model = new InvoiceFormat();
        $taxes =  $model->getTableList('merchant_tax', 'merchant_id', $app->merchant_id);
        $taxes = json_decode(json_encode($taxes), 1);
        $int = 0;
        foreach ($taxes as $t) {
            if (in_array($t['tax_id'], $this->defaulttax)) {
                $this->taxes[$int] = $t['tax_id'];
                $int++;
            }
            $this->merchantTax[$t['tax_id']] = $t;
        }
    }

    public function render()
    {
        
        return view('livewire.format.tax-detail');
    }

    public function remove($key)
    {
        unset($this->taxes[$key]);
    }

    public function addTax()
    {
        $this->taxes[] = 0;
    }
}
