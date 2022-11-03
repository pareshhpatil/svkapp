<?php

namespace App\Http\Livewire\Lookup;

use Livewire\Component;
use App\Model\Hsnsaccode;

class HsnSacCodeSearch extends Component
{
    public $searchTerm;
    public $codes;
    public $resultFound = false;
    public $product_type = '';
    public $gst = 0;
    protected $listeners = [
        'setProductType',
        'addTodo',
        'resetfrm',
        'setSearchTerm'
    ];
    public $highlightWords = '\App\Http\Livewire\Lookup\HsnSacCodeSearch::highlightWords';
	
    public static function highlightWords($text, $searchTerm){
        return preg_replace('#'. preg_quote($searchTerm) .'#i', '<span style="background-color: #F9F902;">\\0</span>', $text);
    }

    public function mount()
    {
        $this->resetfrm();
    }

    public function resetfrm()
    {
        $this->searchTerm = '';
        $this->codes = [];
        $this->resultFound = false;
        $this->gst = 0;
    }
    /*
    public function updatedsearchTerm()
    {
        $this->resultFound = false;
        if(strlen($this->searchTerm) >= 3) {
            $q = $this->searchTerm;
            $this->codes = Hsnsaccode::where('type',$this->product_type)->where(function($query) use ($q) {
                $query->where('code', 'LIKE', '%'.$q.'%')
                    ->orWhere('description', 'LIKE', '%'.$q.'%');
            })->get()->toArray();

        }
    } */

    public function addTodo($selected=null,$gst=0) {
        //$this->searchTerm = $selected;
        $this->codes = [];
        $this->resultFound = true;
        $this->gst = $gst;
    }

    public function setProductType($product_type=null) {
        if(!empty($product_type)) {
            $this->product_type = $product_type;
        }
    }

    public function setSearchTerm($selected=null) {
        $this->resultFound = false;
        $this->searchTerm = $selected;
        if(!empty($selected) &&strlen($selected) >= 3) {
            $q = $this->searchTerm;
            $this->codes = Hsnsaccode::where('type',$this->product_type)->where(function($query) use ($q) {
                $query->where('code', 'LIKE', '%'.$q.'%')
                    ->orWhere('description', 'LIKE', '%'.$q.'%');
            })->get()->toArray();
        }
    }
    
    public function render()
    {
        //$this->resultFound = false;
        if(strlen($this->searchTerm) >= 3) {
            $q = $this->searchTerm;
            $this->codes = Hsnsaccode::where('type',$this->product_type)->where(function($query) use ($q) {
                $query->where('code', 'LIKE', '%'.$q.'%')
                    ->orWhere('description', 'LIKE', '%'.$q.'%');
            })->get()->toArray();

        }
        return view('livewire.lookup.hsn-sac-code-search');
    }

}
