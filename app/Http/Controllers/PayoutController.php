<?php

namespace App\Http\Controllers;

use App\Libraries\Helpers;
use App\Model\Payout;
use App\Libraries\Encrypt;
use Illuminate\Support\Facades\Session;
use Exception;
use Log;
use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Style_Fill;

class PayoutController extends Controller
{

    private $payout_model = null;
    private $merchant_id = null;
    private $user_id = null;

    public function __construct()
    {
        $this->payout_model = new Payout();
        $this->merchant_id = Encrypt::decode(Session::get('merchant_id'));
        $this->user_id = Encrypt::decode(Session::get('userid'));
    }

    public function beneficiarycreate($errors = null)
    {

        Helpers::hasRole(2, 25);
        Session::put('valid_ajax', 'beneficiary');
        $data = Helpers::setBladeProperties('Create beneficiary', ['payout', 'template'], [2, 127, 128]);
        $data['merchant_setting'] = $this->payout_model->getTableRow('merchant_setting', 'merchant_id', $this->merchant_id);
        $data['column'] = $this->payout_model->getTableList('customer_column_metadata', 'merchant_id', $this->merchant_id);
        $data['validerrors'] = $errors;
        return view('app/merchant/payout/create', $data);
    }

    public function beneficiarysave()
    {

        Helpers::hasRole(2, 25);
        $post_string = json_encode($_POST);
        $result = Helpers::APIrequest('v1/beneficiary/save', $post_string);
        $array = json_decode($result, 1);
        if ($array['status'] == 0) {
            if (is_array($array['error'])) {
                $errors = $array['error'];
            } else {
                $errors[] = $array['error'];
            }
            return $this->beneficiarycreate($errors);
        } else {
            Session::flash('success', $array['success']);
            return redirect('/merchant/beneficiary/viewlist');
        }
    }

    public function beneficiarylist()
    {

        Helpers::hasRole(1, 25);
        $data = Helpers::setBladeProperties('List beneficiary', [], [2, 127, 129]);
        $data['datatablejs'] = 'table-no-export';
        $list = $this->payout_model->getBeneficiaryList($this->merchant_id);
        foreach ($list as $key => $row) {
            $list[$key]->encrypted_id = Encrypt::encode($row->beneficiary_id);
        }
        $data['list'] = $list;
        return view('app/merchant/payout/beneficiarylist', $data);
    }

    public function transfer($errors = null)
    {

        Helpers::hasRole(2, 25);
        $data = Helpers::setBladeProperties('Payout', [], [131, 132]);
        $data['beneficiarylist'] = $this->payout_model->getBeneficiaryList($this->merchant_id);
        $data['validerrors'] = $errors;
        return view('app/merchant/payout/transfer', $data);
    }

    public function transfersave()
    {
        Helpers::hasRole(2, 25);
        $post_string = json_encode($_POST);
        $result = Helpers::APIrequest('v1/beneficiary/transfer', $post_string);
        $array = json_decode($result, 1);
        if ($array['status'] == 2) {
            if (!isset($array['error'])) {
                $array['error'] = (isset($array['message'])) ? $array['message'] : 'Something went wrong. Please try again';
            }
            if (is_array($array['error'])) {
                $errors = $array['error'];
            } else {
                $errors[] = $array['error'];
            }
            return $this->transfer($errors);
        } else {
            Session::flash('success', $array['message']);
            return redirect('/merchant/payout/transfer');
        }
    }

    public function deleteBeneficiary($link)
    {

        Helpers::hasRole(3, 25);
        $id = Encrypt::decode($link);
        $this->payout_model->deleteBeneficiary($id, $this->merchant_id, $this->user_id);
        Session::flash('success', 'Beneficiary has been deleted.');
        return redirect('/merchant/beneficiary/viewlist');
    }

