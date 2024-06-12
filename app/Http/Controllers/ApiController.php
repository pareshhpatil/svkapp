<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DashboardController
 *
 * @author Paresh
 */

namespace App\Http\Controllers;

use App\Model\Vehicle;
use App\Model\Bill;
use App\Model\Master;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Log;

class ApiController extends Controller
{

 
    /**
     * 
     * @param Request $request
     * @return type
     */
    public function webhook(Request $request)
    {
		Log::error('Webhook: '.json_encode($request->all()));
		$bill_model = new Bill();
        $master_model = new Master();
		if($request->event=='TRANSFER_SUCCESS')
		{
			//{"event":"TRANSFER_SUCCESS","transferId":"6","referenceId":"1454828560","acknowledged":"1","eventTime":"2024-03-22 09:59:13","utr":"408209572503","signature":"M+OL4mvgYudbbigQGqQPlxIBl8nXgNdROYfctyjRTAc="}  
			$payment_transaction=$master_model->getMasterDetail('payment_transaction', 'id', $request->transferId);	
			$request_id=$payment_transaction->request_id;
			$transaction=$master_model->getMasterDetail('transaction', 'transaction_id', $request_id);
			if($transaction->status==0)
			{
				$master_model->updateTransaction($request->transferId, 'SUCCESS', $request->referenceId, $request->utr, json_encode($request->all()));
				$bill_model->updateTransaction(1, $request_id, $payment_transaction->amount, 'NEFT', 2, $request->eventTime, 100);
				$master_model->updateEmployeeBalance($payment_transaction->amount, $transaction->employee_id);
				$master_model->updateBankBalance($payment_transaction->amount, 2);

			}
			
		}
		
        
    }

	public function facebookWebhook(Request $request)
    {
		
		Log::error('Facebook Webhook: '.json_encode($request->all()));
		die('hii');
	}

   
}
