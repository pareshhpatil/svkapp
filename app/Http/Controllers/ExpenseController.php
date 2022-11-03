<?php

namespace App\Http\Controllers;

use App\Libraries\Helpers;
use App\Model\Expense;
use App\Model\Product;
use App\Libraries\Encrypt;
use Illuminate\Support\Facades\Session;
use Log;
use PHPExcel;
use Illuminate\Http\Request;
use App\Http\Controllers\ProductController;

class ExpenseController extends Controller
{

    private $expense_model = null;
    private $merchant_id = null;
    private $user_id = null;

    public function __construct()
    {
        $this->expense_model = new Expense();
        $this->merchant_id = Encrypt::decode(Session::get('merchant_id'));
        $this->user_id = Encrypt::decode(Session::get('userid'));
        $this->inventory_service_id = Encrypt::encode('15'); //15 service_id
    }

    /**
     * Renders form to store expense
     * Also convert purchase order to expense if link is not null
     *
     * @param $errors - After post form if there are validation errors it will display in form header
     * @param $link - Encrypted PO id. Convert purchase order to expense if link is not null
     * 
     * @return void
     */
    public function create($errors = null, $link = null, $type = null)
    {
        Helpers::hasRole(2, 27);
        $title = 'create';

        /**
         * Using below code while converting purchase order to expense
         * Getting purchase order data and set it to create expense form
         */
        $detail = array();
        $particulars = null;
        if ($link != null) {
            $expense_id = Encrypt::decode($link);
            if ($type == null) {
                $table = 'expense';
                $title = 'convert';
            } else {
                $table = 'staging_expense';
                $title = 'convert';
            }
            $detail = $this->expense_model->getExpenseData($expense_id, $this->merchant_id, $table);
            if (empty($detail)) {
                Log::error('Expense id does not exist' . $expense_id . ' Merchant id ' . $this->merchant_id);
                return redirect('/error/oops');
            }
            $particulars = $this->expense_model->getTableList($table . '_detail', 'expense_id', $expense_id);
        }

        Session::put('valid_ajax', 'expense');
        $data = Helpers::setBladeProperties(ucfirst($title) . ' expense', ['expense', 'product', 'template'], [143, 144, 148]);
        $data['gst_type'] = 'intra';
        $data['button'] = 'Save';
        $gst_list = $this->expense_model->getTableList('merchant_billing_profile', 'merchant_id', $this->merchant_id);
        $gst_array = array();
        foreach ($gst_list as $row) {
            $gst_array[] = $row->gst_number;
        }
        $data['gst_list'] = $gst_array;
        if ($type != null) {
            $data['approve'] = 1;
            $state = $this->expense_model->getColumnValue('merchant_billing_profile', 'merchant_id', $this->merchant_id, 'state');
            if (strtolower($detail->state) != strtolower($state)) {
                $data['gst_type'] = 'inter';
            }
            $data['button'] = 'Approve';
        }
        #Getting master data with merchant id
        $data['category'] = $this->expense_model->getTableList('expense_category', 'merchant_id', $this->merchant_id);
        $data['department'] = $this->expense_model->getTableList('expense_department', 'merchant_id', $this->merchant_id);
        $data['vendors'] = $this->expense_model->getTableList('vendor', 'merchant_id', $this->merchant_id);

        #Get auto generate expense no setting. Set default if not exist
        $data['expense_auto_generate'] = $this->expense_model->getColumnValue('merchant_setting', 'merchant_id', $this->merchant_id, 'expense_auto_generate');
        $exp_seq = $this->expense_model->getExpenseSequence($this->merchant_id, 3);
        if (!empty($exp_seq)) {
            $data['prefix'] = $exp_seq->prefix;
            $data['prefix_val'] = $exp_seq->val;
            $data['prefix_id'] = $exp_seq->auto_invoice_id;
        } else {
            $data['prefix'] = 'EXP/';
            $data['prefix_val'] = 0;
            $data['prefix_id'] = 0;
        }
        $data['detail'] = $detail;
        $data['particulars'] = $particulars;

        $product = new ProductController();

        $getData = $product->getCommonData();
        $data['productCategories'] = $getData['productCategories'];
        $data['gstTax'] = $getData['gstTax'];
        $data['getVendors'] = $getData['getVendors'];
        $data['getUnitTypes'] = $getData['getUnitTypes'];
        $data['enable_inventory'] = $product->checkInventoryServiceEnable();
        $data['service_id'] = $this->inventory_service_id;
        $data['link'] = isset($link) && !empty($link) ? $link : NULL;
        $data['table'] = isset($table) && !empty($table) ? $table : 'expense';
        $data['mode'] = 'create';
        $data['title'] = 'Create expense';

        return view('app/merchant/expense/' . $title, $data);
    }

