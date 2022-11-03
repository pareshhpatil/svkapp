<?php

namespace App\Http\Livewire\Format;

use Livewire\Component;
use App\Model\Invoice;

class CustomerLedger extends Component
{

    public $ledger = [];
    public $customer_names = 's';
    public $customer_id = '';
    public $aaa ;
    public $balance = 0;

    public $listeners = ['getLedger'];
    public function mount()
    {
    }

    public function getLedger($customer_id)
    {
        dd($customer_id);
        $this->customer_id=$customer_id;
        $invoice = new Invoice();
        $data = $invoice->getTableRow('customer', 'customer_id', $customer_id);
        $this->customer_names = trim($data->first_name) . ' ' . $data->last_name;
        $this->aaa = trim($data->first_name) . ' ' . $data->last_name;
        $this->balance=5000;
        $this->render();
    }

    public function updated()
    {
        $this->customer_names='Paresh';
        dd('hii');
    }
    public function render()
    {
        return view('livewire.format.customer-ledger');
    }
}
