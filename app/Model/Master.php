<?php

namespace App\Model;

use Carbon\Carbon;
use App\Model\ParentModel;
use Illuminate\Support\Facades\DB;
use App\Model\CostType;

class Master extends ParentModel
{

    public function saveFoodDeliveryPartner($data, $merchant_id, $user_id)
    {

        $id = DB::table('food_delivery_partner_comission')->insertGetId(
            [
                'merchant_id' => $merchant_id,
                'name' => $data->name,
                'start_date' => $data->start_date,
                'end_date' => $data->end_date,
                'commission' => $data->commission,
                'gst' => $data->gst,
                'created_by' => $user_id,
                'last_update_by' => $user_id,
                'created_date' => date('Y-m-d H:i:s')
            ]
        );
        return $id;
    }

    public function updateFoodDeliveryPartner($data, $user_id)
    {

        DB::table('food_delivery_partner_comission')
            ->where('id', $data->id)
            ->update([
                'name' => $data->name,
                'start_date' => $data->start_date,
                'end_date' => $data->end_date,
                'commission' => $data->commission,
                'gst' => $data->gst,
                'last_update_by' => $user_id
            ]);
    }


    public function saveBilledTransaction($data, $user_id)
    {
        $data['last_update_by'] = $user_id;
        if ($data['id'] > 0) {
            DB::table('billed_transaction')
                ->where('id', $data['id'])
                ->update($data);
        } else {
            unset($data['id']);
            $data['created_by'] = $user_id;
            $data['created_date'] = date('Y-m-d H:i:s');
            DB::table('billed_transaction')->insertGetId(
                $data
            );
        }

    }

