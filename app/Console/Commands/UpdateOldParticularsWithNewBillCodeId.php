<?php

namespace App\Console\Commands;

use App\ContractParticular;
use App\CsiCode;
use App\Model\Order;
use App\PaymentRequest;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateOldParticularsWithNewBillCodeId extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:old-particulars-with-new-bill-code-id';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will update old particulars of Contract, Invoice, CO';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        DB::beginTransaction();
        try {


            $contracts = ContractParticular::where('particulars', '!=', '')->get();
            foreach ($contracts as $contract) {

                $bill_codes = CsiCode::where('project_id', $contract->project_id)->get()->pluck('id', 'code');
                $particulars = json_decode($contract->particulars, 1);

                $orders = DB::table('order')->where('contract_id', $contract->contract_id)->get();
                foreach ($orders as $order) {
                    $orderParticulars = json_decode($order->particulars, 1);
                    $newOrderParticulars = [];
                    foreach ($orderParticulars as $orderParticular) {
                        $code = $orderParticular['bill_code'];

                        if (isset($bill_codes[$code]))
                            $orderParticular['bill_code'] = $bill_codes[$code];
                        $newOrderParticulars[] = $orderParticular;
                    }

                    DB::table('order')->where('order_id', $order->order_id)->update(['particulars' => json_encode($newOrderParticulars)]);

                }


                $invoiceParticulars = DB::table('invoice_construction_particular')
                    ->select(DB::raw('invoice_construction_particular.*'))
                    ->join('payment_request', 'payment_request.payment_request_id', '=', 'invoice_construction_particular.payment_request_id')
                    ->where('payment_request.contract_id', $contract->contract_id)->get();

                foreach ($invoiceParticulars as $invoiceParticular) {
                    if (isset($bill_codes[$invoiceParticular->bill_code])) {
                        DB::table('invoice_construction_particular')->where('id', $invoiceParticular->id)->update(['bill_code' => $bill_codes[$invoiceParticular->bill_code]]);
                    }
                }


                $newParticulars = [];
                foreach ($particulars as $particular) {
                    $code = $particular['bill_code'];
                    if (isset($bill_codes[$code]))
                        $particular['bill_code'] = $bill_codes[$code];
                    $newParticulars[] = $particular;
                }
                $contract->update(['particulars' => json_encode($newParticulars)]);

            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            dd($e);
        }
    }
}
