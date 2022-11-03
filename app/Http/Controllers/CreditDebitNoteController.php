<?php

namespace App\Http\Controllers;

use DOMPDF;
use App\Libraries\Helpers;
use App\Model\Expense;
use App\Model\CreditDebitNote;
use App\Libraries\Encrypt;
use Illuminate\Support\Facades\Session;
use Exception;
use Log;
use PHPExcel;
use Illuminate\Http\Request;

class CreditDebitNoteController extends Controller
{

    private $expense_model = null;
    private $credit_model = null;
    private $merchant_id = null;
    private $user_id = null;

    public function __construct()
    {
        $this->expense_model = new Expense();
        $this->credit_model = new CreditDebitNote();
        $this->merchant_id = Encrypt::decode(Session::get('merchant_id'));
        $this->user_id = Encrypt::decode(Session::get('userid'));
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
    public function createCreditNote($errors = null)
    {
        Helpers::hasRole(2, 27);
        $title = 'create';

        $detail = array();
        $particulars = null;
        Session::put('valid_ajax', 'expense');
        $data = Helpers::setBladeProperties(ucfirst($title) . ' credit note', ['expense', 'template'], [5,160, 163]);

        #Getting master data with merchant id
        $data['customers'] = $this->expense_model->getTableList('customer', 'merchant_id', $this->merchant_id);

        #Get auto generate expense no setting. Set default if not exist
        $data['credit_note_auto_generate'] = $this->expense_model->getColumnValue('merchant_setting', 'merchant_id', $this->merchant_id, 'credit_note_auto_generate');
        $exp_seq = $this->expense_model->getExpenseSequence($this->merchant_id, 5);
        if (!empty($exp_seq)) {
            $data['prefix'] = $exp_seq->prefix;
            $data['prefix_val'] = $exp_seq->val;
            $data['prefix_id'] = $exp_seq->auto_invoice_id;
        } else {
            $data['prefix'] = 'CN/';
            $data['prefix_val'] = 0;
            $data['prefix_id'] = 0;
        }
        $data['errors'] = $errors;
        $data['detail'] = $detail;
        $data['particulars'] = $particulars;
        $data['type'] = 1;
        $food_franchise_mids = explode(',', env('FOOD_FRANCHISE_MERCHANT_ID', ''));
        if (!empty($food_franchise_mids)) {
            if (in_array($this->merchant_id, $food_franchise_mids)) {
                $data['type'] = 2;
            }
        }
        $food_franchise_mids = explode(',', env('FOOD_FRANCHISE_NON_BRAND', ''));
        if (!empty($food_franchise_mids)) {
            if (in_array($this->merchant_id, $food_franchise_mids)) {
                $data['type'] = 3;
            }
        }
        return view('app/merchant/creditdebit/' . $title, $data);
    }

    public function createDebitNote($errors = null)
    {
        Helpers::hasRole(2, 27);
        $title = 'create';
        $detail = array();
        $particulars = null;
        Session::put('valid_ajax', 'expense');
        $data = Helpers::setBladeProperties(ucfirst($title) . ' debit note', ['expense', 'template'], [143, 161, 176]);

        #Getting master data with merchant id
        $data['vendors'] = $this->expense_model->getTableList('vendor', 'merchant_id', $this->merchant_id);

        #Get auto generate expense no setting. Set default if not exist
        $data['debit_note_auto_generate'] = $this->expense_model->getColumnValue('merchant_setting', 'merchant_id', $this->merchant_id, 'debit_note_auto_generate');
        $exp_seq = $this->expense_model->getExpenseSequence($this->merchant_id, 6);
        if (!empty($exp_seq)) {
            $data['prefix'] = $exp_seq->prefix;
            $data['prefix_val'] = $exp_seq->val;
            $data['prefix_id'] = $exp_seq->auto_invoice_id;
        } else {
            $data['prefix'] = 'DN/';
            $data['prefix_val'] = 0;
            $data['prefix_id'] = 0;
        }
        $data['errors'] = $errors;
        $data['detail'] = $detail;
        $data['particulars'] = $particulars;
        return view('app/merchant/creditdebit/create_debit_note', $data);
    }

    /**
     * Save expense and display error if failed
     *
     * @return void
     */
    public function creditnotesave(Request $request)
    {
        $array = $this->setCreditNoteArray();

        $array['notify'] = 1;
        $post_string = json_encode($array);
        #post form json to Swipez APi 
        $result = Helpers::APIrequest('v1/creditdebitnote/save', $post_string);
        $result = json_decode($result, 1);
        if (!isset($result['status'])) {
            $result['status'] = 0;
            $result['error'] = 'Something went wrong';
        }
        if ($result['status'] == 0) {
            if (is_array($result['error'])) {
                $errors = $result['error'];
            } else {
                $errors[] = $result['error'];
            }
            return $this->createCreditNote($errors);
        } else {

            if ($request->credit_note_type == 2 || $request->credit_note_type == 3) {
                if (!empty($request->sale_date)) {
                    $dates = array();
                    foreach ($request->sale_date as $key => $date) {
                        $date = Helpers::sqlDate($date);
                        $dates[] = $date;
                        $invoice_sale = ($_POST['gross_sale'][$key] > 0) ? $_POST['gross_sale'][$key] : 0;
                        $note_sale = ($_POST['new_gross_sale'][$key] > 0) ? $_POST['new_gross_sale'][$key] : 0;
                        $non_brand_invoice_sale = 0;
                        $non_brand_note_sale = 0;
                        if (isset($_POST['non_brand_gross_sale'])) {
                            $non_brand_invoice_sale = ($_POST['non_brand_gross_sale'][$key] > 0) ? $_POST['non_brand_gross_sale'][$key] : 0;
                            $non_brand_note_sale = ($_POST['non_brand_new_gross_sale'][$key] > 0) ? $_POST['non_brand_new_gross_sale'][$key] : 0;
                        }
                        $this->credit_model->saveFranchiseSale($result['note_id'], $date, $invoice_sale, $note_sale, $non_brand_invoice_sale, $non_brand_note_sale, $this->user_id);
                    }
                    $start_date = min($dates);
                    $end_date = max($dates);
                    $bill_period = Helpers::htmlDate($start_date) . ' to ' . Helpers::htmlDate($end_date);
                    $this->credit_model->saveFranchiseSummary($result['note_id'], $request, $bill_period, $this->user_id);
                    $this->expense_model->updateExpense('credit_note_type', $request->credit_note_type, $result['note_id'], 'credit_debit_note');
                }
            }

            if ($array['type'] == 1) {
                $type = 'credit';
                $this->sendCreditNoteMail(Encrypt::encode($result['note_id']));
            } else {
                $type = 'debit';
            }
            #upload expense file
            if (!empty($_FILES['file']['size'] > 0)) {
                $file = Helpers::uploadFile($request->file('file'), $this->merchant_id, $result['note_id'], 'note');
                $this->expense_model->updateExpense('file_path', $file, $result['note_id'], 'credit_debit_note');
            }

            Session::flash('success', $result['success']);
            return redirect('/merchant/' . $type . 'note/viewlist');
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
    public function setCreditNoteArray()
    {
        $type = $_POST['type'];
        if ($type == 1) {
            $array['vendor_id'] = 0;
            $array['customer_id'] = $_POST['customer_id'];
        } else {
            $array['vendor_id'] = $_POST['vendor_id'];
            $array['customer_id'] = 0;
        }
        $array['type'] = $type;
        $array['invoice_no'] = $_POST['invoice_no'];
        $array['credit_note_no'] = $_POST['credit_note_no'];
        $array['credit_note_date'] = Helpers::sqlDate($_POST['credit_note_date']);
        $array['bill_date'] = Helpers::sqlDate($_POST['bill_date']);
        $array['due_date'] = Helpers::sqlDate($_POST['due_date']);
        $array['base_amount'] = $_POST['sub_total'];
        $array['total_amount'] = $_POST['total'];
        $array['cgst_amount'] = $_POST['cgst_amt'];
        $array['sgst_amount'] = $_POST['sgst_amt'];
        $array['igst_amount'] = $_POST['igst_amt'];
        $array['narrative'] = $_POST['narrative'];
        $tax_array = array('3' => 5, '4' => 12, '5' => 18, '6' => 28);
        #set particular array
        foreach ($_POST['particular'] as $key => $value) {
            $particulars[$key]['particular_name'] = $_POST['particular'][$key];
            $particulars[$key]['tax'] = $_POST['tax'][$key];
            $particulars[$key]['sac'] = $_POST['sac'][$key];
            $particulars[$key]['qty'] = $_POST['unit'][$key];
            $particulars[$key]['rate'] = $_POST['rate'][$key];
            $particulars[$key]['particular_id'] = ($_POST['particular_id'][$key] != 'Na') ? $_POST['particular_id'][$key] : 0;
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
        }
        $array['particular'] = $particulars;
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
        $link = $_POST['id'];
        $array = $this->setCreditNoteArray();
        $array['id'] = Encrypt::decode($link);
        $post_string = json_encode($array);
        if ($array['type'] == 1) {
            $type = 'credit';
        } else {
            $type = 'debit';
        }
        #post form json to Swipez APi 
        $result = Helpers::APIrequest('v1/creditdebitnote/updatesave', $post_string);
        $result = json_decode($result, 1);
        if (!isset($result['status'])) {
            $result['status'] = 0;
            $result['error'] = 'Something went wrong';
        }
        if ($result['status'] == 0) {
            if (is_array($result['error'])) {
                $errors = $result['error'];
            } else {
                $errors[] = $result['error'];
            }
            return $this->update($type, $_POST['id']);
        } else {

            if ($request->credit_note_type == 2) {
                if (!empty($request->sale_date)) {
                    $dates = array();
                    $this->credit_model->deleteFranchiseSale($array['id'],  $this->user_id);
                    foreach ($request->sale_date as $key => $date) {
                        $date = Helpers::sqlDate($date);
                        $dates[] = $date;
                        $invoice_sale = ($_POST['gross_sale'][$key] > 0) ? $_POST['gross_sale'][$key] : 0;
                        $note_sale = ($_POST['new_gross_sale'][$key] > 0) ? $_POST['new_gross_sale'][$key] : 0;
                        $this->credit_model->saveFranchiseSale($array['id'], $date, $invoice_sale, $note_sale, $this->user_id);
                    }
                    $start_date = min($dates);
                    $end_date = max($dates);
                    $bill_period = Helpers::htmlDate($start_date) . ' to ' . Helpers::htmlDate($end_date);
                    $this->credit_model->updateFranchiseSummary($array['id'], $request, $bill_period, $this->user_id);
                }
            }

            #upload expense file
            if (!empty($_FILES['file']['size'] > 0)) {
                $file = Helpers::uploadFile($request->file('file'), $this->merchant_id, $array['id'], 'note');
                $this->expense_model->updateExpense('file_path', $file, $array['id'], 'credit_debit_note');
            }
            if ($array['type'] == 1) {
                $this->sendCreditNoteMail(Encrypt::encode($array['id']));
            }
            Session::flash('success', $result['success']);
            return redirect('/merchant/' . $type . 'note/viewlist');
        }
    }

    private function sendCreditNoteMail($link)
    {
        $data = $this->view('credit', $link, 1);
        $merchant = $data['merchant'];
        $detail = $data['detail'];
        $file_name = $this->download('credit', $link, 1);
        $data['attachment'] = $file_name;
        $data['attachment_name'] = 'Credit note';
        if ($detail->email_id != '') {
            Helpers::sendMail($detail->email_id, $data['type'] . 'note', $data, 'Credit note from ' . $merchant->company_name);
        }
    }

    public function download($type, $link, $save = 0)
    {
        $data = $this->view($type, $link, 1);
        define("DOMPDF_ENABLE_HTML5PARSER", true);
        define("DOMPDF_ENABLE_FONTSUBSETTING", true);
        define("DOMPDF_UNICODE_ENABLED", true);
        define("DOMPDF_DPI", 120);
        define("DOMPDF_ENABLE_REMOTE", true);
        if ($data['detail']->credit_note_type == 2) {
            $type = 'franchise';
        }

        $pdf = DOMPDF::loadView('app/merchant/creditdebit/' . $type . '_note_pdf', $data);
        $pdf->setPaper("a4", "portrait");
        if ($save == 1) {
            $output = $pdf->output();
            $file_name = 'tmp/creditnote-' . time() . '.pdf';
            file_put_contents($file_name, $output);
            return $file_name;
        } else {
            return $pdf->download($type . '_note.pdf');
        }
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
    public function view($type, $link, $sendmail = 0)
    {
        $data = Helpers::setBladeProperties('View ' . $type . ' note', array(), [160, 162]);
        $id = Encrypt::decode($link);
        if ($type == 'credit') {
            $detail = $this->credit_model->getCreditNoteData($id, $this->merchant_id);
            $merchant = $this->expense_model->getTableRow('merchant_detail', 'merchant_id', $detail->merchant_id);
            $data['merchant'] = $merchant;
            $data['auto_generate'] = $this->expense_model->getColumnValue('merchant_setting', 'merchant_id', $this->merchant_id, 'credit_note_auto_generate');
            if ($detail->credit_note_type == 2 || $detail->credit_note_type == 3) {
                $data['summary'] = $this->expense_model->getTableRow('credit_note_food_franchise_summary', 'credit_note_id', $id);
                $data['sales'] = $this->expense_model->getTableList('credit_note_food_franchise_sales', 'credit_note_id', $id);
                $type = ($detail->credit_note_type == 2) ? 'creditfranchise' : 'creditfranchisenonbrand';
            }
        } else {
            $detail = $this->credit_model->getDebitNoteData($id, $this->merchant_id);
        }
        $particulars = $this->credit_model->getTableList('credit_debit_detail', 'credit_debit_id', $id);
        $data['link'] = $link;
        $data['detail'] = $detail;
        $data['type'] = $type;
        $data['particulars'] = $particulars;
        if ($sendmail == 1) {
            return $data;
        }
        return view('app/merchant/creditdebit/view_' . $type, $data);
    }

    public function creditnoteupdate($link = null)
    {
        return $this->update('credit', $link);
    }

    public function debitnoteupdate($link = null)
    {
        return $this->update('debit', $link);
    }

    public function creditviewlist($link = null)
    {
        return $this->viewlist('credit', $link);
    }

    public function debitviewlist($link = null)
    {
        return $this->viewlist('debit', $link);
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
    public function update($type, $link, $errors = null)
    {
        Helpers::hasRole(2, 27);
        Session::put('valid_ajax', 'expense');
        $data = Helpers::setBladeProperties('Update ' . $type . ' note', ['expense', 'template'], [160, 162]);
        $id = Encrypt::decode($link);
        #Getting master data with merchant id
        if ($type == 'credit') {
            $data['customers'] = $this->credit_model->getTableList('customer', 'merchant_id', $this->merchant_id);
            $data['credit_note_auto_generate'] = $this->expense_model->getColumnValue('merchant_setting', 'merchant_id', $this->merchant_id, 'credit_note_auto_generate');
            $detail = $this->credit_model->getCreditNoteData($id, $this->merchant_id);
            $data['script'] .= 'setVendorState(' . $detail->vendor_id . ');';
            $data['auto_generate'] = $this->expense_model->getColumnValue('merchant_setting', 'merchant_id', $this->merchant_id, 'credit_note_auto_generate');
            if ($detail->credit_note_type == 2 || $detail->credit_note_type == 3) {
                $data['summary'] = $this->expense_model->getTableRow('credit_note_food_franchise_summary', 'credit_note_id', $id);
                $data['sales'] = $this->expense_model->getTableList('credit_note_food_franchise_sales', 'credit_note_id', $id);
                $type = 'creditfranchise';
            }
        } else {
            $data['vendors'] = $this->credit_model->getTableList('vendor', 'merchant_id', $this->merchant_id);
            $data['debit_note_auto_generate'] = $this->expense_model->getColumnValue('merchant_setting', 'merchant_id', $this->merchant_id, 'debit_note_auto_generate');
            $detail = $this->credit_model->getDebitNoteData($id, $this->merchant_id);
            $data['script'] .= 'setVendorState(' . $detail->vendor_id . ');';
            $data['auto_generate'] = $this->expense_model->getColumnValue('merchant_setting', 'merchant_id', $this->merchant_id, 'debit_note_auto_generate');
        }

        $particulars = $this->credit_model->getTableList('credit_debit_detail', 'credit_debit_id', $id);
        $data['link'] = $link;
        $data['detail'] = $detail;
        $data['particulars'] = $particulars;
        $data['errors'] = $errors;

        return view('app/merchant/creditdebit/update', $data);
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

    public function viewlist($note_type, $errors = null)
    {
        Helpers::hasRole(1, 27);
        if ($note_type == 'credit') {
            $name = 'credit note';
            $menu = [5,160,165];
            $type = 2;
        } else {
            $name = 'debit note';
            $menu = [143,176,162];
            $type = 1;
        }
        $dates = Helpers::setListDates();
        Session::put('valid_ajax', 'expense');
        $data = Helpers::setBladeProperties('List ' . $name, ['expense'], $menu);


        $data['bulk'] = 0;
        if ($note_type == 'credit') {
            $list = $this->credit_model->getCreditNoteList($this->merchant_id, $dates['from_date'], $dates['to_date']);
        } else {
            $list = $this->credit_model->getDebitNoteList($this->merchant_id, $dates['from_date'], $dates['to_date']);
        }


        #Add encrypted link for update or delete expense
        foreach ($list as $key => $row) {
            $list[$key]->encrypted_id = Encrypt::encode($row->id);
        }
        $data['datatablejs'] = 'table-no-export';
        $data['list'] = $list;
        $data['errors'] = $errors;
        return view('app/merchant/creditdebit/' . $note_type . '_viewlist', $data);
    }

    function tallyfiledownload($link)
    {
        $id = Encrypt::decode($link);
        $file_name = $this->expense_model->getColumnValue('tally_export_request', 'id', $id, 'system_filename');
        if ($file_name != '') {
            return response()->download(storage_path('app/download/tallyexport/' . $file_name));
        } else {
            return redirect('/error/oops');
        }
    }
}
