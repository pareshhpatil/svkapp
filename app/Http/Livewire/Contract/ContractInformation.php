<?php

namespace App\Http\Livewire\Contract;

use Livewire\Component;
use App\Model\Contract;
use App\Http\Controllers\AppController;
use App\Model\Master;

class ContractInformation extends Component
{
    public $merchant_id;
    public $project_id;
    public $project_list;

    public function mount($project_id = 0)
    {
        $this->project_id = $project_id;
        $app = new AppController();
        $this->merchant_id = $app->merchant_id;
        $masterModel = new Master();
        #get billing profile list
        $this->project_list = $masterModel->getProjectList($this->merchant_id);
    }
    public function render()
    {
        return view('livewire.contract.contract-information', []);
    }

    public function updatedProjectId($project_id)
    {
        // $this->emit('changeProfile', $profileId);
        $this->project_id = $project_id;
    }
}
