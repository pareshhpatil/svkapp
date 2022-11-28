<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\MigrateModel;
use Log;
use App\Model\Validate;

class ValidateData extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'validate:migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Validate data';

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
        $start = 0;
        $limit = 3000;
        $count = 1;
        $int = 1;
        while ($count > 0) {
            $model = new MigrateModel();
            $list = $model->getPaymentRequestValidate($start, $limit);
            $count = count($list);
            $start = $start + $limit;
            echo 'batch no:' . $int . ' count rows' . $count;
            $int++;
            $v_array = array();
            foreach ($list as $row) {
                echo '.';
                $template_type = $model->getTemplateType($row->template_id);
                if ($template_type != 'simple' && $template_type != 'travel_ticket_booking') {
                    $base_amount = $model->getParticularSum($row->payment_request_id);
                    $tax_amount = $model->getTaxSum($row->payment_request_id);
                    if ($tax_amount) {
                        
                    } else {
                        $tax_amount = 0;
                    }
                    if ($base_amount) {
                        
                    } else {
                        $base_amount = 0;
                    }
                    $message = '';
                    if ($base_amount != $row->basic_amount) {
                        $diff = 0;
                        if ($row->basic_amount > $base_amount) {
                            $diff = $row->basic_amount - $base_amount;
                        } else {
                            $diff = $base_amount - $row->basic_amount;
                        }
                        if ($diff > 1) {
                            $message.='Base amount not matching';
                        }
                    }
                    if ($tax_amount != $row->tax_amount) {
                        $diff = 0;
                        if ($row->tax_amount > $tax_amount) {
                            $diff = $row->tax_amount - $tax_amount;
                        } else {
                            $diff = $tax_amount - $row->tax_amount;
                        }
                        if ($diff > 1) {
                            $message.=' Tax amount not matching ';
                        }
                    }
                    if ($message != '') {
                        $v_array[] = array('merchant_id' => $row->merchant_id, 'payment_request_id' => $row->payment_request_id, 'base_amount' => $row->basic_amount, 'c_base_amount' => $base_amount, 'tax_amount' => $row->tax_amount, 'c_tax_amount' => $tax_amount, 'error' => $message, 'created_date' => $row->created_date, 'last_update_date' => date('Y-m-d H:i:s'));
                    }
                }
            }
            if (!empty($v_array)) {
                Validate::insert($v_array);
            }
        }
    }

}
