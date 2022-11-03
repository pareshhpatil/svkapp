<?php

namespace App\Http\Livewire\Format;

use Livewire\Component;
use App\Model\InvoiceFormat;
use App\Http\Controllers\AppController;

class PluginDetail extends Component
{
    public $plugins = [];
    public $coveringNotes = [];
    public $supplier = [];
    public function render()
    {
        return view('livewire.format.plugin-detail');
    }

    public function mount($plugin = null, $columns = null)
    {
        if ($plugin != null && $plugin != '' && $plugin != 'null') {
            $this->plugins = json_decode($plugin, 1);
        }
        if ($columns != null) {
            foreach ($columns as $col) {
                switch ($col->function_id) {
                    case 9:
                        $this->plugins['has_invoice_number'] = 1;
                        break;
                    case 1:
                        $this->plugins['has_expiry_date'] = 1;
                        break;
                    case 4:
                        $this->plugins['has_previous_due'] = 1;
                        break;
                }
            }
        }
        $app = new AppController();
        $merchant_id = $app->merchant_id;
        $model = new InvoiceFormat();
        $supplier = $model->getMerchantValues($merchant_id, 'supplier');
        $coveringNotes = $model->getMerchantValues($merchant_id, 'covering_note');
        $this->coveringNotes = json_decode(json_encode($coveringNotes), 1);
        $this->supplier = json_decode(json_encode($supplier), 1);
    }

    public function change()
    {
    }
}
