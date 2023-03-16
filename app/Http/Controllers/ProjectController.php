<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Project;
use App\Model\Master;
use App\CsiCode;
use App\Model\InvoiceFormat;
use Validator;
use App\Libraries\Helpers;
use App\User;
use App\Libraries\Encrypt;
use Illuminate\Support\Facades\Session;
use Exception;
use App\Http\Controllers\API\APIController;
class ProjectController extends Controller
{
    private $projectModel = null;
    private $masterModel = null;
    private $invoiceFormatModel = null;
    private $user_id = null;
    private $merchant_id = null;
    private $apiController = null;

    public function __construct()
    {
        $this->projectModel = new Project();
        $this->masterModel = new Master();
        $this->invoiceFormatModel = new InvoiceFormat();
        $this->user_id = Encrypt::decode(Session::get('userid'));
        $this->merchant_id = Encrypt::decode(Session::get('merchant_id'));
        $this->apiController = new APIController();
    }

    //api to get project list
    function getProjectList(Request $request) {
        $validator = Validator::make($request->all(), [
            'start' => 'numeric',
            'limit' => 'numeric',
        ]);
        if ($validator->fails()) {
            return response()->json($this->apiController->APIResponse(0,'',$validator->errors()), 422);
        }
        $start = ($request->start > 0) ? $request->start : 0;
        $limit = ($request->limit > 0) ? $request->limit : 15;

        $projectlists = $this->projectModel->getProjectList($request->merchant_id,$start,$limit);
        $response['lastno'] = count($projectlists) + $start;
        $response['list'] = $projectlists;
        //return $this->apiController->APIResponse('', $response);
        return response()->json($this->apiController->APIResponse('',$response), 200);
    }

    function getProjectDetails($project_id) {
        if($project_id!=null) {
            $projectDetails = $this->projectModel->getTableRow('project', 'id', $project_id,1);
            return response()->json($this->apiController->APIResponse('',$projectDetails), 200);
        }
    }