    /**
     * Renders form to store purchase order 
     * 
     * @param $errors - After post form if there are validation errors it will display in form header
     * 
     * @return void
     */
    public function createpo($errors = null)
    {

        Helpers::hasRole(2, 27);
        Session::put('valid_ajax', 'expense');
        $data = Helpers::setBladeProperties('Create Purchase order', ['expense', 'product', 'template'], [143, 145, 149]);

        #Getting master data with merchant id
        $data['category'] = $this->expense_model->getTableList('expense_category', 'merchant_id', $this->merchant_id);
        $data['department'] = $this->expense_model->getTableList('expense_department', 'merchant_id', $this->merchant_id);
        $data['vendors'] = $this->expense_model->getTableList('vendor', 'merchant_id', $this->merchant_id);

        #Get auto generate po no setting. Set default if not exist
        $data['po_auto_generate'] = $this->expense_model->getColumnValue('merchant_setting', 'merchant_id', $this->merchant_id, 'po_auto_generate');
        $exp_seq = $this->expense_model->getExpenseSequence($this->merchant_id, 4);
        if (!empty($exp_seq)) {
            $data['prefix'] = $exp_seq->prefix;
            $data['prefix_val'] = $exp_seq->val;
            $data['prefix_id'] = $exp_seq->auto_invoice_id;
        } else {
            $data['prefix'] = 'PO/';
            $data['prefix_val'] = 0;
            $data['prefix_id'] = 0;
        }
        //$data['errors'] = $errors;

        $product = new ProductController();

        $getData = $product->getCommonData();
        $data['productCategories'] = $getData['productCategories'];
        $data['gstTax'] = $getData['gstTax'];
        $data['getVendors'] = $getData['getVendors'];
        $data['getUnitTypes'] = $getData['getUnitTypes'];
        $data['enable_inventory'] = $product->checkInventoryServiceEnable();
        $data['service_id'] = $this->inventory_service_id;
        $data['title'] = 'Create purchase order';
        return view('app/merchant/expense/create_po', $data);
    }

    /**
     * Save expense and display error if failed
     *
     * @return void
     */
    public function expensesave(Request $request)
    {

        $array = $this->setExpenseArray(0);

        $array['type'] = 1; # type 1 for expense and 2 for purchase order
        $array['notify'] = 1;

        $data[] = $array;
        $post_string = json_encode($data);

        #post form json to Swipez APi 
        $result = Helpers::APIrequest('v1/expense/save', $post_string);
        $result = json_decode($result, 1);
        if ($result['status'] == 0) {
            if (is_array($result['error'])) {
                $errors = $result['error'];
            } else {
                $errors[] = $result['error'];
            }
            //return $this->create($errors);
            return redirect()->back()->withInput()->withErrors($errors);
        } else {
            $expense_id = $result['expense_id'];

            #if this is convert po expense request then change po status as converted
            if (isset($_POST['convert_po_id'])) {
                $this->expense_model->updateExpense('status', 1, $_POST['convert_po_id']);
            }

            if (isset($_POST['payment_request_id'])) {
                $this->expense_model->updateExpense('payment_request_id', $_POST['payment_request_id'], $expense_id);
                $this->expense_model->updateExpense('type', 1, $_POST['staging_expense_id'], 'staging_expense');
            }

            if (isset($_POST['gst_number'])) {
                $this->expense_model->updateExpense('gst_number', $_POST['gst_number'], $expense_id);
            }

            #upload expense file
            if (!empty($_FILES['file']['size'] > 0)) {
                $file = Helpers::uploadFile($request->file('file'), $this->merchant_id, $expense_id, 'expense');
                $this->expense_model->updateExpense('file_path', $file, $expense_id);
            } else {
                if (isset($_POST['file_path'])) {
                    $this->expense_model->updateExpense('file_path', $_POST['file_path'], $expense_id);
                }
            }

            #if expense is paid then store vendor tranfer record
            if ($_POST['payment_status'] == 1) {
                $this->saveTransfer($expense_id);
            }
            Session::flash('success', $result['success']);
            return redirect('/merchant/expense/viewlist/expense');
        }
    }

    /**
     * If expense if paid then store in vendor transfer with expense id
     * 
     * @param type $expense_id - Save transfer against expense id
     * @param type $transfer_id - If transfer id not zero then update the transfer data with transfer id
     * 
     * @return void
     */
    public function saveTransfer($expense_id, $transfer_id = 0)
    {

        $data = (object) array();
        $data->vendor_id = $_POST['vendor_id'];
        $data->amount = ($_POST['amount'] > 0) ? $_POST['amount'] : 0;
        $data->narrative = $_POST['narrative'];
        $data->payment_mode = $_POST['payment_mode'];
        $data->date = Helpers::sqlDate($_POST['date']);
        $data->bank_name = $_POST['bank_name'];
        $data->bank_transaction_no = $_POST['bank_transaction_no'];
        $data->cheque_no = $_POST['cheque_no'];
        $data->cash_paid_to = $_POST['cash_paid_to'];

        #If transfer id not zero then update the transfer data with transfer id or save new record
        if ($transfer_id == 0) {
            $transfer_id = $this->expense_model->saveVendorTransfer($data, $this->merchant_id, $this->user_id);
            $this->expense_model->updateExpense('transfer_id', $transfer_id, $expense_id);
        } else {
            $this->expense_model->updateVendorTransfer($data, $transfer_id, $this->user_id);
        }
    }

