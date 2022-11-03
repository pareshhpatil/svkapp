<?php

namespace App\Http\Livewire\Product;

use Livewire\Component;
use App\Model\Product;
use App\Http\Livewire\Field;
use App\Http\Controllers\AppController;
use App\Http\Controllers\ProductController;
use Livewire\WithFileUploads;
use DB;

class CreateVariationRow extends Component
{

    use WithFileUploads;
    public $enable_inventory;
    public $productAttributes;
    public $variationsRow = [];
    protected $listeners = [
        'addRow',
        'remove'
    ];
    public $product_id;
    public $getProductAttributeValues = [];
    public $deleteId = '-1';

    public function mount($enable_inventory = null, $product_id = null, $var_rows = null)
    {
        $app = new AppController();
        $productCont = new ProductController();
        $productModel = new Product();
        $getProductData = $productCont->getCommonData();
        $this->productAttributes = $getProductData['productAttributes'];

        //find all variable products 
        if ($product_id != null) {
            $getVariableProducts = $productModel->getVariationProducts($product_id, $app->merchant_id);
            if (!empty($getVariableProducts)) {
                foreach ($getVariableProducts as $vk => $vPro) {
                    //find product attribute values - update screen

                    $getProductAttributeValuesData = DB::table('merchant_product_attribute_values')->where('product_id', $vPro->product_id)->Where('merchant_id', $app->merchant_id)->pluck('attribute_value', 'attribute_id')->toArray();

                    $this->getProductAttributeValues[$vPro->product_id] = $getProductAttributeValuesData;

                    $this->variationsRow[] = array(
                        'product_attributes' => '',
                        'product_image' => $vPro->product_image,
                        'sku' => $vPro->sku,
                        'product_expiry_date' => $vPro->product_expiry_date,
                        'price' => $vPro->price,
                        'mrp' => $vPro->mrp,
                        'purchase_cost' => $vPro->purchase_cost,
                        'has_stock_keeping' => $vPro->has_stock_keeping,
                        'is_stock_keeping' => $vPro->has_stock_keeping,
                        'available_stock' => $vPro->available_stock,
                        'minimum_stock' => $vPro->minimum_stock,
                        'variable_product_id' => $vPro->product_id
                    );
                }
            }
        }
        if (!empty($var_rows)) {
            //print_r(old('attribute_values'));
            if (is_array($var_rows)) {
                foreach ($var_rows as $k => $val) {
                    $this->variationsRow[] = array(
                        'product_attributes' => '',
                        'product_image' => (isset(old('old_image')[$k]) && !empty(old('old_image')[$k])) ?  old('old_image')[$k] : old('product_image')[$k],
                        'sku' => old('sku')[$k],
                        'product_expiry_date' => old('product_expiry_date')[$k],
                        'price' => $val,
                        'mrp' => old('mrp')[$k],
                        'purchase_cost' => old('purchase_cost')[$k],
                        'has_stock_keeping' => old('has_stock_keeping')[$k],
                        'is_stock_keeping' => old('has_stock_keeping')[$k],
                        'available_stock' => old('available_stock')[$k],
                        'minimum_stock' => old('minimum_stock')[$k],
                        'variable_product_id' => ''
                    );
                }
            }
        }
    }

    public function addRow()
    {
        $productCont = new ProductController();
        $getProductData = $productCont->getCommonData();
        $this->productAttributes = $getProductData['productAttributes'];
        if ($this->enable_inventory == 1) {
            $this->variationsRow[] = array('product_attributes' => '', 'product_image' => '', 'sku' => '', 'product_expiry_date' => '', 'price' => 0, 'mrp' => 0, 'purchase_cost' => 0, 'has_stock_keeping' => 1, 'is_stock_keeping' => 1, 'available_stock' => '', 'minimum_stock' => '', 'variable_product_id' => 0);
        } else {
            $this->variationsRow[] = array('product_attributes' => '', 'product_image' => '', 'sku' => '', 'product_expiry_date' => '', 'price' => 0, 'mrp' => 0, 'purchase_cost' => 0, 'variable_product_id' => 0);
        }
    }

    public function deleteID($i)
    {
        $this->deleteId = $i;
    }

    public function remove()
    {
        if ($this->deleteId == '-1') {
            $this->variationsRow = [];
        } else {
            unset($this->variationsRow[$this->deleteId]);
        }
    }


    public function getProductAttr()
    {
        $productCont = new ProductController();
        $getProductData = $productCont->getCommonData();
        return $getProductData['productAttributes'];
    }

    public function render()
    {
        return view('livewire.product.create-variation-row');
    }
}
