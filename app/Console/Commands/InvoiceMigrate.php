<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\MigrateModel;
use App\Model\Particular;
use App\Model\Tax;

class InvoiceMigrate extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoice:migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate Invoice';

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
        $limit = 1000;
        $count = 1;
        $int = 1;
        while ($count > 0) {
            $model = new MigrateModel();
            $list = $model->getPaymentRequest($start, $limit);
            $count = count($list);
            $start = $start + $limit;
            //echo 'batch no:' . $int . ' count rows' . $count;
            // $int++;
            $particular_array = array();
            $tax_array = array();
            foreach ($list as $row) {
                $template_type = $row->template_type;
                if ($row->merchant_id != '') {
                    //echo '.';
                    $group_row = $model->getGroupID('PF', $row->payment_request_id);
                    foreach ($group_row as $gr) {
                        $gprows = $model->getGroupData($gr->column_group_id, 'PS', $row->payment_request_id);
                        $data = (object) array();
                        $data->annual_recurring_charges = null;
                        $data->sac_code = null;
                        $data->description = null;
                        $data->narrative = null;
                        $data->payment_request_id = $row->payment_request_id;
                        $data->created_date = $gr->created_date;
                        $data->last_update_date = $gr->created_date;
                        $data->created_by = $row->created_by;
                        $data->last_update_by = $row->created_by;
                        $data->item = $gr->column_name;
                        $data->qty = 0;
                        $data->rate = 0;
                        $data->gst = 0;
                        $data->tax_amount = 0;
                        $data->discount = 0;
                        $data->total_amount = 0;
                        foreach ($gprows as $gprow) {
                            if ($gprow->column_type == 'PF') {
                                $data->item = $gprow->value;
                            }
                            switch ($gprow->column_name) {
                                case 'Unit Price':
                                    if ($template_type == 'isp') {
                                        $data->annual_recurring_charges = $gprow->value;
                                    } else {
                                        $data->rate = $gprow->value;
                                    }
                                    break;
                                case 'No of units':
                                    if ($template_type == 'isp') {
                                        $data->description = $gprow->value;
                                    } else {
                                        $data->qty = $gprow->value;
                                    }
                                    break;
                                case 'Absolute cost':
                                    $data->total_amount = $gprow->value;
                                    break;
                                case 'Narrative':
                                    $data->narrative = $gprow->value;
                                    break;
                                case 'Duration':
                                    $data->description = $gprow->value;
                                    break;
                                case 'Amount':
                                    $data->total_amount = $gprow->value;
                                    break;
                                case 'Quantity':
                                    $data->qty = $gprow->value;
                                    break;
                                case 'Unit cost':
                                    $data->rate = $gprow->value;
                                    break;
                                case 'Annual recurring charges':
                                    $data->annual_recurring_charges = $gprow->value;
                                    break;
                                case 'Time period':
                                    $data->description = $gprow->value;
                                    break;
                            }
                        }
                        if (is_numeric($data->qty)) {
                            
                        } else {
                            $data->qty = 0;
                        }
                        if (is_numeric($data->rate)) {
                            
                        } else {
                            $data->rate = 0;
                        }

                        if (is_numeric($data->total_amount)) {
                            
                        } else {
                            $data->total_amount = $data->rate * $data->qty;
                        }
                        $particular_array[] = (array) $data;
                    }
                    $group_row = $model->getGroupID('TF', $row->payment_request_id);
                    foreach ($group_row as $gr) {
                        $gprows = $model->getGroupData($gr->column_group_id, 'TS', $row->payment_request_id);
                        $data = (object) array();
                        $data->payment_request_id = $row->payment_request_id;
                        $data->created_date = $gr->created_date;
                        $data->last_update_date = $gr->created_date;
                        $tax_name = $gr->column_name;
                        $data->tax_amount = '';
                        $data->applicable = '';
                        $data->narrative = '';
                        $data->tax_percent = 0;
                        $data->created_by = $row->created_by;
                        $data->last_update_by = $row->created_by;
                        $tax_id = 0;
                        foreach ($gprows as $gprow) {
                            if ($gprow->column_type == 'TF') {
                                $tax_name = $gprow->value;
                            }
                            switch ($gprow->column_name) {
                                case 'Percentage':
                                    $data->tax_percent = $gprow->value;
                                    break;
                                case 'Applicable on (RS)':
                                    $data->applicable = $gprow->value;
                                    break;
                                case 'Absolute cost':
                                    $data->tax_amount = $gprow->value;
                                    break;
                                case 'Narrative':
                                    $data->narrative = $gprow->value;
                                    break;
                            }
                        }
                        if (is_numeric($data->applicable)) {
                            
                        } else {
                            if (is_numeric($data->narrative)) {
                                $data->applicable = $data->narrative;
                                $data->narrative = '';
                            }
                        }

                        if (is_numeric($data->applicable)) {
                            
                        } else {
                            $data->applicable = 0;
                        }

                        if (is_numeric($data->tax_amount)) {
                            
                        } else {
                            $data->tax_amount = 0;
                        }

                        if ($data->tax_percent > 0 && $data->tax_amount > 0) {
                            $tax_id = $model->getTaxID($tax_name, $data->tax_percent, $row->merchant_id);
                            $data->tax_id = $tax_id;
                            $tax_array[] = (array) $data;
                            //$model->saveTax($data);
                        }
                    }
                }
            }
            if (count($particular_array) > 4000) {
                $particular_split = array_chunk($particular_array, 4000);
                foreach ($particular_split as $particular_array) {
                    Particular::insert($particular_array);
                }
            } else {
                Particular::insert($particular_array);
            }

            if (count($tax_array) > 4000) {
                $tax_split = array_chunk($tax_array, 4000);
                foreach ($tax_split as $tax_array) {
                    Tax::insert($tax_array);
                }
            } else {
                Tax::insert($tax_array);
            }
        }
    }

}