    /**
     * Save purchase order and display error if failed
     *
     * @return void
     */
    public function posave()
    {

        $array = $this->setExpenseArray(0);

        $array['type'] = 2; # type 2 for purchase order and 1 for expesne
        $array['notify'] = $_POST['notify'];
        $data[] = $array;
        $post_string = json_encode($data);

        #post form json to Swipez APi 
        $result = Helpers::APIrequest('v1/expense/save', $post_string);
        $result = json_decode($result, 1);
        if ($result['status'] == 0) {
            if (is_array($result['error'])) {
                $errors = $result['error'];
            } else {
                $errors[] = $result['error'];
            }
            //return $this->createpo($errors);
            return redirect()->back()->withInput()->withErrors($errors);
        } else {
            #if notify flag is true then send mail to vendor
            if ($_POST['notify'] == 1) {
                $this->sendPOMail(Encrypt::encode($result['expense_id']));
            }

            Session::flash('success', $result['success']);
            return redirect('/merchant/expense/viewlist/po');
        }
    }

    /**
     * 
     * Set expense post details and return array for save or update expense / PO
     * 
     * @param staging $staging - If expense is staging then do not store expense no
     * 
     * @return array
     * 
     */
    public function setExpenseArray($staging = 0)
    {
        $array['vendor_id'] = $_POST['vendor_id'];
        $array['category_id'] = $_POST['category_id'];
        $array['department_id'] = $_POST['department_id'];
        $array['invoice_no'] = $_POST['invoice_no'];
        if ($staging == 0) {
            $array['expense_no'] = $_POST['expense_no'];
        }
        $array['bill_date'] = Helpers::sqlDate($_POST['bill_date']);
        $array['due_date'] = Helpers::sqlDate($_POST['due_date']);
        $array['base_amount'] = $_POST['sub_total'];
        $array['total_amount'] = $_POST['total'];
        $array['cgst_amount'] = isset($_POST['cgst_amt']) ? $_POST['cgst_amt'] : 0;
        $array['sgst_amount'] = isset($_POST['sgst_amt']) ? $_POST['sgst_amt'] : 0;
        $array['igst_amount'] = isset($_POST['igst_amt']) ? $_POST['igst_amt'] : 0;
        if (isset($_POST['gst_number'])) {
            $array['gst_number'] = $_POST['gst_number'];
        }

        $array['tds'] = $_POST['tds'];
        $array['discount'] = ($_POST['discount'] > 0) ? $_POST['discount'] : 0;
        $array['adjustment'] = ($_POST['adjustment'] != '') ? $_POST['adjustment'] : 0;
        $_POST['payment_status'] = (isset($_POST['payment_status'])) ? $_POST['payment_status'] : 0;
        $array['payment_status'] = $_POST['payment_status'];
        if (isset($_POST['payment_mode'])) {
            $array['payment_mode'] = ($_POST['payment_mode'] > 0) ? $_POST['payment_mode'] : 0;
        } else {
            $array['payment_mode'] = 0;
        }
        $array['narrative'] = $_POST['narrative'];
        //$tax_array = array('3' => 5, '4' => 12, '5' => 18, '6' => 28);
        $tax_array = array('5' => 5, '12' => 12, '18' => 18, '28' => 28);
        $productModel = new Product();
        #set particular array
        if (!empty($_POST['particular'])) {
            foreach ($_POST['particular'] as $key => $value) {
                $particulars[$key]['expense_detail_id'] = isset($_POST['expense_detail_id'][$key]) && ($_POST['expense_detail_id'][$key] != 0) ? $_POST['expense_detail_id'][$key] : 0;
                $particulars[$key]['product_id'] = $_POST['particular'][$key];
                $getData = $productModel->getProductDetail($_POST['particular'][$key], $this->merchant_id);
                $particulars[$key]['particular_name'] = $getData->product_name; //$_POST['particular'][$key];
                $particulars[$key]['tax'] = $_POST['tax'][$key];
                $particulars[$key]['sac'] = $_POST['sac'][$key];
                $particulars[$key]['qty'] = $_POST['unit'][$key];
                $particulars[$key]['rate'] = $_POST['rate'][$key];
                $particulars[$key]['sale_price'] = $_POST['sale_price'][$key];
                //$particulars[$key]['particular_id'] = isset($_POST['particular_id'][$key]) && ($_POST['particular_id'][$key] != 'Na') ? $_POST['particular_id'][$key] : 0;
                $rate = ($_POST['rate'][$key] != '') ? $_POST['rate'][$key] : 0;
                $unit = ($_POST['unit'][$key] != '') ? $_POST['unit'][$key] : 0;
                $amount = $rate * $unit;
                $particulars[$key]['amount'] = $amount;
                $tax_amount = 0;
                $cgst_amt = 0;
                $igst_amt = 0;
                $percent = 0;
                if ($_POST['tax'][$key] > 2) {
                    $percent = $tax_array[$_POST['tax'][$key]];
                    $tax_amount = $amount * $percent / 100;
                    if ($_POST['gst_type'] == 'intra') {
                        $cgst_amt = $tax_amount / 2;
                    } else {
                        $igst_amt = $tax_amount;
                    }
                }
                $particulars[$key]['gst_percent'] = $percent;
                $particulars[$key]['cgst_amount'] = $cgst_amt;
                $particulars[$key]['sgst_amount'] = $cgst_amt;
                $particulars[$key]['igst_amount'] = $igst_amt;

                $particulars[$key]['total_value'] = $amount + $tax_amount;

                //if sac code change then update in merchant_product master table
                if ($getData->sac_code != $particulars[$key]['sac'] && !empty($particulars[$key]['sac'])) {
                    $updateProduct['sac_code'] = $particulars[$key]['sac'];
                    $updateProductId = $productModel->updateProduct($updateProduct, $_POST['particular'][$key]);
                }
                //if sale price change then update in merchant_product master table
                if ($getData->price != $particulars[$key]['sale_price'] && !empty($particulars[$key]['sale_price'])) {
                    $updateProduct['price'] = $particulars[$key]['sale_price'];
                    $updateProductId = $productModel->updateProduct($updateProduct, $_POST['particular'][$key]);
                }
            }
            $array['particular'] = $particulars;
        }
        return $array;
    }