    public function getProjectList($merchant_id, $privilegesIDs = [])
    {
        $ids = implode(",", $privilegesIDs);
        if (!empty($ids) && !in_array('all', $privilegesIDs)) {
            $retObj =  DB::select("SELECT a.*, ifnull(b.company_name, concat(b.first_name,' ' ,  b.last_name)) company_name
                            FROM project a
                            join customer b on a.customer_id = b.customer_id
                            WHERE a.id in($ids)
                            and a.merchant_id = '$merchant_id' 
                            and a.is_active ='1'
                            ORDER by 1 DESC");
        } else {
            $retObj =  DB::select("SELECT a.*, ifnull(b.company_name, concat(b.first_name,' ' ,  b.last_name)) company_name
                            FROM project a
                            join customer b on a.customer_id = b.customer_id
                            and a.merchant_id = '$merchant_id' 
                            and a.is_active ='1'
                            ORDER by 1 DESC");
        }

        return $retObj;
    }


    public function getCustomerList($merchant_id, $column_name, $bulk_id, $where = '')
    {
        $retObj = DB::select("call `get_customer_list`('$merchant_id','$column_name','$where','','','$bulk_id')");
        return $retObj;
    }

    public function saveNewProject($data, $merchant_id, $user_id)
    {

        $id = DB::table('project')->insertGetId(
            [
                'merchant_id' => $merchant_id,
                'project_id' => $data->project_id,
                'project_name' => $data->project_name,
                'customer_id' => $data->customer_id,
                'end_date' => $data->end_date,
                'start_date' => $data->start_date,
                'sequence_number' => $data->sequence_number,
                'users' => json_encode($data->users),
                'created_by' => $user_id,
                'last_update_by' => $user_id,
                'created_date' => date('Y-m-d H:i:s')
            ]
        );
        return $id;
    }

    public function updateProject($data, $user_id)
    {
        DB::table('project')
            ->where('id', $data->id)
            ->update([
                'project_name' => $data->project_name,
                'customer_id' => $data->customer_id,
                'end_date' => $data->end_date,
                'start_date' => $data->start_date,
                'sequence_number' => $data->sequence_id,
                'users' => json_encode($data->users),
                'last_update_by' => $user_id
            ]);
    }

    public function updateProjectSequence($merchant_id, $id, $val, $separator=null)
    {
        DB::table('merchant_auto_invoice_number')
            ->where('auto_invoice_id', $id)
            ->update([
                'val' => $val,
                'seprator' => $separator
            ]);
    }


    public function getProjectCodeList($merchant_id, $project_id, $table = 'csi_code')
    {
        $retObj = DB::table($table)
            ->select(DB::raw('*'))
            ->where('is_active', 1)
            ->where('merchant_id', $merchant_id)
            ->where('project_id', $project_id)
            ->get();
        if ($retObj->isEmpty()) {
            return array();
        } else {
            return $retObj;
        }
    }


    public function getProjectBillTransactionList($merchant_id, $project_id)
    {
        $retObj = DB::table('billed_transaction as b')
            ->select(DB::raw('b.*,concat(c.abbrevation," - ",c.name) as cost_type_label'))
            ->join('cost_types as c', 'b.cost_type', '=', 'c.id')
            ->where('b.is_active', 1)
            ->where('b.merchant_id', $merchant_id)
            ->where('b.project_id', $project_id)
            ->get();
        if ($retObj->isEmpty()) {
            return array();
        } else {
            return $retObj;
        }
    }
  

    public function getCostTypes($merchant_id): array
    {
        return CostType::where('merchant_id', $merchant_id)->where('is_active', 1)
                ->select(['id as value', DB::raw('CONCAT(abbrevation, " - ", name) as label') ])
                ->get()->toArray();
    }

    public function getUsersListByMerchant($user_id)
    {       
        $retObj = DB::select("call `get_sub_userlist`('$user_id')");
        return $retObj;
    }

    public function getUserPrivilegesIDsWithType($user_id, $type) {
        $typeIDs = DB::table('briq_privileges')
                        ->where('user_id', $user_id)
                        ->where('type', $type)
                        ->pluck('type_id')->toArray();

        if ($type == 'project') {
            if(empty($typeIDs)) {
                $customerIDs = DB::table('briq_privileges')
                    ->where('user_id', $user_id)
                    ->where('type', 'customer')
                    ->pluck('type_id')->toArray();

                $typeIDs = DB::table('project')
                                ->whereIn('customer_id', $customerIDs)
                                ->pluck('id')
                                ->toArray();
            }
        }

        return $typeIDs;
    }

    public function getUserPrivilegesAccessIDsWithType($user_id) {
        $privilegesCollect = DB::table('briq_privileges')
            ->where('user_id', $user_id)
            ->select(['type', 'type_id', 'access'])
            ->get()
            ->collect();

        $customerPrivilegesCollect = clone $privilegesCollect->where('type', 'customer')
                                            ->pluck('access', 'type_id');

        $customerPrivilegesArray = $customerPrivilegesCollect->toArray();

        $projectPrivilegesCollect = clone $privilegesCollect->where('type', 'project')
                                                ->pluck('access', 'type_id');
        
        $projectPrivilegesArray = $projectPrivilegesCollect->toArray();


        $contractPrivilegesCollect = clone $privilegesCollect->where('type', 'contract')
                                                            ->pluck('access', 'type_id');

        $contractPrivilegesArray = $contractPrivilegesCollect->toArray();

        $invoicePrivilegesCollect = clone $privilegesCollect->where('type', 'invoice')
                                                        ->pluck('access', 'type_id');

        $invoicePrivilegesArray = $invoicePrivilegesCollect->toArray();

        $orderPrivilegesCollect = clone $privilegesCollect->where('type', 'change-order')
            ->pluck('access', 'type_id');

        $orderPrivilegesArray = $orderPrivilegesCollect->toArray();

        if(empty($contractPrivilegesArray)) {
            if (!empty($projectPrivilegesArray)) {
                $contractPrivilegesArray = $this->emptyContractIDs($projectPrivilegesArray, 'project');
            } else {
                $contractPrivilegesArray = $this->emptyContractIDs($customerPrivilegesArray, 'customer');
            }

        }

        if(empty($orderPrivilegesArray)) {
            $orderPrivilegesArray = $this->emptyOrderIDs($contractPrivilegesArray);
        }


        if(empty($invoicePrivilegesArray)) {
            $invoicePrivilegesArray = $this->emptyInvoiceIDs($contractPrivilegesArray);
        }

        if(empty($projectPrivilegesArray)) {
            $projectPrivilegesArray = $this->emptyProjectIDs($customerPrivilegesArray);
        }

        return [
            'customer_privileges' => $customerPrivilegesArray,
            'project_privileges' => $projectPrivilegesArray,
            'contract_privileges' => $contractPrivilegesArray,
            'invoice_privileges' => $invoicePrivilegesArray,
            'change_order_privileges' => $orderPrivilegesArray
        ];
    }

    public function emptyProjectIDs($customerPrivilegesArray) {
        $projectIDs = DB::table('project')
                                ->where('is_active', 1)
                                ->whereIn('customer_id', array_keys($customerPrivilegesArray))
                                ->select(['id', 'customer_id'])
                                ->get()
                                ->toArray();

        $tempArr= [];
        foreach ($projectIDs as $projectID) {
            $tempArr[$projectID->id] = $customerPrivilegesArray[$projectID->customer_id];
        }

        return $tempArr;
    }

    public function emptyContractIDs($privilegesArray, $type) {
        $tempArr= [];
        if ($type == 'project') {
            $contractIDs = DB::table('contract')
                ->where('is_active', 1)
                ->whereIn('project_id', array_keys($privilegesArray))
                ->select(['contract_id', 'project_id'])
                ->get()
                ->toArray();

            foreach ($contractIDs as $contractID) {
                $tempArr[$contractID->contract_id] = $privilegesArray[$contractID->project_id];
            }
        }

        if($type == 'customer') {
            $contractIDs = DB::table('contract')
                ->where('is_active', 1)
                ->whereIn('customer_id', array_keys($privilegesArray))
                ->select(['contract_id', 'customer_id'])
                ->get()
                ->toArray();

            foreach ($contractIDs as $contractID) {
                $tempArr[$contractID->contract_id] = $privilegesArray[$contractID->customer_id];
            }
        }

        return $tempArr;
    }

    public function emptyOrderIDs($contractPrivilegesArray) {
        $orderIDs = DB::table('order')
            ->where('is_active', 1)
            ->whereIn('contract_id', array_keys($contractPrivilegesArray))
            ->select(['order_id', 'contract_id'])
            ->get()
            ->toArray();

        $tempArr= [];
        foreach ($orderIDs as $orderID) {
            $tempArr[$orderID->order_id] = $contractPrivilegesArray[$orderID->contract_id];
        }

        return $tempArr;
    }

    public function emptyInvoiceIDs($contractPrivilegesArray) {
        $invoiceIDs = DB::table('invoice')
            ->where('is_active', 1)
            ->whereIn('contract_id', array_keys($contractPrivilegesArray))
            ->select(['payment_request_id', 'contract_id'])
            ->get()
            ->toArray();

        $tempArr= [];
        foreach ($invoiceIDs as $invoiceID) {
            $tempArr[$invoiceID->payment_request_id] = $contractPrivilegesArray[$invoiceID->contract_id];
        }

        return $tempArr;
    }


}
