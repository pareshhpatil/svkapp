<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Product;
use App\Model\Invoice;
use Exception;
use App\Libraries\Helpers;
use App\Libraries\Encrypt;
use Illuminate\Support\Facades\Session;
use App\Model\ProductCategory;
use App\Model\ProductAttributeValue;
use App\Model\StockLedger;
use DB;
use File;
use Validator;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\UppyFileUploadController;

class ProductController extends Controller
{

    private $merchant_id = null;
    private $user_id = null;

    public function __construct()
    {
        $this->productModel = new Product();
        $this->invoiceModel = new Invoice();
        $this->merchant_id = Encrypt::decode(Session::get('merchant_id'));
        $this->user_id = Encrypt::decode(Session::get('userid'));
        $this->inventory_service_id = Encrypt::encode('15'); //15 service_id
        //$this->product_base_url = 'https://s3.' . env('S3REGION') . '.amazonaws.com/' . env('S3BUCKET_EXPENSE') . '/products/';            
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Inventory';
        $data = Helpers::setBladeProperties($title,  [],  [171]);
        $getProducts =  $this->productModel->getAllParentProducts($this->merchant_id);
        $getProducts = json_decode(json_encode($getProducts), true);

        foreach ($getProducts as $pk => $product) {
            $getProducts[$pk]['encrypted_id'] = Encrypt::encode($product['product_id']);
            //find no of variable products
            if ($product['goods_type'] == 'variable') {
                $getVariationProductDetails = $this->productModel->getVariationProducts($product['product_id'], $this->merchant_id, $count = 1);
                if (!empty($getVariationProductDetails) && !empty($getVariationProductDetails[0])) {
                    $getProducts[$pk]['variations'] = $getVariationProductDetails[0]->variations;
                    $getProducts[$pk]['available_stock'] = $getVariationProductDetails[0]->total_stock;
                    if ($getVariationProductDetails[0]->min_price == $getVariationProductDetails[0]->max_price) {
                        $getProducts[$pk]['price'] = $getVariationProductDetails[0]->min_price;
                    } else {
                        $getProducts[$pk]['price'] = $getVariationProductDetails[0]->min_price . ' - ' . $getVariationProductDetails[0]->max_price;
                    }
                } else {
                    $getProducts[$pk]['variations'] = '0';
                }
            }
        }
        $data['products'] = $getProducts;
        $data['enable_inventory'] = $this->checkInventoryServiceEnable();
        $data['datatablejs'] = 'table-no-export-tablestatesave'; //table-no-export
        $data['list_name'] = 'product_list';
        return view('app/merchant/product/index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Create product/service';
        $data = Helpers::setBladeProperties($title,  ['product', 'template'],  [171]);

        $getData = $this->getCommonData();
        $data['productCategories'] = $getData['productCategories'];
        $data['gstTax'] = $getData['gstTax'];
        $data['getVendors'] = $getData['getVendors'];
        $data['getUnitTypes'] = $getData['getUnitTypes'];
        $data['redirect'] = '1';
        $data['title'] = $title;
        $data['enable_inventory'] = $this->checkInventoryServiceEnable();
        $data['service_id'] = $this->inventory_service_id;
        $data['mode'] = 'create';
        $breadcrumbs['menu'] = 'inventory';
        $breadcrumbs['title'] = $data['title'];
        $breadcrumbs['url'] = '/merchant/product/createnew';
        Session::put('breadcrumbs', $breadcrumbs);

        return view('app/merchant/product/create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request) {
            $validator = $this->productModel->validateRules($request->all(), $this->merchant_id);

            if ($validator->fails()) {
                //check redirect parameter and response
                if (isset($request->redirect) && ($request->redirect == 1)) {
                    return redirect()->back()->withInput()->withErrors($validator);
                } else {
                    $haserror['status'] = 0;
                    $haserror['error'] = response()->json(['error' => $validator->errors()->all()]);
                    echo json_encode($haserror);
                }
            } else {
                if ($request['goods_type'] == 'variable') {
                    $attr_errors = array();

                    if (!empty($request['attribute_values'])) {
                        $attr_keys = array_keys($request['attribute_values']);
                        $total_variations = count($request['price']);

                        for ($j = 0; $j < $total_variations; $j++) {
                            foreach ($attr_keys as $k => $attr_val) {
                                $attr_name = $request['attribute_values'][$attr_val][$j];
                                $string[$k] = $attr_name;
                            }
                            $tmp = array_filter($string);
                            if (empty($tmp)) {
                                $attr_errors[$j] = 'Please select at least one product attribute for variation row ' . ($j + 1);
                            }
                        }
                        if (!empty($attr_errors)) {
                            return redirect()->back()->withInput()->withErrors($attr_errors);
                        }
                    } else {
                        return redirect()->back()->withInput()->withErrors('Please add at least one product attributes and variable product');
                    }
                }
                $saveProduct = $request->all();
                $saveProduct['merchant_id'] = $this->merchant_id;
                //find unit_name from unit_type_id
                if (isset($saveProduct['unit_type_id']) && !empty($request['unit_type_id'])) {
                    $getUnitType = DB::table('merchant_unit_type')->select('name')->where('id', $saveProduct['unit_type_id'])->first();
                    $saveProduct['unit_type'] = (isset($getUnitType->name) && !empty($getUnitType->name)) ? $getUnitType->name : NULL;
                } else {
                    $saveProduct['unit_type_id'] = 0;
                }

                unset($saveProduct['is_stock_keeping']);
                unset($saveProduct['redirect']);
                //save image if type is product and image is set
                if ($request['type'] == 'Goods' && $request['goods_type'] == 'simple') {
                    $saveProduct['product_image'] = (!empty($request['product_image'])) ? $request['product_image'] : NULL;
                }

                if ($request['goods_type'] == 'variable') {
                    $this->setVariableProduct($saveProduct);
                } else {
                    $savedProductId = $this->productModel->saveProduct($saveProduct);
                    //update parent_id for simple product and service
                    if ($request['type'] == 'Goods' && $request['goods_type'] == 'simple') {
                        $updateProduct['parent_id'] = $savedProductId;
                        $updateProductId = $this->productModel->updateProduct($updateProduct, $savedProductId);
                    }
                    if ($request['type'] == 'Service') {
                        $updateProduct['parent_id'] = NULL;
                        $updateProductId = $this->productModel->updateProduct($updateProduct, $savedProductId);
                    }

                    if (isset($request['has_stock_keeping']) && $request['has_stock_keeping'] == '1' && $request['available_stock'] != '0') {
                        //create enrty in stock_ledger table for invenotry management
                        $saveStock['product_id'] = $savedProductId;
                        $saveStock['reference_id'] = $savedProductId;
                        $saveStock['quantity'] = $request->available_stock;
                        $saveStock['amount'] = $request->price;
                        $saveStock['reference_type'] = 1;
                        $saveStock['narrative'] = 'Stock added';
                        $stockLedger = new StockLedger();
                        $savedStockLedgerId = $stockLedger->saveStockLedger($saveStock);
                    }
                }

                //check redirect method and response
                if (isset($request->redirect) && ($request->redirect == 1)) {
                    return redirect('merchant/product/index')->with('success', "Product has been created");
                } else {
                    if (isset($savedProductId)) {
                        $getSavedProductData = Product::select('product_id', 'product_name', 'purchase_cost', 'price', 'mrp', 'product_expiry_date', 'product_number', 'sac_code', 'gst_percent', 'unit_type', 'has_stock_keeping', 'available_stock')->where('product_id', $savedProductId)->where('merchant_id', $this->merchant_id)->first();
                    }
                    $getMerchantProduct = $this->invoiceModel->getAllParentProducts($this->merchant_id);

                    foreach ($getMerchantProduct as $pr) {
                        $pr->product_name = str_replace("'", "", $pr->product_name);
                        $products[$pr->product_name] = array(
                            'price' => $pr->price,
                            'mrp' => $pr->mrp,
                            'product_expiry_date' => $pr->product_expiry_date,
                            'product_number' => $pr->product_number,
                            'sac_code' => $pr->sac_code,
                            'gst_percent' => $pr->gst_percent,
                            'unit_type' => $pr->unit_type,
                            'available_stock' => $pr->available_stock,
                            'enable_stock' => $pr->has_stock_keeping,
                            'name' => $pr->product_name
                        );
                    }
                    $res["product_list"] = json_encode($products);
                    if (isset($savedProductId)) {
                        $res["product_array"] = json_encode($getSavedProductData);
                        $res['name'] = $getSavedProductData['product_name'];
                        $res["product_type"] = 'simple';
                    } else {
                        $res["product_array"] = json_encode([]);
                        $res["name"] = '';
                        $res["product_type"] = 'variable';
                    }
                    $res['status'] = 1;
                    echo json_encode($res);
                }
            }
        }
    }

    public function setVariableProduct($saveData = null, $product_id = null)
    {
        if (!empty($saveData)) {
            //first save parent variable product
            $saveProduct['product_name'] = $saveData['product_name'];
            $saveProduct['product_number'] = $saveData['product_number'];
            $saveProduct['goods_type'] = $saveData['goods_type'];
            $saveProduct['type'] = $saveData['type'];
            $saveProduct['parent_id'] = 0;
            $saveProduct['gst_percent'] = $saveData['gst_percent'];
            $saveProduct['sac_code'] = $saveData['sac_code'];
            if (isset($saveData['unit_type_id']) && !empty($saveData['unit_type_id'])) {
                $getUnitType = DB::table('merchant_unit_type')->select('name')->where('id', $saveData['unit_type_id'])->first();
                $saveProduct['unit_type'] = (isset($getUnitType->name) && !empty($getUnitType->name)) ? $getUnitType->name : NULL;
            } else {
                $saveProduct['unit_type_id'] = 0;
                $saveProduct['unit_type']  = NULL;
            }
            $saveProduct['category_id'] = $saveData['category_id'];
            $saveProduct['vendor_id'] = $saveData['vendor_id'];
            $saveProduct['merchant_id'] = $this->merchant_id;

            if ($product_id != null) {
                $parentProductId = $this->productModel->updateProduct($saveProduct, $product_id);
            } else {
                $parentProductId = $this->productModel->saveProduct($saveProduct);
            }
            $variationProduct = array();
            if (!empty($saveData['attribute_values']) && $parentProductId != '') {
                $i = 0;
                $attr_keys = array_keys($saveData['attribute_values']);
                $total_variations = count($saveData['price']);

                for ($j = 0; $j < $total_variations; $j++) {
                    $string = '';
                    foreach ($attr_keys as $k => $attr_val) {
                        $attr_name = $saveData['attribute_values'][$attr_val][$j];
                        if ($attr_name != '0') {
                            $string .= ", $attr_name";
                        }
                    }
                    $product_name = $saveData['product_name'] . ' - ' . substr($string, 1);
                    $variationProduct[$j]['product_name'] = $product_name;
                    $variationProduct[$j]['product_number'] =  (isset($saveProduct['product_number']) ? $saveProduct['product_number'] : '');
                    $variationProduct[$j]['goods_type'] = $saveData['goods_type'];
                    $variationProduct[$j]['type'] = $saveData['type'];
                    $variationProduct[$j]['gst_percent'] = $saveData['gst_percent'];
                    $variationProduct[$j]['category_id'] = $saveData['category_id'];
                    $variationProduct[$j]['parent_id'] = $parentProductId;
                    $variationProduct[$j]['unit_type_id'] = $saveData['unit_type_id'];
                    $variationProduct[$j]['sku'] = (isset($saveData['sku'][$j]) ? $saveData['sku'][$j] : NULL);
                    $variationProduct[$j]['product_expiry_date'] = ($saveData['product_expiry_date'][$j] != '') ? $saveData['product_expiry_date'][$j] : '';
                    $variationProduct[$j]['price'] = ($saveData['price'][$j] != '') ? $saveData['price'][$j] : 0;
                    $variationProduct[$j]['mrp'] = ($saveData['mrp'][$j] != '') ? $saveData['mrp'][$j] : 0;
                    $variationProduct[$j]['purchase_cost'] = (isset($saveData['purchase_cost'][$j]) ? $saveData['purchase_cost'][$j] : NULL);
                    $variationProduct[$j]['has_stock_keeping'] = (isset($saveData['has_stock_keeping'][$j]) ? $saveData['has_stock_keeping'][$j] : 0);
                    $variationProduct[$j]['available_stock'] = (isset($saveData['available_stock'][$j]) ? $saveData['available_stock'][$j] : 0);
                    $variationProduct[$j]['minimum_stock'] = (isset($saveData['minimum_stock'][$j]) ? $saveData['minimum_stock'][$j] : 0);
                    $variationProduct[$j]['merchant_id'] = $this->merchant_id;

                    //set product image
                    if (isset($saveData['old_image']) && !empty($saveData['old_image'][$j])) {
                        if (isset($saveData['product_image']) && !empty($saveData['product_image'][$j])) {
                            if ($saveData['product_image'][$j] != $saveData['old_image'][$j]) {
                                $variationProduct[$j]['product_image'] = (!empty($saveData['product_image'][$j])) ? $saveData['product_image'][$j] : NULL;
                            }
                        } else {
                            $variationProduct[$j]['product_image'] = (!empty($saveData['old_image'][$j])) ? $saveData['old_image'][$j] : NULL;
                        }
                    } else {
                        $variationProduct[$j]['product_image'] = (!empty($saveData['product_image'][$j])) ? $saveData['product_image'][$j] : NULL;
                    }

                    //$variationProduct[$j]['variable_product_id'] = $saveData['variable_product_id'][$j];

                    if (!empty($variationProduct)) {
                        if ($saveData['variable_product_id'][$j] == 0) {
                            //save variable product
                            $savedProductId = $this->productModel->saveProduct($variationProduct[$j]);
                            $delete_var_product[] = $savedProductId;

                            if (isset($variationProduct[$j]['has_stock_keeping']) && $variationProduct[$j]['has_stock_keeping'] == '1' && $variationProduct[$j]['available_stock'] != '0') {
                                //check stock_ledger data is added or edited
                                $checkStockExist = StockLedger::where('product_id', $savedProductId)->where('reference_type', 1)->first();
                                if (empty($checkStockExist)) {
                                    $saveStock['product_id'] = $savedProductId;
                                    $saveStock['reference_id'] = $savedProductId;
                                    $saveStock['quantity'] = $variationProduct[$j]['available_stock'];
                                    $saveStock['amount'] = $variationProduct[$j]['price'];
                                    $saveStock['reference_type'] = 1;
                                    $saveStock['narrative'] = 'Stock added';
                                    $stockLedger = new StockLedger();
                                    $savedStockLedgerId = $stockLedger->saveStockLedger($saveStock);
                                }
                            } else {
                                //update is_active flag for the product in stock_ledger table if it has previous stock keeping data
                                $updatedQuery = StockLedger::where('product_id', $savedProductId)->where('reference_type', 1)->update(['is_active' => 0]);
                            }

                            //save product attribute meta values
                            $variation_id = $this->generateRandomString(6);
                            foreach ($attr_keys as $mk => $attr_val) {
                                $attr_name = $saveData['attribute_values'][$attr_val][$j];
                                $productAttrVal = new ProductAttributeValue();
                                $saveAttr['product_id'] = $savedProductId;
                                $saveAttr['merchant_id'] = $this->merchant_id;
                                $saveAttr['variation_id'] = $variation_id;
                                $saveAttr['attribute_id'] = $attr_val;
                                $saveAttr['attribute_value'] = $attr_name;
                                $savedAttrValueId = $productAttrVal->saveProductAttrValue($saveAttr);
                            }
                        } else {
                            //update variable product
                            $updateProductId = $this->productModel->updateProduct($variationProduct[$j], $saveData['variable_product_id'][$j]);
                            $delete_var_product[] = $updateProductId;

                            //update new product attribute meta values
                            //check is added variation id for variable products in meta values table
                            $existVaritionID = ProductAttributeValue::select('variation_id')->where('product_id', $updateProductId)->where('is_active', 1)->first();
                            if ($existVaritionID != null) {
                                $variation_id = $existVaritionID['variation_id'];
                            } else {
                                $variation_id = $this->generateRandomString(6);
                            }

                            foreach ($attr_keys as $mk => $attr_val) {
                                $attr_name = $saveData['attribute_values'][$attr_val][$j];
                                $productAttrVal = new ProductAttributeValue();
                                $saveAttr['product_id'] = $updateProductId;
                                $saveAttr['merchant_id'] = $this->merchant_id;
                                $saveAttr['variation_id'] = $variation_id;
                                $saveAttr['attribute_id'] = $attr_val;
                                $saveAttr['attribute_value'] = $attr_name;

                                //first check attribute is meta value is exist or not if not then create else update 
                                $existMetaValue = ProductAttributeValue::where('product_id', $updateProductId)->where('is_active', 1)->where('attribute_id', $attr_val)->where('attribute_value', $attr_name)->where('merchant_id', $this->merchant_id)->first();
                                if ($existMetaValue == null) {
                                    $savedAttrValueId = $productAttrVal->saveProductAttrValue($saveAttr);
                                }
                            }
                        }
                    }
                }
                //check remove any variable product if yes then update as in_Active 0
                $this->setIsActiveVarProduct($parentProductId, $delete_var_product);
            }
        }
    }

    public function setIsActiveVarProduct($parentProductId = null, $data = null)
    {
        if ($parentProductId != null) {
            $getVarproducts = DB::table('merchant_product')
                ->where('is_active', 1)
                ->where('merchant_id', $this->merchant_id)
                ->where('parent_id', $parentProductId)
                ->pluck('product_id', 'product_id')->toArray();

            if (!empty($getVarproducts)) {
                foreach ($getVarproducts as $vk => $val) {
                    if (!in_array($val, $data)) {
                        $updatedVarProduct = Product::where('product_id', $val)->update(['is_active' => 0]);
                    }
                }
            }
        }
    }


    public function generateRandomString($length = 25)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show($type, $product)
    {

        $title = 'View inventory details';
        $stockLedgerModel = new StockLedger();
        $data = Helpers::setBladeProperties($title,  [],  [171]);
        $data['encrypted_id'] = $product;
        $product_id = Encrypt::decode($product);
        $getProduct = Product::find($product_id);
        $data['product'] = $getProduct;

        //find category name
        if ($getProduct->category_id != '') {
            $getProductCategory = ProductCategory::select('id', 'name')->where('id', $getProduct->category_id)->where('merchant_id', $this->merchant_id)->first();
            $data['product']['category_name'] = (!empty($getProductCategory->name)) ? $getProductCategory->name : '-';
        }

        //find Vendor name
        if ($getProduct->vendor_id != '') {
            $getVendor = DB::table('vendor')->where('vendor_id', $getProduct->vendor_id)->where('merchant_id', $this->merchant_id)->first();
            $data['product']['vendor_name'] = (!empty($getVendor->vendor_name)) ? $getVendor->vendor_name : '-';
        }

        //find variation products if goods_type is variable
        $getVariationProducts = array();
        $getStockLedger = array();
        if ($getProduct->type == 'Goods' && $getProduct->goods_type == 'variable') {
            $getVariationProducts = $this->productModel->getVariationProducts($getProduct->product_id, $this->merchant_id);
            $data['variableProducts'] = $getVariationProducts;

            foreach ($data['variableProducts'] as $var_data) {
                if ($var_data['product_expiry_date'] != '') {
                    $var_data['product_expiry_date'] = date("d M Y", strtotime($var_data['product_expiry_date']));
                } else {
                    $var_data['product_expiry_date'] = '-';
                }
            }

            //find stock ledger for variable products
            foreach ($getVariationProducts as $vk => $varStk) {
                $getStockLedgers = $stockLedgerModel->getProductStockLedger($varStk->product_id);
                $getStockLedger[] = $getStockLedgers;
            }
        } else if ($getProduct->type == 'Goods' && $getProduct->goods_type == 'simple') {
            //find stock ledger for the product
            $getStockLedger[] = $stockLedgerModel->getProductStockLedger($product_id);
        }

        $data['type'] = $type;
        $data['title'] = $title;
        $data['stockLedger'] = $getStockLedger;
        $data['enable_inventory'] = $this->checkInventoryServiceEnable();
        $data['datatablejs'] = 'table-no-export';
        return view('app/merchant/product/show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit($product)
    {
        $title = 'Update product/service';
        $data = Helpers::setBladeProperties($title,  ['product', 'template'],  [171]);
        $product_id = Encrypt::decode($product);
        $getProduct = Product::find($product_id);
        $data['product'] = $getProduct;
        $getData = $this->getCommonData();
        $data['productCategories'] = $getData['productCategories'];
        $data['gstTax'] = $getData['gstTax'];
        $data['getVendors'] = $getData['getVendors'];
        $data['getUnitTypes'] = $getData['getUnitTypes'];
        $data['enable_inventory'] = $this->checkInventoryServiceEnable();
        $data['service_id'] = $this->inventory_service_id;
        $data['title'] = $title;
        $data['mode'] = 'update';

        $breadcrumbs['menu'] = 'inventory';
        $breadcrumbs['title'] = $data['title'];
        $breadcrumbs['url'] = '/merchant/product/edit/' . $product;
        if (Session::has('breadcrumbs')) {
            Session::remove('breadcrumbs');
        }
        Session::put('breadcrumbs', $breadcrumbs);

        return view('app/merchant/product/edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        if ($request) {
            $product = new Product();
            if ($request['type'] == 'Service') {
                $request['goods_type'] = NULl;
            }
            $validator = $product->validateRules($request->all(), $this->merchant_id, $request['product_id']);

            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            } else {
                if ($request['goods_type'] == 'variable') {
                    $attr_errors = array();
                    if (!empty($request['attribute_values'])) {
                        $attr_keys = array_keys($request['attribute_values']);
                        $total_variations = count($request['price']);

                        for ($j = 0; $j < $total_variations; $j++) {
                            foreach ($attr_keys as $k => $attr_val) {
                                $attr_name = $request['attribute_values'][$attr_val][$j];
                                $string[$k] = $attr_name;
                            }
                            $tmp = array_filter($string);
                            if (empty($tmp)) {
                                $attr_errors[$j] = 'Please select at least one product attribute for variation row ' . ($j + 1);
                            }
                        }
                        if (!empty($attr_errors)) {
                            return redirect()->back()->withInput()->withErrors($attr_errors);
                        }
                    } else {
                        return redirect()->back()->withInput()->withErrors('Please add at least one product attributes and variable product');
                    }
                }

                //if image is changed then remove first image and store new image
                $updateProduct = $request->except('_token');
                unset($updateProduct['is_stock_keeping']);
                if ($request['type'] == 'Goods' && $request['goods_type'] == 'simple') {
                    $product = Product::findOrFail($request['product_id']);
                    if (isset($request->new_image) && !empty($request->new_image)) {
                        //check first file exist on s3 bucket
                        $exist = Storage::disk('s3_expense')->exists($product->product_image);
                        if ($exist) {
                            Storage::disk('s3_expense')->delete($product->product_image);
                        }
                        $updateProduct['product_image'] = (!empty($request['new_image'])) ? $request['new_image'] : NULL;
                    } else {
                        if ((!empty($request['new_image']))) {
                            $updateProduct['product_image'] = (!empty($request['new_image'])) ? $request['new_image'] : NULL;
                        } else if (!empty($request['old_image'])) {
                            $updateProduct['product_image'] = (!empty($request['old_image'])) ? $request['old_image'] : NULL;
                        }
                    }

                    unset($updateProduct['new_image']);
                    unset($updateProduct['old_image']);
                }
                if ($request['type'] == 'Service') {
                    unset($updateProduct['new_image']);
                }
                //find unit_name from unit_type_id
                if (isset($request['unit_type_id']) && !empty($request['unit_type_id'])) {
                    $getUnitType = DB::table('merchant_unit_type')->select('name')->where('id', $request['unit_type_id'])->first();
                    $updateProduct['unit_type'] = (isset($getUnitType->name) && !empty($getUnitType->name)) ? $getUnitType->name : NULL;
                } else {
                    $updateProduct['unit_type_id'] = 0;
                }

                if ($request['goods_type'] == 'variable') {
                    $this->setVariableProduct($updateProduct, $request['product_id']);
                } else {
                    $updateProductId = $product->updateProduct($updateProduct, $request['product_id']);

                    if ($updateProductId) {
                        //check if product has stock_keeping info added in stock_ledger table
                        if (isset($request['has_stock_keeping']) && ($request['has_stock_keeping'] == '1') && $request['available_stock'] != '0') {
                            //check stock_ledger data is added or edited
                            $checkStockExist = StockLedger::where('product_id', $request['product_id'])->where('reference_type', 1)->first();
                            if (empty($checkStockExist)) {
                                $saveStock['product_id'] = $updateProductId;
                                $saveStock['reference_id'] = $updateProductId;
                                $saveStock['quantity'] = $request['available_stock'];
                                $saveStock['amount'] = $request['price'];
                                $saveStock['reference_type'] = 1;
                                $saveStock['narrative'] = 'Stock added';
                                $stockLedger = new StockLedger();
                                $savedStockLedgerId = $stockLedger->saveStockLedger($saveStock);
                            }
                        } else {
                            //update is_active flag for the product in stock_ledger table if it has previous stock keeping data
                            $updatedQuery = StockLedger::where('product_id', $request['product_id'])->where('reference_type', 1)->update(['is_active' => 0]);
                        }
                    }
                }
                return redirect('merchant/product/index')->with('success', "Product has been updated");
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($product)
    {
        $product_id = Encrypt::decode($product);
        $getProduct = Product::select('has_stock_keeping', 'product_id', 'parent_id', 'type', 'goods_type')->where('product_id', $product_id)->first();

        if ($getProduct['goods_type'] == 'variable' && $getProduct['parent_id'] == 0) {
            $getVariationProducts = $this->productModel->getVariationProducts($product_id, $this->merchant_id);

            //find stock ledger for variable products
            if (!empty($getVariationProducts)) {
                foreach ($getVariationProducts as $vk => $varStk) {
                    $updatedVarProduct = Product::where('product_id', $varStk->product_id)->update(['is_active' => 0]);
                }
            }
        }

        $updatedQuery = Product::where('product_id', $product_id)->update(['is_active' => 0]);
        if ($updatedQuery) {
            return redirect('merchant/product/index')->with('success', "Product has been deleted");
        } else {
            return redirect('merchant/product/index')->with('error', "Product can not be deleted");
        }
    }

    public function getCommonData()
    {
        //find all active categories of merchant
        $getProductCategories = ProductCategory::where('is_active', 1)->where('merchant_id', $this->merchant_id)->pluck('name', 'id')->toArray();
        $data['productCategories'] = $getProductCategories;

        //find gst applicable
        $getGstTax = DB::table('config')->where('config_type', 'gst_tax')->pluck('config_key', 'config_value')->toArray();
        $data['gstTax'] = $getGstTax;

        //find active vendors of merchant
        $getVendors = DB::table('vendor')->where('is_active', 1)->where('merchant_id', $this->merchant_id)->pluck('vendor_name', 'vendor_id')->toArray();
        $data['getVendors'] = $getVendors;

        //find unit_types (default list & merchant added units) from merchant_unit_type table
        $getUnitTypes = DB::table('merchant_unit_type')->where('is_active', 1)->WhereIn('merchant_id', [$this->merchant_id, 'system'])->pluck('name', 'id')->toArray();
        $data['getUnitTypes'] = $getUnitTypes;

        //find product_attributes list
        $getProductAttributes = DB::table('merchant_product_attribute_metadata')->where('is_active', 1)->Where('merchant_id', $this->merchant_id)->pluck('name', 'id')->toArray();
        $attributesDropdown = array();
        if (!empty($getProductAttributes)) {
            foreach ($getProductAttributes as $pk => $pval) {
                $getProductAttributeValues = DB::table('merchant_product_attribute_metadata')->where('is_active', 1)->where('id', $pk)->Where('merchant_id', $this->merchant_id)->select('default_values')->first();
                $attributesDropdown[$pk]['name'] = $pval;
                $attributesDropdown[$pk]['values'] = json_decode($getProductAttributeValues->default_values, true);
            }
        }
        $data['productAttributes'] = $attributesDropdown;
        return $data;
    }

    public function checkInventoryServiceEnable()
    {
        $checkInventoryServiceactive = DB::table('account')->select('inventory')->where('merchant_id', $this->merchant_id)->where('inventory', 1)->where('is_active', 1)->first();
        if ($checkInventoryServiceactive != null && $checkInventoryServiceactive->inventory == 1) {
            return 1;
        }
        //select id  from merchant_active_apps where merchant_id=_merchant_id and service_id=15 and status=1;
        $checkInventoryServiceEnable = DB::table('merchant_active_apps')->select('status')->where('merchant_id', $this->merchant_id)->where('service_id', 15)->first();
        if ($checkInventoryServiceEnable != null) {
            return 2;
        }
        return 0;
    }

    public function dashboard(Request $request)
    {
        $title = 'Inventory Dashboard';
        $data = Helpers::setBladeProperties($title, ['product'], [171]);

        if (isset($request['pie_chart_filter']) && isset($request['bar_chart_filter'])) {
            $data['pie_chart_filter'] = $request['pie_chart_filter'];
            $data['bar_chart_filter'] = $request['bar_chart_filter'];
        } else {
            $data['pie_chart_filter'] = 'last_7_days';
            $data['bar_chart_filter'] = 'last_7_days';
        }

        //find dashboard statistics values
        $data['get_dashboard_statistics'] = $this->productModel->inventroy_dashboard_statistics($this->merchant_id);
        $data['stock_status'] = $this->productModel->stock_status($this->merchant_id, $data['pie_chart_filter']);
        $data['time_wise_sales_report'] = $this->productModel->get_time_wise_sales_status($this->merchant_id, $data['bar_chart_filter']);
        $data['enable_inventory'] = $this->checkInventoryServiceEnable();
        $data['service_id'] = $this->inventory_service_id;
        return view('app/merchant/product/inventory-dashboard', $data);
    }

    // public function mapFiters(Request $request) {
    //     if(isset($request['date_filter']) && isset($request['chart_type'])) {
    //         if($request['date_filter']=='last_7_days') {
    //             $days = '7 day';
    //         } else if($request['date_filter']=='this_month') {
    //             $days = '7 day';
    //         } else if($request['date_filter']=='last_6_months') {
    //             $days = '6 month';
    //         }

    //         if($request['chart_type']=='pie') {
    //             if(Session::has('pie_chart_filter')) {
    //                 Session::remove('pie_chart_filter');
    //             }
    //             Session::put('pie_chart_filter', $days);
    //             $response['stock_status'] = $this->productModel->stock_status($this->merchant_id,$days);
    //         } 
    //         if($request['chart_type']=='bar') {
    //             $response['time_wise_sales_report'] = $this->productModel->get_time_wise_sales_status($this->merchant_id,$days);
    //             //dd($data['time_wise_sales_report']);
    //         }
    //         $response['status'] = 1;
    //     } else {
    //         $response['status'] = 0;
    //     }
    //     echo json_encode($response);
    // }
}