    /**
     * Update expense and po information
     * 
     * @return void
     * 
     */
    public function updatesave(Request $request)
    {

        $link = $_POST['expense_id'];
        $array = $this->setExpenseArray(0);
        $array['expense_id'] = Encrypt::decode($link);

        $array['notify'] = 1;
        $array['type'] = $_POST['type'];
        $post_string = json_encode($array);

        $result = Helpers::APIrequest('v1/expense/updatesave', $post_string);
        $result = json_decode($result, 1);
        if ($result['status'] == 0) {
            if (is_array($result['error'])) {
                $errors = $result['error'];
            } else {
                $errors[] = $result['error'];
            }
            return redirect()->back()->withInput()->withErrors($errors);
            //return $this->update($link, $errors);
        } else {
            $expense_id = $result['expense_id'];
            $type = ($_POST['type'] == 1) ? 'expense' : 'po';
            if (isset($_FILES['file']['size'])) {
                if (!empty($_FILES['file']['size'] > 0)) {
                    $file = Helpers::uploadFile($request->file('file'), $this->merchant_id, $expense_id, 'expense');
                    if ($file != false) {
                        $this->expense_model->updateExpense('file_path', $file, $expense_id);
                    }
                } else {
                    if (isset($_POST['file_path'])) {
                        $this->expense_model->updateExpense('file_path', $_POST['file_path'], $expense_id);
                    }
                }
            } else {
                if (isset($_POST['file_path'])) {
                    $this->expense_model->updateExpense('file_path', $_POST['file_path'], $expense_id);
                }
            }

            if (isset($_POST['staging_expense_id'])) {
                $this->expense_model->updateExpense('type', 1, $_POST['staging_expense_id'], 'staging_expense');
            }

            #if expense is paid then store vendor tranfer record 
            if ($_POST['payment_status'] == 1) {
                $this->saveTransfer($expense_id);
            }
            Session::flash('success', $result['success']);
            return redirect('/merchant/expense/viewlist/' . $type);
        }
    }

    /**
     * Update staging expense information
     * 
     * @return void
     * 
     */
    public function bulkupdatesave(Request $request)
    {
        $link = $_POST['expense_id'];
        $array = $this->setExpenseArray(1);
        $array['expense_id'] = Encrypt::decode($link);
        $expense_id = $array['expense_id'];
        $array['is_bulk'] = 1;
        $array['notify'] = 1;
        $array['expense_no'] = $_POST['expense_no'];
        $post_string = json_encode($array);
        $result = Helpers::APIrequest('v1/expense/updatesave', $post_string);
        $result = json_decode($result, 1);

        if ($result['status'] == 0) {
            if (is_array($result['error'])) {
                $errors = $result['error'];
            } else {
                $errors[] = $result['error'];
            }
            return redirect()->back()->withInput()->withErrors($errors);
            //return $this->bulkupdate($link, $errors);
        } else {
            $bulk_id = $this->expense_model->getColumnValue('staging_expense', 'expense_id', $expense_id, 'bulk_id');
            if (isset($_FILES['file'])) {
                if (!empty($_FILES['file']['size'] > 0)) {
                    $file = Helpers::uploadFile($request->file('file'), $this->merchant_id, $expense_id, 'expense');
                    if ($file != false) {
                        $this->expense_model->updateExpense('file_path', $file, $expense_id, 'staging_expense');
                    }
                }
            }
            Session::flash('success', $result['success']);
            return redirect('/merchant/vendor/bulklist/expense/' . Encrypt::encode($bulk_id));
        }
    }

    /**
     * Update expense payment details
     * 
     * @return void
     */
    public function updatepayment()
    {

        $link = $_POST['expense_id'];
        $expense_id = Encrypt::decode($link);
        $this->expense_model->updatePaymentData($_POST['payment_status'], $_POST['payment_mode'], $expense_id, $this->user_id);
        if ($_POST['payment_status'] == 1) {
            $this->saveTransfer($expense_id);
        }
        Session::flash('success', 'Payment status updated successfully');
        return redirect('/merchant/expense/viewlist/expense');
    }