    public function transactions()
    {

        Helpers::hasRole(1, 25);
        $dates = Helpers::setListDates();
        $data = Helpers::setBladeProperties('Payout transactions', [], [131, 133]);
        $data['datatablejs'] = 'table-no-export';
        $data['list'] = $this->payout_model->getTransactionList($this->merchant_id, $dates['from_date'], $dates['to_date']);
        if (isset($_POST['export'])) {
            $this->Export($data['list']);
        }
        return view('app/merchant/payout/transferlist', $data);
    }

    public function Export($data)
    {
        $column_name[] = 'Transfer ID';
        $column_name[] = 'Reference id';
        $column_name[] = 'Amount';
        $column_name[] = 'Narrative';
        $column_name[] = 'Status';
        $column_name[] = 'PG ref';
        $column_name[] = 'UTR';
        $column_name[] = 'Name';
        $column_name[] = 'Beneficiary code';
        $column_name[] = 'Bank account no';
        $column_name[] = 'IFSC code';
        $column_name[] = 'UPI';
        $column_name[] = 'Date';

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("swipez")
            ->setLastModifiedBy("swipez")
            ->setTitle("Payout")
            ->setDescription("Payout transactions");
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
        foreach ($data as $key => $row) {
            $int = 0;
            $key = $key + 2;
            $objPHPExcel->getActiveSheet()->setCellValue('A' . $key, $row->transfer_id);
            $objPHPExcel->getActiveSheet()->setCellValue('B' . $key, $row->reference_id);
            $objPHPExcel->getActiveSheet()->setCellValue('C' . $key, $row->amount);
            $objPHPExcel->getActiveSheet()->setCellValue('D' . $key, $row->narrative);
            $objPHPExcel->getActiveSheet()->setCellValue('E' . $key, $row->status);
            $objPHPExcel->getActiveSheet()->setCellValue('F' . $key, $row->cashfree_transfer_id);
            $objPHPExcel->getActiveSheet()->setCellValue('G' . $key, $row->utr_number);
            $objPHPExcel->getActiveSheet()->setCellValue('H' . $key, $row->name);
            $objPHPExcel->getActiveSheet()->setCellValue('I' . $key, $row->beneficiary_code);
            $objPHPExcel->getActiveSheet()->setCellValue('J' . $key, $row->bank_account_no);
            $objPHPExcel->getActiveSheet()->setCellValue('K' . $key, $row->ifsc_code);
            $objPHPExcel->getActiveSheet()->setCellValue('L' . $key, $row->upi);
            $objPHPExcel->getActiveSheet()->setCellValue('M' . $key, $row->created_date);
        }
        $objPHPExcel->getDefaultStyle()->getFont()->setName('verdana')
            ->setSize(10);
        $objPHPExcel->getActiveSheet()->setTitle('Payout');
        $objPHPExcel->getActiveSheet()->getStyle('A1:M1')->applyFromArray(
            array(
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => 'AAAADD')
                )
            )
        );
        $int++;
        $autosize = 0;
        while ($autosize < $int) {
            $objPHPExcel->getActiveSheet()->getColumnDimension(substr($column[$autosize], 0, -1))->setAutoSize(true);
            $autosize++;
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="PayoutTransactions.xlsx"');
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

    public function nodal()
    {

        Helpers::hasRole(1, 25);
        $data = Helpers::setBladeProperties('Nodal account', [], [131, 135]);
        $result = Helpers::APIrequest('v1/payout/balance', '', "GET");
        $array = json_decode($result, 1);
        $data['balance'] = 0;
        if ($array['status'] == 1) {
            $data['balance'] = number_format($array['balance'], 2);
        }
        $data['list'] = $this->payout_model->getWithdrawalList($this->merchant_id);
        $account = $this->payout_model->getMerchantData($this->merchant_id, 'PAYOUT_NODAL_ACCOUNT');
        $data['nodal_account'] = array();
        if ($account != false) {
            $data['nodal_account'] = json_decode($account);
        }
        $data['datatablejs'] = 'table-no-export';
        return view('app/merchant/payout/nodal', $data);
    }

    public function withdraw()
    {

        Helpers::hasRole(2, 25);
        $data = Helpers::setBladeProperties('Nodal', [], [131, 135]);
        $post_string = json_encode($_POST);
        $result = Helpers::APIrequest('v1/payout/withdraw', $post_string);
        $array = json_decode($result, 1);
        if ($array['status'] == 1) {
            Session::flash('success', $array['message']);
        } else {
            Session::flash('error', $array['error']);
        }
        return redirect('/merchant/payout/nodal');
    }

    public function cashgramlist()
    {

        Helpers::hasRole(1, 25);
        $dates = Helpers::setListDates();
        $data = Helpers::setBladeProperties('Cashgram list', [], [131, 142]);
        $data['datatablejs'] = 'table-no-export';
        $api_url = 'v1/cashgram/list';
        $franchise_post = '';
        if (Session::get('group_type') == 2) {
            if (Session::has('sub_franchise_id')) {
                $franchise_post = '&franchise_id=' . Session::get('sub_franchise_id');
            }
        }
        $result = Helpers::APIrequest($api_url, 'from_date=' . $dates['from_date'] . '&to_date=' . $dates['to_date'] . $franchise_post, "POST");
        $list = array();
        $result = json_decode($result);
        if (!empty($result->data)) {
            $list = $result->data;
            foreach ($list as $key => $row) {
                $list[$key]->encrypted_id = Encrypt::encode($row->cashgram_id);
            }
        }
        $data['list'] = $list;
        return view('app/merchant/payout/cashgramlist', $data);
    }

    public function createcashgram($errors = null)
    {

        Helpers::hasRole(2, 25);
        $data = Helpers::setBladeProperties('Create cashgram', ['template'], [131, 142]);
        $result = Helpers::APIrequest('v1/payout/balance', '', "GET");
        $array = json_decode($result, 1);
        $data['balance'] = 0;
        if ($array['status'] == 1) {
            $data['balance'] = number_format($array['balance'], 2);
        }
        $data['validerrors'] = $errors;
        return view('app/merchant/payout/createcashgram', $data);
    }

    public function deletecashgram($link)
    {

        Helpers::hasRole(3, 25);
        $id = Encrypt::decode($link);
        $result = Helpers::APIrequest('v1/cashgram/delete/' . $id, '', 'GET');
        $array = json_decode($result, 1);
        if ($array['status'] == 0) {
            if (is_array($array['error'])) {
                $errors = $array['error'];
            } else {
                $errors[] = $array['error'];
            }
            return redirect('/merchant/cashgram/list');
        } else {
            Session::flash('success', $array['message']);
            return redirect('/merchant/cashgram/list');
        }
    }

    public function cashgramsave()
    {
        Helpers::hasRole(2, 25);
        $_POST['notify_customer'] = $_POST['notify_patron'];
        $post_string = json_encode($_POST);
        $result = Helpers::APIrequest('v1/cashgram/save', $post_string);
        $array = json_decode($result, 1);
        if ($array['status'] == 0) {
            if (is_array($array['error'])) {
                $errors = $array['error'];
            } else {
                $errors[] = $array['error'];
            }
            return $this->createcashgram($errors);
        } else {
            if ($_POST['notify_patron'] == 1) {
                Helpers::sendSMS(Session::get('company_name') . ' has initiated cash back of Rs. ' . $_POST['amount'] . '/-. To receive this fund please click' . $array['cashgram_link'], $_POST['mobile']);
                $data['customer_name'] = $_POST['name'];
                $data['merchant_name'] = Session::get('company_name');
                $data['link'] = $array['cashgram_link'];
                $data['amount'] = $_POST['amount'];
                Helpers::sendMail($_POST['email_id'], 'cashgram', $data, 'You have received a Cash back from ' . Session::get('company_name'));
            }
            Session::flash('success', $array['message']);
            return redirect('/merchant/cashgram/list');
        }
    }

    public function cashfreePayout()
    {
        Log::error('Payout: ' . json_encode($_POST));
        Log::error(json_encode($_GET));
    }

    public function payoneerWebhook()
    {
        Log::error('Webhook: ' . json_encode($_POST));
        Log::error(json_encode($_GET));
    }
}
