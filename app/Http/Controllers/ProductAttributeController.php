<?php

namespace App\Http\Controllers;

use App\Model\ProductAttribute;
use Illuminate\Http\Request;
use App\Http\Controllers\AppController;
use Exception;
use Validator;
use App\Libraries\Helpers;
use App\Libraries\Encrypt;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class ProductAttributeController extends AppController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Product variations';
        $data = Helpers::setBladeProperties($title,  [] ,  []);
        $productAttributes = ProductAttribute::select('id','name','default_values')->where('merchant_id', $this->merchant_id)->where('is_active',1)->get();
        foreach ($productAttributes as $pc=>$productAttr) {
            $productAttributes[$pc]['encrypted_id'] = Encrypt::encode($productAttr['id']);
        }
        $data['productAttributes'] = $productAttributes;
        $data['datatablejs'] = 'table-no-export';
        return view('app/merchant/product-attribute/index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Create product variation';
        $data = Helpers::setBladeProperties($title,  ['product-attribute'] ,  []);
        return view('app/merchant/product-attribute/create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required','min:2','max:45',Rule::unique('merchant_product_attribute_metadata','name')
            ->where('merchant_id',$this->merchant_id)->where('is_active',1)],
            'default_values' => ['required']
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        } else {
            $saveProductAttribute['name'] = ucwords(strtolower($request->name));
            $saveProductAttribute['merchant_id'] = $this->merchant_id;
            if(!empty($request->default_values)) {
                $saveProductAttribute['default_values'] = json_encode($request->default_values,true);
            }
            $productAttribute = new ProductAttribute();
            $savedQuery = $productAttribute->saveProductAttribute($saveProductAttribute);
            return redirect('merchant/product-attribute/index')->with('success',"Product variation has been created");            
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ProductAttribute  $productAttribute
     * @return \Illuminate\Http\Response
     */
    public function show(ProductAttribute $productAttribute)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ProductAttribute  $productAttribute
     * @return \Illuminate\Http\Response
     */
    public function edit($productAttribute)
    {
        $title = 'Update product variation';
        $data = Helpers::setBladeProperties($title,  ['product-attribute'],  []);
        $product_attr_id = Encrypt::decode($productAttribute);
        $data['productAttribute']['encrypted_id'] = $product_attr_id;
        $getProductAttribute = ProductAttribute::find($product_attr_id);
        $data['productAttribute'] = $getProductAttribute;
        $data['productAttribute']['encrypted_id'] = Encrypt::encode($getProductAttribute->id);
        $data['title'] = $title;
        return view('app/merchant/product-attribute/edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ProductAttribute  $productAttribute
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProductAttribute $productAttribute)
    {
        $product_attr_id = Encrypt::decode($request->id);
        $validator = Validator::make($request->all(), [
            'name' => ['required','min:2','max:45',Rule::unique('merchant_product_attribute_metadata','name')
            ->where('merchant_id',$this->merchant_id)->where('is_active',1)->ignore($product_attr_id,'id')
        ]]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        } else {
            $saveProductAttribute['name'] = ucwords(strtolower($request->name));
            if(!empty($request->default_values)) {
                $saveProductAttribute['default_values'] = json_encode($request->default_values,true);
            }
            $update = ProductAttribute::where('id', $product_attr_id)->update($saveProductAttribute);
            if($update) {
                return redirect('merchant/product-attribute/index')->with('success',"Product variations has been updated");
            } else {
                return redirect('merchant/product-attribute/index')->with('error',"Product variations can not be updated");
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ProductAttribute  $productAttribute
     * @return \Illuminate\Http\Response
     */
    public function destroy($productAttribute)
    {
        if($productAttribute) {
            $productAttributeId = Encrypt::decode($productAttribute);
            $deleteAttribute= ProductAttribute::where('id', $productAttributeId)->update(['is_active' => 0]);
            if ($deleteAttribute){
                return redirect('merchant/product-attribute/index')->with('success',"Product variation has been deleted");
            }else{
                return redirect('merchant/product-attribute/index')->with('error',"Product variation can not be deleted");
            }
        } else {
            return redirect('merchant/product-attribute/index')->with('error',"Product variation can not be deleted");
        }
    }
}