    /*
     * Display staging expense information with expense id
     * 
     * @param $link - Encrypted expense id
     *
     * @return void
     */

    public function bulkview($link)
    {
        return $this->view($link, 'staging_expense');
    }

    /*
     * Resend purchase order mail to vendor 
     *
     * @param $link - Encrypted po id
     * 
     * @return void
     */

    public function resend($link)
    {
        $this->sendPOMail($link);
        Session::flash('success', 'Email sent successfully');
        return redirect('/merchant/expense/viewlist/po');
    }

    /**
     * 
     * Send purchase order mail to vendor
     * 
     * @param $link - Encrypted expense id
     * 
     * @return void
     */
    private function sendPOMail($link)
    {
        $data = $this->setExpenseData($link);
        $merchant = $data['merchant'];
        Helpers::sendMail($data['email_id'], 'po', $data, 'Purchase order from ' . $merchant->company_name);
    }

    /**
     * 
     * Set expense data to array which can be use display expense/ PO or send PO mail
     * 
     * @param type $link - Encrypted expense id
     * @param type $table - get expense data from expense table or staging expense table
     * 
     * @return array
     */
    private function setExpenseData($link, $table = 'expense')
    {
        Helpers::hasRole(1, 27);
        $expense_id = Encrypt::decode($link);
        $detail = $this->expense_model->getExpenseData($expense_id, $this->merchant_id, $table);
        if ($detail->type == 2) {
            $name = 'Purchase order';
            $menu = 151;
            $type = 'po';
        } else {
            $name = 'Expense';
            $menu = 150;
            $type = 'expense';
        }
        $data = Helpers::setBladeProperties($name . ' detail', [], [143, 145, $menu]);

        # if type is PO then get merchant information to display on header
        if ($type == 'po') {
            $merchant = $this->expense_model->getTableRow('merchant_detail', 'merchant_id', $detail->merchant_id);
            $data['merchant'] = $merchant;
        }

        $particulars = $this->expense_model->getTableList($table . '_detail', 'expense_id', $expense_id);
        $data['email_id'] = $detail->email_id;
        $data['type'] = $type;
        $data['link'] = $link;
        $data['btn_update'] = ($table == 'expense') ? 'update' : 'bulkupdate';
        $data['detail'] = $detail;
        $data['particulars'] = $particulars;
        return $data;
    }

    /**
     * 
     * 
     * Display expense or PO details 
     * 
     * @param type $link
     * @param type $table
     * 
     * @return void
     */
    public function view($link, $table = 'expense')
    {
        $data = $this->setExpenseData($link, $table);
        return view('app/merchant/expense/view_' . $data['type'], $data);
    }

    /**
     * 
     * 
     * render staging expense update form
     * 
     * @param type $link
     * @param type $errors
     * 
     * @return void
     */
    public function bulkupdate($link, $errors = null)
    {
        return $this->update($link, $errors, 'staging_expense');
    }

    /**
     * 
     * render expense or staging expense update form
     * 
     * @param type $link
     * @param type $errors
     * @param type $table

     * 
     * @return void
     */
    public function update($link, $errors = null, $table = 'expense', $approve_id = null)
    {

        Helpers::hasRole(2, 27);
        Session::put('valid_ajax', 'expense');
        $data = Helpers::setBladeProperties('Update expense', ['expense', 'product', 'template'], [143, 145]);

        #Getting master data with merchant id
        $data['category'] = $this->expense_model->getTableList('expense_category', 'merchant_id', $this->merchant_id);
        $data['department'] = $this->expense_model->getTableList('expense_department', 'merchant_id', $this->merchant_id);
        $data['vendors'] = $this->expense_model->getTableList('vendor', 'merchant_id', $this->merchant_id);
        $data['expense_auto_generate'] = $this->expense_model->getColumnValue('merchant_setting', 'merchant_id', $this->merchant_id, 'expense_auto_generate');
        $data['btn_update'] = ($table == 'expense') ? 'updatesave' : 'bulkupdatesave';
        $expense_id = Encrypt::decode($link);
        $detail = $this->expense_model->getExpenseData($expense_id, $this->merchant_id, $table);
        $data['button'] = 'Save';
        if ($approve_id != null) {
            $approve_expense_id = Encrypt::decode($approve_id);
            $apprDetail = $this->expense_model->getExpenseData($approve_expense_id, $this->merchant_id, 'staging_expense');
            $detail->invoice_no = $apprDetail->invoice_no;
            $detail->bill_date = $apprDetail->bill_date;
            $detail->due_date = $apprDetail->due_date;
            $detail->base_amount = $apprDetail->base_amount;
            $detail->cgst_amount = $apprDetail->cgst_amount;
            $detail->sgst_amount = $apprDetail->sgst_amount;
            $detail->igst_amount = $apprDetail->igst_amount;
            $detail->total_amount = $apprDetail->total_amount;
            $detail->narrative = $apprDetail->narrative;
            $detail->expense_id = $approve_expense_id;
            $table = 'staging_expense';
            $expense_id = $approve_expense_id;
            $data['approve'] = 1;
            $data['button'] = 'Approve';
        }

        #if expense if paid then fetch transfer details 
        $data['transfer_details'] = array();

        if ($detail->transfer_id > 0) {
            $transfer_details = $this->expense_model->getTableRow('vendor_transfer', 'transfer_id', $detail->transfer_id);
            $data['transfer_details'] = $transfer_details;
            $data['script'] .= 'responseType(' . $detail->payment_mode . ');';
        }

        $particulars = $this->expense_model->getTableList($table . '_detail', 'expense_id', $expense_id);
        $data['link'] = $link;
        $data['detail'] = $detail;
        $data['particulars'] = $particulars;
        //$data['errors'] = $errors;
        $data['script'] .= 'setVendorState(' . $detail->vendor_id . ');';
        $product = new ProductController();
        $getData = $product->getCommonData();
        $data['productCategories'] = $getData['productCategories'];
        $data['gstTax'] = $getData['gstTax'];
        $data['getVendors'] = $getData['getVendors'];
        $data['getUnitTypes'] = $getData['getUnitTypes'];
        $data['enable_inventory'] = $product->checkInventoryServiceEnable();
        $data['service_id'] = $this->inventory_service_id;
        $data['table'] = $table; //expense or staging expense
        $data['title'] = 'Update expense';
        return view('app/merchant/expense/update', $data);
    }

