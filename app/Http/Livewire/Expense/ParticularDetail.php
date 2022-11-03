<?php

namespace App\Http\Livewire\Expense;
use Livewire\Component;

use App\Http\Livewire\Field;
use App\Model\Product;
use App\Http\Controllers\AppController;
use App\Http\Controllers\ProductController;
use DB;
use App\Libraries\Helpers;
use App\Model\ProductCategory;
use App\Libraries\Encrypt;
use Illuminate\Support\Arr;

class ParticularDetail extends Component
{
    public $sac = [];
    public $unit = [];
    public $tax = []; 
    public $total = [];
    public $products = [];
    public $rate = [];
    public $sale_price = [];
    public $particular = [];
    //public $i = 1;
    public $discount = 0;
    public $adjustment = 0;
    public $tds = 0;
    public $sub_total = 0;
    public $gst_type = '';
    public $product_id = "";
    protected $listeners = [
        'setGstType',
        'setParticularDetails'
    ];
    public $cgst_amt = 0;
    public $sgst_amt = 0;
    public $igst_amt = 0;
    public $sgst = false;
    public $cgst = false;
    public $igst = false;
    public $grand_total = 0;
    public $expense_id;
    public $expense_detail_id = [];
    public $narrative = null;
    
    public function mount($expense_id=null,$table=null){
        $app = new AppController();
        $model = new Product();

        if (!empty($expense_id)) {
            $expense_id = Encrypt::decode($expense_id);
            
            //find particulars details for the expense_id from expenses_detail table 
            $particulars = DB::table($table.'_detail')->where('expense_id',$expense_id)->where('is_active', 1)->get();
            foreach($particulars as $pk=>$particular) {
                $this->products[] =  $model->getProducts($app->merchant_id,$particular->product_id);
                $this->particular[] = array('product_id'=>$particular->product_id,'sac'=>$particular->sac_code,'unit'=>$particular->qty,'rate'=>$particular->rate,'sale_price'=> $particular->sale_price,'tax'=>$particular->tax,'total'=>$particular->amount,'expense_detail_id'=>$particular->id);
            }

            //find expense data like tds,discount, adjustment, narrative, igst, cgst, sgst amount 
            $expenseData = DB::table($table)->where('expense_id',$expense_id)->where('is_active', 1)->first();
            
            $this->tds = $expenseData->tds;
            $this->discount = $expenseData->discount;
            $this->adjustment = $expenseData->adjustment;
            $this->narrative = $expenseData->narrative;
            $this->updated();
        } else {
            $this->products[] =  $model->getProducts($app->merchant_id);
            $this->particular[]=array('product_id'=>'','sac'=>'','unit'=>1,'rate'=>0,'sale_price'=>0,'tax'=>'0','total'=>'0','expense_detail_id'=>0);
        }
    }

    public function addRow()
    {
        $app = new AppController();
        $model = new Product();
        $this->products[] =  $model->getProducts($app->merchant_id);
        $this->particular[]=array('product_id'=>'','sac'=>'','unit'=>1,'rate'=>0,'sale_price'=>0,'tax'=>'0','total'=>'0','expense_detail_id'=>0);
    }

    public function remove($i)
    {
        unset($this->particular[$i]);
        $this->updated();
    }
    
