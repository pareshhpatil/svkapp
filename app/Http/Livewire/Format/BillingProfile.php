<?php

namespace App\Http\Livewire\Format;

use Livewire\Component;
use App\Model\InvoiceFormat;
use App\Http\Controllers\AppController;

class BillingProfile extends Component
{
    public $merchant_id;
    public $profileId;
    public $profileList;
    public $templateName;
    public $error = null;
    public $exist_template_name = null;

    public function mount($profileId = 0, $templateName = '')
    {
        $this->profileId = $profileId;
        $this->exist_template_name = $templateName;
        $this->templateName = $templateName;
        $app = new AppController();
        $this->merchant_id = $app->merchant_id;
        $model = new InvoiceFormat();
        #get billing profile list
        $profileList = $model->getTableList('merchant_billing_profile', 'merchant_id', $this->merchant_id);
        $this->profileList = json_decode(json_encode($profileList), 1);
    }
    public function render()
    {
        return view('livewire.format.billing-profile', ['dprofileId' => 10]);
    }

    public function updatedprofileId($profileId)
    {
        $this->emit('changeProfile', $profileId);
        $this->profileId = $profileId;
    }
    public function updatedtemplateName()
    {
        if ($this->templateName != $this->exist_template_name && $this->templateName != '') {
            $model = new InvoiceFormat();
            $exist = $model->isExistData($this->merchant_id, 'invoice_template', 'template_name', $this->templateName);
            if ($exist == true) {
                $this->error = "Template name already exist";
            } else {
                $this->error = null;
            }
        } else {
            $this->error = null;
        }
    }
}