    /*
     * 
     * Convert Purchase order to expense
     * Render expense create form with details filled of purchase order
     * 
     * @param type $link
     * 
     * @return void
     */

    public function convertexpense($link)
    {
        return $this->create(null, $link);
    }

    public function approveexpense($link)
    {
        $expense_id = Encrypt::decode($link);
        $payment_request_id = $this->expense_model->getColumnValue('staging_expense', 'expense_id', $expense_id, 'payment_request_id');
        $expense_id = $this->expense_model->getColumnValue('expense', 'payment_request_id', $payment_request_id, 'expense_id');
        if ($expense_id == false) {
            return $this->create(null, $link, 'approve');
        } else {
            $exlink = Encrypt::encode($expense_id);
            return $this->update($exlink, null, 'expense', $link);
        }
    }

    /*
     * 
     * Display list of expense or purchase order 
     * If bulk id param is not null then display bulk expense with this bulk id

     * @param type $expense_type
     * @param type $errors
     * @param type $bulk_id
     * @return void
     */

    public function viewlist($expense_type, $errors = null, $bulk_id = null)
    {

        Helpers::hasRole(1, 27);
        if ($expense_type == 'po') {
            $name = 'purchase order';
            $menu = [143, 145, 151];
            $type = 2;
        } else {
            $name = 'expense';
            $menu = [143, 144, 150];
            $type = 1;
        }
        $dates = Helpers::setListDates();
        Session::put('valid_ajax', 'expense');
        $data = Helpers::setBladeProperties(ucfirst($name) . ' list', ['expense'],$menu );

        $data['category_id'] = (isset($_POST['category_id'])) ? $_POST['category_id'] : 0;
        $data['department_id'] = (isset($_POST['department_id'])) ? $_POST['department_id'] : 0;
        $data['payment_status'] = (isset($_POST['payment_status'])) ? $_POST['payment_status'] : '';

        #Getting master data with merchant id
        $data['category'] = $this->expense_model->getTableList('expense_category', 'merchant_id', $this->merchant_id);
        $data['department'] = $this->expense_model->getTableList('expense_department', 'merchant_id', $this->merchant_id);

        #If bulk id is not null then display bulk expense with this bulk id
        if ($bulk_id == null) {
            $data['bulk'] = 0;
            $list = $this->expense_model->getExpenseList($this->merchant_id, $dates['from_date'], $dates['to_date'], $type, $data['category_id'], $data['department_id'], $data['payment_status']);
        } else {
            $data['bulk'] = 1;
            $list = $this->expense_model->getBulkExpenseList($this->merchant_id, $bulk_id, 'expense', $data['category_id'], $data['department_id'], $data['payment_status']);
        }

        #Add encrypted link for update or delete expense
        foreach ($list as $key => $row) {
            $list[$key]->encrypted_id = Encrypt::encode($row->expense_id);
        }
        $data['datatablejs'] = 'table-no-export';
        $data['list'] = $list;
        $data['errors'] = $errors;
        return view('app/merchant/expense/' . $expense_type . '_viewlist', $data);
    }

    public function pendingExpense()
    {

        Helpers::hasRole(1, 27);
        Session::put('valid_ajax', 'expense');
        $data = Helpers::setBladeProperties('Incoming expenses', ['expense'], [143, 169]);
        $dates = Helpers::setListDates();
        $list = $this->expense_model->getPendingExpenseList($this->merchant_id, $dates['from_date'], $dates['to_date']);
        #Add encrypted link for update or delete expense
        foreach ($list as $key => $row) {
            $list[$key]->encrypted_id = Encrypt::encode($row->expense_id);
        }
        $data['datatablejs'] = 'table-no-export';
        $data['list'] = $list;
        return view('app/merchant/expense/pending_viewlist', $data);
    }

