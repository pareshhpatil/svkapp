<?php

namespace App\Http\Controllers;

use App\Model\ProductCategory;
use Illuminate\Http\Request;
use Exception;
use Validator;
use App\Libraries\Helpers;
use App\Libraries\Encrypt;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class ProductCategoryController extends Controller
{   

    private $merchant_id = null;
    private $user_id = null;
    
    public function __construct()
    {
        $this->merchant_id = Encrypt::decode(Session::get('merchant_id'));
        $this->user_id = Encrypt::decode(Session::get('userid'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Product category list';
        $data = Helpers::setBladeProperties($title,  ['units', 'template'] ,  []);
        $productCategories = ProductCategory::select('id','name')->where('merchant_id', $this->merchant_id)->where('is_active',1)->get();
        foreach ($productCategories as $pc=>$productCategory) {
            $productCategories[$pc]['encrypted_id'] = Encrypt::encode($productCategory['id']);
        }
        $data['productCategories'] = $productCategories;
        $data['datatablejs'] = 'table-no-export';
        return view('app/merchant/product-category/index', $data);
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
            'name' => ['required','min:2','max:50',Rule::unique('product_category','name')
            ->where('merchant_id',$this->merchant_id)->where('is_active',1)
        ]]);
        
        if ($validator->fails()) {
            if(isset($request->response_type) && ($request->response_type == 'json')) {
                $haserror['status'] = 0;
                $haserror['error'] = response()->json(['error'=>$validator->errors()->all()]);
                echo json_encode($haserror);
            } else {
                return redirect()->back()->withInput()->withErrors($validator);
            }
            //return redirect('merchant/product-category/index')->withErrors($validator);
        } else {
            $saveProductCategory['name'] = ucwords(strtolower($request->name));
            $saveProductCategory['merchant_id'] = $this->merchant_id;
            $productCategory = new ProductCategory();
            $savedQuery = $productCategory->saveProductCategory($saveProductCategory);

            if(isset($request->response_type) && ($request->response_type == 'json')) {
                $response['name'] = $saveProductCategory['name'];
                $response['id'] = $savedQuery->id;
                $response['status'] = 1;
                echo json_encode($response);
            } else {
                return redirect('merchant/product-category/index')->with('success',"Product category has been created");
            }
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ProductCategory  $productCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProductCategory $productCategory)
    {
        $cat_id = Encrypt::decode($request->id);
        // $validator = Validator::make($request->all(), [
        //     'name' => 'required|min:2|max:50|unique:product_category,name,'. $cat_id .',id'
        // ]);
        $validator = Validator::make($request->all(), [
            'name' => ['required','min:2','max:50',Rule::unique('product_category','name')
            ->where('merchant_id',$this->merchant_id)->where('is_active',1)->ignore($cat_id,'id')
        ]]);

        if ($validator->fails()) {
            return redirect('merchant/product-category/index')->withErrors($validator);
        } else {
            $saveProductCategory['name'] = ucwords(strtolower($request->name));
            $update = ProductCategory::where('id', $cat_id)->update($saveProductCategory);
            if($update) {
                return redirect('merchant/product-category/index')->with('success',"Product category has been updated");
            } else {
                return redirect('merchant/product-category/index')->with('error',"Product category can not be updated");
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ProductCategory  $productCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy($productCategory)
    {
        if($productCategory) {
            $productCategoryId = Encrypt::decode($productCategory);       
            $deleteCategory = ProductCategory::where('id', $productCategoryId)->update(['is_active' => 0]);
            if ($deleteCategory){
                return redirect('merchant/product-category/index')->with('success',"Product Category has been deleted");
            }else{
                return redirect('merchant/product-category/index')->with('error',"Product Category can not be deleted");
            }
        } else {
            return redirect('merchant/product-category/index')->with('error',"Product Category can not be deleted");
        }
    }
}