    function createProject(Request $request) {
        $validator = Validator::make($request->all(), [
            'project_id' => 'required|max:45',
            'project_name' => 'required|max:100',
            'customer_id'=> 'required',
            'start_date' => 'required',
            'end_date' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($this->apiController->APIResponse(0,'',$validator->errors()), 422);
        }
        
        //check project code is already exist or not
        $exists = $this->masterModel->isExistData($request->merchant_id,'project', 'project_id', $request->project_id);
            
        if ($exists) {
            $error['project_id'][] = 'Project id already exists';
            return response()->json($this->apiController->APIResponse(0,'',$error), 422);
        }
        
        $request->start_date = Helpers::sqlDate($request->start_date);
        $request->end_date = Helpers::sqlDate($request->end_date);

        //check suquence no is exist or not
        $prefix = ($request->invoice_sequence_number['prefix'] != '') ? $request->invoice_sequence_number['prefix'] : '';
        $number = ($request->invoice_sequence_number['sequence_number'] != '' ? $request->invoice_sequence_number['sequence_number'] : 0);
        $prefix = str_replace('~', '/', $prefix);
        $separator = isset($request->invoice_sequence_number['separator']) ? $request->invoice_sequence_number['separator'] : '';

        
        if ($prefix == '' && $separator != '') {
            $error['separator'][] = 'You can not add separator without prefix';
            return response()->json($this->apiController->APIResponse(0,'',$error), 422);
        } else if ($number == '') {
            $error['sequence_number'][] = 'Sequence number is required';
            return response()->json($this->apiController->APIResponse(0,'',$error), 422);
        } else {
            $existsSequence = $this->invoiceFormatModel->existInvoicePrefix($request->merchant_id, $prefix, $separator);
            
            if($existsSequence == FALSE) {
                $seq_number = $number - 1;
                $sequence_id = $this->invoiceFormatModel->saveSequence($request->merchant_id, $prefix, $seq_number, $request->user_id, $separator);
                $request->sequence_number = $sequence_id;
            } else {
                $request->sequence_number = $existsSequence->auto_invoice_id;
            }

            $project_id=$this->masterModel->saveNewProject($request, $request->merchant_id, $request->user_id);
            $project =  $this->projectModel->getTableRow('project', 'id', $project_id);
            return response()->json($this->apiController->APIResponse('',$project), 200);
        }

    }

    function updateProject(Request $request){
        $validator = Validator::make($request->all(), [
            //'project_id' => 'required|max:45',
            'project_name' => 'required|max:100',
            'customer_id'=> 'required',
            'start_date' => 'required',
            'end_date' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($this->apiController->APIResponse(0,'',$validator->errors()), 422);
        }
        $request->start_date = Helpers::sqlDate($request->start_date);
        $request->end_date = Helpers::sqlDate($request->end_date);

        //check suquence no is exist or not
        $prefix = ($request->invoice_sequence_number['prefix'] != '') ? $request->invoice_sequence_number['prefix'] : '';
        $number = ($request->invoice_sequence_number['sequence_number'] != '' ? $request->invoice_sequence_number['sequence_number'] : 0);
        $prefix = str_replace('~', '/', $prefix);
        $separator = isset($request->invoice_sequence_number['separator']) ? $request->invoice_sequence_number['separator'] : '';

        if ($prefix == '' && $separator != '') {
            $error['separator'][] = 'You can not add separator without prefix';
            return response()->json($this->apiController->APIResponse(0,'',$error), 422);
        } else if ($number == '') {
            $error['sequence_number'][] = 'Sequence number is required';
            return response()->json($this->apiController->APIResponse(0,'',$error), 422);
        } else {
            $existsSequence = $this->invoiceFormatModel->existInvoicePrefix($request->merchant_id, $prefix, $separator);
            
            if($existsSequence == FALSE) {
                $seq_number = $number - 1;
                $sequence_id = $this->invoiceFormatModel->saveSequence($request->merchant_id, $prefix, $seq_number, $request->user_id, $separator);
                $request->sequence_number = $sequence_id;
            } else {
                $request->sequence_number = $existsSequence->auto_invoice_id;
            }
            $request->id=$request->project_id;
            $this->masterModel->updateProject($request, $request->merchant_id, $request->user_id);
            $project =  $this->projectModel->getTableRow('project', 'id', $request->project_id);
            return response()->json($this->apiController->APIResponse('',$project), 200);
        }
       
    }

    public function deleteProject(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                'project_id' => 'required|numeric'
            ]);
            if ($validator->fails()) {
                return response()->json($this->apiController->APIResponse(0,'',$validator->errors()), 422);
            }
            $this->masterModel->deleteTableRow('project', 'id', $request->project_id, $request->merchant_id, $request->user_id);
            $response['project_id'] = $request->project_id;
            return response()->json($this->apiController->APIResponse('',$response), 200);
        } catch (Exception $e) {
            Log::error('Error while deleting project :' . $e->getMessage());
        }
    }

    function getBillCodesList(Request $request) {
        $validator = Validator::make($request->all(), [
            'start' => 'numeric',
            'limit' => 'numeric',
        ]);
        if ($validator->fails()) {
            return response()->json($this->apiController->APIResponse(0,'',$validator->errors()), 422);
        }
        $start = ($request->start > 0) ? $request->start : 0;
        $limit = ($request->limit > 0) ? $request->limit : 15;

        $billCodeLists = $this->projectModel->getBillCodesList($request->merchant_id,$request->project_id,$start,$limit);
        $response['lastno'] = count($billCodeLists) + $start;
        $response['list'] = $billCodeLists;
        return response()->json($this->apiController->APIResponse('',$response), 200);
    }

    function getBillCodeDetails($billcode_id) {
        if($billcode_id!=null) {
            $billCodeDetails = $this->projectModel->getTableRow('csi_code', 'id', $billcode_id,1);
            return response()->json($this->apiController->APIResponse('',$billCodeDetails), 200);
        }
    }

    function createBillCode(Request $request) {
        $validator = Validator::make($request->all(), [
            'bill_code' => 'required',
            'bill_description' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($this->apiController->APIResponse(0,'',$validator->errors()), 422);
        } else {
            $billCode = CsiCode::create([
                'code' => $request->bill_code,
                'title' => $request->bill_description,
                'description' => $request->bill_description,
                'project_id' => $request->project_id,
                'merchant_id' => $request->merchant_id,
                'created_by' => $request->user_id,
                'last_update_by' => $request->user_id,
                'created_date' => date('Y-m-d H:i:s')
            ]);
            return response()->json($this->apiController->APIResponse('',$billCode), 200);
        }
       
    }

    function updateBillCode(Request $request) {
        $validator = Validator::make($request->all(), [
            'bill_code' => 'required',
            'bill_description' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($this->apiController->APIResponse(0,'',$validator->errors()), 422);
        } else {
            $this->projectModel->updateBillcode($request, $request->user_id);
            $billCode =  $this->projectModel->getTableRow('csi_code', 'id', $request->bill_code_id);
            return response()->json($this->apiController->APIResponse('',$billCode), 200);
        }
    }

    public function deleteBillCode(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                'bill_code_id' => 'required|numeric'
            ]);
            if ($validator->fails()) {
                return response()->json($this->apiController->APIResponse(0,'',$validator->errors()), 422);
            }
            $this->masterModel->deleteTableRow('csi_code', 'id', $request->bill_code_id, $request->merchant_id, $request->user_id);
            $response['bill_code_id'] = $request->bill_code_id;
            return response()->json($this->apiController->APIResponse('',$response), 200);
        } catch (Exception $e) {
            Log::error('Error while deleting bill code :' . $e->getMessage());
        }
    }

    public function createToken() {
        $title = 'API Token';
        $data = Helpers::setBladeProperties($title,  [],  []);
        $user_token = $this->masterModel->getTableRow('personal_access_tokens', 'name', $this->merchant_id,'token');
        $data['show_token'] = 0;
        if(!empty($user_token) && $user_token->plain_text!='') {
            $data['show_token'] = 1;
            $data['plain_text'] = str_replace($user_token->id.'|', '', $user_token->plain_text);
        }
       
        return view('app/merchant/user/create-token', $data);
    }
    
    public function saveToken(Request $request) {
        if (isset($request['submit_password'])) {
            $user_row = $this->masterModel->getTableRow('user', 'user_id', $this->user_id,'password');
            if (password_verify($request['password'], $user_row->password)) {
                $user = User::find($user_row->id);
                $token = $user->createToken($this->merchant_id)->plainTextToken;
                $this->masterModel->updateTable('personal_access_tokens', 'name', $this->merchant_id, 'plain_text', $token);
                return redirect('/merchant/user/create-token')->with('success', "Token has been generated successfully");
            } else {
                return redirect()->back()->withInput()->withErrors([
                    'password' => 'Invalid password'
                ]);
            }
        }
    }
}