    /*
     * Display bulk expense list with encrypted bulk id
     * 
     * @param type $link
     * 
     * @return void
     */

    public function bulkexpense($link)
    {
        $bulk_id = Encrypt::decode($link);
        return $this->viewlist('expense', null, $bulk_id);
    }

    /*
     * Display staging expense list with encrypted bulk id
     * @param type $link

     * @return void
     */

    public function bulklist($link)
    {

        Helpers::hasRole(1, 27);
        $bulk_id = Encrypt::decode($link);
        $data = Helpers::setBladeProperties('List expense', ['expense'], [143, 145, 153]);
        $list = $this->expense_model->getBulkExpenseList($this->merchant_id, $bulk_id);
        #Add encrypted link for update or delete expense
        foreach ($list as $key => $row) {
            $list[$key]->encrypted_id = Encrypt::encode($row->expense_id);
        }
        $data['datatablejs'] = 'table-no-export';
        $data['list'] = $list;
        return view('app/merchant/expense/bulk_viewlist', $data);
    }

    /*
     * Render create category form
     * 
     * @return void
     */

    public function createcategory($errors = null)
    {

        Helpers::hasRole(1, 27);
        $data = Helpers::setBladeProperties('Create category', [], [143, 145]);
        $data['errors'] = $errors;
        return view('app/merchant/expense/createcategory', $data);
    }

    /*
     * Save category master data
     * 
     * @return void
     */

    public function categorysave()
    {

        if ($_POST['category'] != '') {
            $exist = $this->expense_model->isExistData($this->merchant_id, 'expense_category', 'name', $_POST['category']);
            if ($exist == true) {
                $errors[] = 'Category name already exist';
            } else {
                $this->expense_model->saveMaster('expense_category', $_POST['category'], $this->merchant_id, $this->user_id);
                Session::flash('success', 'Category saved successfully');
                return redirect('/merchant/expense/category');
            }
        } else {
            $errors[] = 'Category empty';
        }
        return $this->listcategory($errors);
    }

    /*
     * Update category data
     * 
     * @return void
     */

    public function categoryupdatesave()
    {

        if ($_POST['category'] != '') {
            $id = Encrypt::decode($_POST['id']);
            $this->expense_model->updateMaster('expense_category', $_POST['category'], $id, $this->user_id);
            Session::flash('success', 'Category updated successfully');
            return redirect('/merchant/expense/category');
        } else {
            $errors[] = 'Category empty';
            return $this->listcategory($errors);
        }
    }

    /*
     * Display list of category
     * 
     * @return void
     */

    public function listcategory($errors = null)
    {

        Helpers::hasRole(1, 27);
        $data = Helpers::setBladeProperties('Category list', ['expense'], [143, 146]);
        $list = $this->expense_model->getTableList('expense_category', 'merchant_id', $this->merchant_id);
        foreach ($list as $key => $row) {
            $list[$key]->encrypted_id = Encrypt::encode($row->id);
        }
        $data['category'] = $list;
        $data['errors'] = $errors;
        return view('app/merchant/expense/listcategory', $data);
    }

    /*
     * Render create department form
     * 
     * @return void
     */

    public function createdepartment($errors = null)
    {

        Helpers::hasRole(2, 27);
        $data = Helpers::setBladeProperties('Create department', [], [143, 146]);
        $data['errors'] = $errors;
        return view('app/merchant/expense/createdepartment', $data);
    }

    /*
     * Save department master data
     * 
     * @return void
     */

    public function departmentsave()
    {

        if ($_POST['department'] != '') {
            $exist = $this->expense_model->isExistData($this->merchant_id, 'expense_department', 'name', $_POST['department']);
            if ($exist == true) {
                $errors[] = 'Department name already exist';
            } else {
                $this->expense_model->saveMaster('expense_department', $_POST['department'], $this->merchant_id, $this->user_id);
                Session::flash('success', 'Department saved successfully');
                return redirect('/merchant/expense/department');
            }
        } else {
            $errors[] = 'Department empty';
        }
        return $this->listdepartment($errors);
    }

    /*
     * Update department data
     * 
     * @return void
     */

    public function departmentupdatesave()
    {

        if ($_POST['department'] != '') {
            $id = Encrypt::decode($_POST['id']);
            $this->expense_model->updateMaster('expense_department', $_POST['department'], $id, $this->user_id);
            Session::flash('success', 'Department updated successfully');
            return redirect('/merchant/expense/department');
        } else {
            $errors[] = 'Department empty';
            return $this->createdepartment($errors);
        }
    }

    /*
     * Display list of department
     * 
     * @return void
     */

    public function listdepartment($errors = null)
    {

        Helpers::hasRole(1, 27);
        $data = Helpers::setBladeProperties('Department list', ['expense'], [143, 147]);
        $list = $this->expense_model->getTableList('expense_department', 'merchant_id', $this->merchant_id);
        #Add encrypted link for update or delete department
        foreach ($list as $key => $row) {
            $list[$key]->encrypted_id = Encrypt::encode($row->id);
        }
        $data['department'] = $list;
        $data['errors'] = $errors;
        return view('app/merchant/expense/listdepartment', $data);
    }

