<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\Expense;
use Log;
use Exception;

class Expensesave extends Command {

    private $user_id = 0;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'expense:save';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Save bulk expense';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        try {
            $expense_model = new Expense();
            Log::info('Bulk expense save cron initiated');
            $list = $expense_model->getPendingBulkExpense();
            foreach ($list as $item) {
                Log::info('Expense saving bulk id :' . $item->bulk_upload_id);
                $expense_list = $expense_model->getStagingExpenseList($item->bulk_upload_id);
                foreach ($expense_list as $exp) {
                    $expense_no = $this->getExpenseNumber($exp->expense_no, $exp->merchant_id);
                    $reqs = $expense_model->bulkexpensesave($exp->expense_id,$expense_no);
                    //dd($reqs);
                }
                Log::info('Bulk Expenses saved bulk id :' . $item->bulk_upload_id);
                $expense_model->updateBulkUploadStatus($item->bulk_upload_id, 5);
            }
        } catch (Exception $e) {
            Log::error('EB009 Error Bulk expense save Error: ' . $e->getMessage());
        }
    }

    public function getExpenseNumber($expense_no, $merchant_id) {
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