    public function updated() {
        $tds_amt = 0;
        $amount = 0;
        $total_tax = 0;
        $percent = 0;
        $total_price = 0;
        
        if (!empty($this->particular)) {
            foreach($this->particular as $updatekey=>$updateValue) {
                if(!empty($updateValue['product_id']) && $updateValue['product_id'] != 0) {

                    $this->particular[$updatekey]['rate'] = !empty($updateValue['rate']) ? $updateValue['rate'] : 0;
                    $this->particular[$updatekey]['tax'] = $updateValue['tax'];
                    $this->particular[$updatekey]['sac'] = $updateValue['sac'];
                    $this->particular[$updatekey]['unit'] = $updateValue['unit'];
                    $this->particular[$updatekey]['sale_price'] = !empty($updateValue['sale_price']) ? $updateValue['sale_price'] : 0;

                    //calculate amount
                    if(isset($this->particular[$updatekey]['tax']) && !empty($this->particular[$updatekey]['tax'])) {   
                        $percent = $this->particular[$updatekey]['tax'];
                    } else {
                        $percent = 0;
                    }

                    if($this->particular[$updatekey]['rate'] != 0 &&  $this->particular[$updatekey]['unit'] != 0) {
                        //dd($this->particular);
                        $rate = $this->particular[$updatekey]['rate'];
                        $unit = $this->particular[$updatekey]['unit'];
                        $total_price =  $rate * $unit; 
                        $this->particular[$updatekey]['total'] = number_format((float)$total_price, 2, '.', '');

                        if ($percent > 0) {
                            $tax_amount = $total_price * $percent / 100;
                            $total_tax = $total_tax + $tax_amount;
                        }
                        $amount = $amount + $total_price;
                    } else {
                        $this->particular[$updatekey]['total'] = 0;
                    }
                }
            }   
        }
        //remaining calculations
        try {
            $adjustment = (isset($this->adjustment) && !empty($this->adjustment)) ? $this->adjustment : 0;
            $discount = (isset($this->discount) && !empty($this->discount)) ? $this->discount : 0;
            $tds = $this->tds;
        } catch(Exception $e) {
            $adjustment = 0;
            $discount = 0;
            $tds = 0;
        }
        
        $this->sub_total = number_format((float)$amount, 2, '.', '');

        if ($total_tax > 0) {
            //$gst_type = $this->gst_type;
            if ($this->gst_type == 'intra') {
                $this->sgst = true;
                $this->cgst = true;
                $this->igst = false;
                $this->cgst_amt = number_format((float)($total_tax/2), 2, '.', '');
                $this->sgst_amt = number_format((float)($total_tax/2), 2, '.', '');
            } else {
                $this->sgst = false;
                $this->cgst = false;
                $this->igst = true;
                $this->igst_amt = number_format((float)$total_tax, 2, '.', '');
            }
        } else {
            $this->sgst = false;
            $this->cgst = false;
            $this->igst = false;
        }
        
        if ($tds > 0) {
            $tds_amt = $amount * $tds / 100;
        }
       
        $grand_total = $amount + $total_tax - $discount + $adjustment - $tds_amt;
        $this->grand_total = number_format((float)$grand_total, 2, '.', '');
    } 

    public function setParticularDetails($product_id=null,$row=null) {
        if(!empty($product_id) && $product_id != 0) {
            $app = new AppController();
            $model = new Product();
            $getData = $model->getProductDetail($product_id,$app->merchant_id);
            //purchase_cost not sale_price
            $this->particular[$row]['rate'] = $getData->purchase_cost;
            $this->particular[$row]['tax'] = $getData->gst_percent;
            $this->particular[$row]['sac'] = $getData->sac_code;
            $this->particular[$row]['sale_price'] = $getData->price;
            $this->updated();
        }
    }

    public function setGstType($vendor_id=null, $table = 'vendor') {
        $app = new AppController();
        $type = 'inter';
        
        $getMerchantProfileDetail = DB::table('merchant_billing_profile')->select('id','state','merchant_id','gst_number')->where('merchant_id',$app->merchant_id)->where('is_default', 1)->first();
        $merchant_gst_number = $getMerchantProfileDetail->gst_number;
        $merchant_state = $getMerchantProfileDetail->state;

        if ($table == 'vendor') {
            $vendor = DB::table('vendor')->select('vendor_id','state','gst_number')->where('vendor_id',$vendor_id)->first();
        } else {
            $vendor = DB::table('customer')->select('customer_id','state','gst_number')->where('customer_id',$vendor_id)->first();
        }
        if ($merchant_gst_number != '' && $vendor->gst_number != '') {
            if (substr($merchant_gst_number, 0, 2) == substr($vendor->gst_number, 0, 2)) {
                $type = 'intra';
            }
        } else {
            if ($merchant_state == $vendor->state) {
                $type = 'intra';
            }
        }
        $this->gst_type = $type;
    }
    
    public function render()
    {   
        $app = new AppController();
        //find gst applicable
        $getGstTax = DB::table('config')->where('config_type', 'gst_tax')->pluck('config_key','config_value')->toArray();
        $data['gstTax'] = $getGstTax;
        return view('livewire.expense.particular-detail',$data);
    }
}