    /*
     * Disable table data eg. Category,Department,expense,PO
     * 
     * @return void
     */

    public function deleteMaster($table, $link)
    {

        Helpers::hasRole(3, 27);
        $id = Encrypt::decode($link);
        Session::flash('success', ucfirst($table) . ' deleted successfully');
        if ($table == 'expense' || $table == 'po') {
            $this->expense_model->deleteTableRow('expense', 'expense_id', $id, $this->merchant_id, $this->user_id);
            $this->expense_model->stockManagement($id, $this->merchant_id, 2);
            return redirect('/merchant/expense/viewlist/' . $table);
        } else if ($table == 'bulkexpense') {
            Session::flash('success', 'Expense deleted successfully');
            $this->expense_model->deleteTableRow('staging_expense', 'expense_id', $id, $this->merchant_id, $this->user_id);
            $bulk_id = $this->expense_model->getColumnValue('staging_expense', 'expense_id', $id, 'bulk_id');
            if ($bulk_id == 0) {
                return redirect('/merchant/expense/pending');
            }
            return redirect('/merchant/vendor/bulklist/expense/' . Encrypt::encode($bulk_id));
        } else if ($table == 'creditnote') {
            $this->expense_model->deleteTableRow('credit_debit_note', 'id', $id, $this->merchant_id, $this->user_id);
            $this->expense_model->deleteLedger($id);

            return redirect('/merchant/creditnote/viewlist');
        } else if ($table == 'debitnote') {
            $this->expense_model->deleteTableRow('credit_debit_note', 'id', $id, $this->merchant_id, $this->user_id);
            return redirect('/merchant/debitnote/viewlist');
        } else {
            $this->expense_model->deleteTableRow('expense_' . $table, 'id', $id, $this->merchant_id, $this->user_id);
            return redirect('/merchant/expense/' . $table);
        }
    }

    /*
     * Export expense bulk upload format
     * 
     * @return void
     */

    public function exportFormat()
    {

        Helpers::hasRole(2, 27);
        $expense_auto_generate = $this->expense_model->getColumnValue('merchant_setting', 'merchant_id', $this->merchant_id, 'expense_auto_generate');
        if ($expense_auto_generate == 0) {
            $column_name[] = 'Expense no';
        }
        $column_name[] = 'Vendor ID';
        $column_name[] = 'Category';
        $column_name[] = 'Department';
        $column_name[] = 'Invoice no';
        $column_name[] = 'Bill date';
        $column_name[] = 'Due date';
        $column_name[] = 'TDS (%)';
        $column_name[] = 'Discount (Rs)';
        $column_name[] = 'Adjustment (Rs)';
        $column_name[] = 'Payment status';
        $column_name[] = 'Payment mode';
        $column_name[] = 'Narrative';
        $column_name[] = 'Particular';
        $column_name[] = 'SAC/HSN code';
        $column_name[] = 'Unit';
        $column_name[] = 'Rate';
        $column_name[] = 'GST (%)';
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("swipez")
            ->setLastModifiedBy("swipez")
            ->setTitle("swipez_Expense")
            ->setDescription("swipez expense template");
        #create array of excel column
        $first = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
        $column = array();
        foreach ($first as $s) {
            $column[] = $s . '1';
        }
        foreach ($first as $f) {
            foreach ($first as $s) {
                $column[] = $f . $s . '1';
            }
        }
        $int = 0;
        foreach ($column_name as $col) {
            $objPHPExcel->getActiveSheet()->setCellValue($column[$int], $col);
            $int = $int + 1;
        }
        $objPHPExcel->getDefaultStyle()->getFont()->setName('verdana')
            ->setSize(10);
        $objPHPExcel->getActiveSheet()->setTitle('Expense template');
        $int++;
        $autosize = 0;
        while ($autosize < $int) {
            $objPHPExcel->getActiveSheet()->getColumnDimension(substr($column[$autosize], 0, -1))->setAutoSize(true);
            $autosize++;
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="expense.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        $objPHPExcel->disconnectWorksheets();
        unset($objPHPExcel);
        exit;
    }


    function expensebulksave()
    {
        $expense_model = new Expense();
        $list = $expense_model->getPendingBulkExpense();
        foreach ($list as $item) {
            $expense_list = $expense_model->getStagingExpenseList($item->bulk_upload_id);
            foreach ($expense_list as $exp) {
                $expense_no = $this->getExpenseNumber($exp->expense_no, $exp->merchant_id);
                $reqs = $expense_model->bulkexpensesave($exp->expense_id, $expense_no);
            }
            $expense_model->updateBulkUploadStatus($item->bulk_upload_id, 5);
        }
    }

    public function getExpenseNumber($expense_no, $merchant_id)
    {
        $expense_model = new Expense();
        if ($expense_no == 'Auto generate') {
            $seq_detail = $expense_model->getExpenseSequence($merchant_id, 3);
            $data = $expense_model->expenseNumber($seq_detail->auto_invoice_id);
            return $data[0]['auto_invoice_number'];
        } else {
            return $expense_no;
        }
    }
}
