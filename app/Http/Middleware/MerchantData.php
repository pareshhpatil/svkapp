<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Model\ParentModel;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\API\APIController;

class MerchantData
{
    private $apiController = null;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        
        if (Auth::check()) {
            //define('REQ_TIME', date("Y-m-d H:i:s"));
            $user = Auth::user();
            $request->user = $user;
            $request->user_id = $user->user_id;
            
            $model = new ParentModel();
            $merchantdetail = $model->getTableRow('merchant', 'user_id', $request->user_id);
            //$merchantdetail = Merchant::where('user_id', $request->user_id)->first();
            
            if (!empty($merchantdetail)) {
                $request->merchant = $merchantdetail;
                $request->merchant_id = $merchantdetail->merchant_id;

                $method = $request->route()->getActionMethod();
                $this->validateAccess($method, $request);

            } else {
                throw new Exception('Merchant details not found for this user ' . $request->user_id);
            }
        }
        return $next($request);
    }

    private function validateAccess($method, $request) {
        switch($method) {
            case('getProjectDetails'):
                $parameters = $request->route()->parameter('project_id');
                $this->checkAccess('project','id',$parameters,'merchant_id','ER02044', 'ER02043',$request);
                break;
            case('getBillCodesList'): 
                $this->checkAccess('project','id',$request->project_id,'merchant_id', 'ER02044','ER02053',$request);
                break;
            case('getBillCodeDetails'):
                $parameters = $request->route()->parameter('billcode_id');
                $this->checkAccess('csi_code','id',$parameters,'merchant_id','ER02045', 'ER02043',$request);
                break;
            case('createBillCode'):
                $this->checkAccess('project','id',$request->project_id,'merchant_id', 'ER02046','ER02053',$request);
                break;
            case('updateBillCode'):
                $this->checkAccess('csi_code','id',$request->bill_code_id,'merchant_id','ER02047','ER02054', $request);
                break;
            case('deleteBillCode'):
                $this->checkAccess('csi_code','id',$request->bill_code_id,'merchant_id', 'ER02048','ER02054',$request);
                break;
            case('createProject'):
                $this->checkAccess('customer','customer_id',$request->customer_id,'merchant_id', 'ER02049','ER02055',$request);
                break;
            case('deleteProject'):
                $this->checkAccess('project','id',$request->project_id,'merchant_id', 'ER02050','ER02053',$request);
                break;
            case('updateProject'):
                $this->checkAccess('customer','customer_id',$request->customer_id,'merchant_id', 'ER02051','ER02055',$request);
                $this->checkAccess('project','id',$request->project_id,'merchant_id', 'ER02052','ER02053',$request);
                break;
            case('getContractDetails'):
                $parameters = $request->route()->parameter('contract_id');
                $this->checkAccess('contract','contract_id',$parameters,'merchant_id','ER02058', 'ER02059',$request);
                break;
            case('deleteContract'):
                $this->checkAccess('contract','contract_id',$request->contract_id,'merchant_id','ER02060', 'ER02059',$request);
                break;
            case('createContract'):
                break;
            case('updateContract'):
                break;
            case('getchangeOrderList'):
                if($request->contract_id!='') {
                    $this->checkAccess('contract','contract_id',$request->contract_id,'merchant_id','ER02064', 'ER02059',$request);
                }
                break;
            case('getInvoiceDetails'):
                $model = new ParentModel();
                $this->apiController = new APIController();
                $parameters = $request->route()->parameter('payment_request_id');
                $getProjectMerchant = $model->getColumnValue('payment_request','payment_request_id', $parameters, 'merchant_id');
                if($getProjectMerchant!=false) {
                    if($getProjectMerchant!=$request->merchant_id) {    
                        echo json_encode($this->apiController->APIResponse('ER02065'));
                        dd();
                    }
                } else {
                    echo json_encode($this->apiController->APIResponse('ER02065'));
                    dd();
                }
                break;
        }
    }

    private function checkAccess($table,$col,$value,$field,$message1,$message2,$request) {
        $model = new ParentModel();
        $this->apiController = new APIController();
        if(is_numeric($value)) {
            $getProjectMerchant = $model->getColumnValue($table, $col, $value, $field);
            
            if($getProjectMerchant!=false) {
                if($getProjectMerchant!=$request->merchant_id) {         
                    echo json_encode($this->apiController->APIResponse($message1));
                    die();
                }
            } else {
                echo json_encode($this->apiController->APIResponse($message2));
                die();
            }
        } else {
            //$this->apiController->APIResponse($message2);
            echo json_encode($this->apiController->APIResponse($message2));
            die();
        }
    }
    
}
