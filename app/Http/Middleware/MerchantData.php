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
            define('REQ_TIME', date("Y-m-d H:i:s"));
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
                return $this->checkAccess('project','id',$parameters,'merchant_id','ER02044', 'ER02043',$request);
                break;
            case('getBillCodesList'): 
                return $this->checkAccess('project','id',$request->project_id,'merchant_id', 'ER02044','ER02053',$request);
                break;
            case('getBillCodeDetails'):
                $parameters = $request->route()->parameter('billcode_id');
                return $this->checkAccess('csi_code','id',$parameters,'merchant_id','ER02045', 'ER02043',$request);
                break;
            case('createBillCode'):
                return $this->checkAccess('project','id',$request->project_id,'merchant_id', 'ER02046','ER02053',$request);
                break;
            case('updateBillCode'):
                return $this->checkAccess('csi_code','id',$request->bill_code_id,'merchant_id','ER02047','ER02054', $request);
                break;
            case('deleteBillCode'):
                return $this->checkAccess('csi_code','id',$request->bill_code_id,'merchant_id', 'ER02048','ER02054',$request);
                break;
            case('createProject'):
                return $this->checkAccess('customer','customer_id',$request->customer_id,'merchant_id', 'ER02049','ER02055',$request);
                break;
            case('deleteProject'):
                return $this->checkAccess('project','id',$request->project_id,'merchant_id', 'ER02050','ER02053',$request);
                break;
            case('updateProject'):
                //return $this->checkAccess('customer','customer_id',$request->customer_id,'merchant_id', 'ER02051','ER02055',$request);
                return $this->checkAccess('project','id',$request->project_id,'merchant_id', 'ER02052','ER02053',$request);
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
                    return $this->apiController->APIResponse($message1);
                }
            } else {
                return $this->apiController->APIResponse($message2);
            }
        } else {
            return $this->apiController->APIResponse($message2);
            //echo json_encode(['error' => 'Invalid '. $message2], 401);
        }
    }
    
}
