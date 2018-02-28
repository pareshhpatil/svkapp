<?php

namespace App\Http\Controllers;

use App\Model\Bulkupload;
use App\Model\Template;
use App\Model\Paymentrequest;
use App\Http\Controllers\PaymentWrapper;
use App\Lib\Encryption;
use Session;
use App\Lib\MailWrapper;
use App\Lib\SMSSender;
use Excel;
use Log;

//$pdfpath = getenv('MPDF_PATH');
///require_once($pdfpath . 'mpdf.php');
//require_once '/opt/app/swipez-util/svn/branches/0.1/src/shorturl/SwipezShortURLWrapper.php';
require_once 'C:/xampp2/htdocs/swipez/swipez-util/svn/branches/0.1/src/shorturl/SwipezShortURLWrapper.php';

use SwipezShortURLWrapper;
use PDF;

//use MPDF;
#Crypt::encryptString('Hello world.')
#Crypt::decryptString($encrypted)

class GenericController extends Controller {

    private $payment_request_model;
    private $template_model;
    private $employee_model;
    private $bulk_model;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->payment_request_model = new Paymentrequest();
        $this->template_model = new Template();
        $this->bulk_model = new Bulkupload();
    }

    public function gettemplateJson($id) {
        try {
            $template_id = Encryption::decode($id);
            if (is_numeric($template_id)) {
                $detail = $this->template_model->getTemplateDetail($template_id);
                if (!empty($detail)) {
                    $metadata = $this->template_model->getTemplateMetadata($template_id);
                    $json['secret_access_key'] = '';
                    $json['template_id'] = $id;
                    $json['user_id'] = '';
                    $json['corporate_id'] = '';
                    $json['insurance_company_id'] = '';
                    $json['policy_id'] = '';
                    $column['bill_date'] = '';
                    $column['due_date'] = '';
                    $column['employee_code'] = '';
                    $column['employee_name'] = '';
                    $column['employee_email_id'] = '';
                    $column['employee_mobile_no'] = '';
                    foreach ($metadata as $item) {
                        if ($item->save_table_name == 'metadata' && $item->column_type != 'DS') {
                            $column['custom_fields'][] = array('column_id' => $item->column_id, 'column_name' => $item->column_name, 'value' => '');
                        }
                    }
                    if ($detail->template_type == 'standard') {
                        $column['policy_details'][] = array('dependant_name' => '', 'relationship' => '', 'gender' => '', 'date_of_birth' => '', 'sum_insured' => '', 'premium' => '', 'gst' => '', 'total_premium' => '');
                    } else {
                        $column['policy_details'][] = array('sum_insured' => '', 'premium' => '', 'gst' => '', 'total_premium' => '');
                    }
                    $column['group_suminsured'] = $detail->group_suminsured;
                    $column['comments'] = '';
                    $json['data'][] = $column;
                    return $json;
                } else {
                    return FALSE;
                }
            } else {
                return FALSE;
            }
        } catch (Exception $e) {
            Log::error('GN001 Error while get template JSON Error: ' . $e->getMessage());
            return $this->setGenericError();
        }
    }

    public function downloadpdf($id) {
        try {
            $req_id = Encryption::decode($id);
            $payment_wraper = new PaymentWrapper();
            $data = $payment_wraper->getRequestData($req_id);
            $data['staging'] = 0;
            $data['link'] = '';
            //$html = '<html><body>';
            //$html .= view('pdf.' . $data['template_type'] . '_invoice', $data)->render();
            //$html.='</body></html>';
            //print_r($html);
            $pdf = PDF::loadView('pdf.' . $data['template_type'] . '_invoice', $data);
            return $pdf->stream('Payment_request_' . date('Y-M-d H:m:s') . '.pdf');
            exit();
        } catch (Exception $e) {
            Log::error('GN002 Error while download PDF Error: ' . $e->getMessage());
            return $this->setGenericError();
        }
    }

    public function exportExcel($column, $name) {
        try {
            Excel::create($name, function($excel) use($column) {
                $excel->sheet('Sheet 1', function($sheet) use($column) {
                    $sheet->fromArray($column);
                    if (!empty($column)) {
                        $sheet->row(1, function($row) {
                            // call cell manipulation methods
                            $row->setBackground('#2874A6');
                            $row->setFontColor('#ffffff');
                        });
                    }
                    $sheet->freezeFirstRow();
                    $sheet->setAutoSize(true);
                });
            })->export('xlsx');
        } catch (Exception $e) {
            Log::error('GN003 Error while Export Excel Error: ' . $e->getMessage());
            return $this->setGenericError();
        }
    }

    public function getinsurancebranch($session_id, $name) {
        try {
            $insurance_id = Session::get($session_id);
            if (strlen($name) > 2 && $insurance_id > 0) {
                $plan = $this->bulk_model->getInsuranceBranch($insurance_id, $name);
                $array = array();
                foreach ($plan as $row) {
                    $array[] = $row->branch_insurers_name;
                }
                echo json_encode($array);
            } else {
                echo '{}';
            }
        } catch (Exception $e) {
            Log::error('GN004 Error while get insurance branch Error: ' . $e->getMessage());
            return $this->setGenericError();
        }
    }

    public function sendRequestNotification($req_id, $revised = '', $reminder = '') {
        try {
            $payment_wraper = new PaymentWrapper();
            $data = $payment_wraper->getRequestData($req_id);
            if ($data['detail']['notify'] == 1) {
                try {
                    $link = Encryption::encode($req_id);
                    $payment_url = env("WEB_LINK") . '/employee/paymentrequest/view/' . $link;
                    if ($data['detail']['employee_email'] != '') {
                        $email_wrap = new MailWrapper();
                        if ($data['detail']['template_id'] == getenv('SPECIAL_TOPUP_TEMPLATE_ID')) {
                            $data['template_type'] = 'special';
                        }
                        $file = $data['template_type'] . '_invoice';
                        $link = Encryption::encode($req_id);
                        $data['link'] = $payment_url;
                        Log::info('Sending payment request email to ' . $data['detail']['employee_email']);
                        $email_wrap->sendmail($data['detail']['employee_email'], $file, $data, $revised . 'Payment request from ' . $data['detail']['corporate_name'] . $reminder);
                    }
                } catch (Exception $e) {
                    Log::error('PR006 Error while send email payment request Error: ' . $e->getMessage());
                }
                try {
                    if (strlen($data['detail']['employee_mobile']) > 9 && $reminder == "") {
                        if ($data['detail']['short_url'] == '') {
                            $short_url = $this->getShortLink($req_id, $payment_url);
                        } else {
                            $short_url = $data['detail']['short_url'];
                        }
                        $sms_sender = new SMSSender();
                        $sms = $sms_sender->fetchMessage('m1');
                        $sms = str_replace('__CORPORATE_NAME__', $data['detail']['corporate_name'], $sms);
                        $sms = str_replace('__AMOUNT__', $data['detail']['gross_premium'], $sms);
                        $sms = str_replace('__LINK__', $short_url, $sms);
                        Log::info('Sending payment request sms to ' . $data['detail']['employee_mobile'] . ' SMS: ' . $sms);
                        $sms_sender->send($sms, $data['detail']['employee_mobile']);
                    }
                } catch (Exception $e) {
                    Log::error('PR006 Error while send sms payment request Error: ' . $e->getMessage());
                }
            }
        } catch (Exception $e) {
            Log::error('PR006 Error while send notification payment request Error: ' . $e->getMessage());
        }
    }

    public function getShortLink($id, $payment_link) {
        $url[] = $payment_link;
        $shortUrlWrap = new SwipezShortURLWrapper();
        $shortUrls = $shortUrlWrap->SaveUrl($url);

        $this->payment_request_model->updateShortUrl($id, $shortUrls[0]);
        return $shortUrls[0];
    }

    public function setsession($id, $value) {
        Session::put($id, $value);
    }

}
