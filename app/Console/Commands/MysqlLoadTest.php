<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\MigrateModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

class MysqlLoadTest extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mysql:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mysql load test';

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
     * @return mixed
     */
    public function handle()
    {
        echo 'Start: ' . date('h:i:s') . PHP_EOL;
        $ports = array('3308','3309','3305');
        $ports_name = array('3308' => 'Mysql 5.7', '3305' => 'Mysql 5.6', '3306' => 'Mysql 5.5', '3307' => 'Mysql 8','3309' => 'Maria');
        $hosts = array('rds-56-perf-test.corscyqxrdcb.ap-south-1.rds.amazonaws.com','rds-57-perf-test.corscyqxrdcb.ap-south-1.rds.amazonaws.com','rds-8-perf-test.corscyqxrdcb.ap-south-1.rds.amazonaws.com','127.0.0.1');
        $host_name = array('rds-56-perf-test.corscyqxrdcb.ap-south-1.rds.amazonaws.com' => 'RDS 5.6', 'rds-57-perf-test.corscyqxrdcb.ap-south-1.rds.amazonaws.com' => 'RDS 5.7', 'rds-8-perf-test.corscyqxrdcb.ap-south-1.rds.amazonaws.com' => 'RDS 8', '127.0.0.1' => 'Linux 5.6');
        foreach ($ports as $port) {
            
            Config::set("database.connections.mysql.port", $port);
            if ($port == '3305') {
                Config::set("database.connections.mysql.password", '');
            }else
            {
                Config::set("database.connections.mysql.password", 'swipezapp');
            }
             $total = $this->insertQuery();
            $data[] = array('PORT' => $ports_name[$port], 'Task' => 'Single table Isert', 'Time' => $total);
            echo '1' . PHP_EOL;
            $total = $this->updateQuery();
             $data[] = array('PORT' => $ports_name[$port], 'Task' => 'Single table Update', 'Time' => $total);
            echo '2' . PHP_EOL;
             $total = $this->selectQuery();
             $data[] = array('PORT' => $ports_name[$port], 'Task' => 'Single table Select', 'Time' => $total);
            echo '3' . PHP_EOL;
              $total = $this->getJoinTables();
             $data[] = array('PORT' => $ports_name[$port], 'Task' => 'Select statement with Join', 'Time' => $total);
            echo '4' . PHP_EOL;
             $total = $this->saveMerchant();
             $data[] = array('PORT' => $ports_name[$port], 'Task' => 'Storeprocedure Insert', 'Time' => $total);
            echo '5' . PHP_EOL;
            $total = $this->updateInvoice();
            $data[] = array('PORT' => $ports_name[$port], 'Task' => 'Storeprocedure Update', 'Time' => $total);
            echo '6' . PHP_EOL;
            $total = $this->getInvoiceDetails();
            $data[] = array('PORT' => $ports_name[$port], 'Task' => 'Storeprocedure Select', 'Time' => $total);
            echo '7' . PHP_EOL;
            DB::disconnect('mysql');
        }
        $this->createFile($data);
        echo 'Start: ' . date('h:i:s');
    }

    public function saveMerchant()
    {
        $start = time();
        for ($i = 1; $i < 500; $i++) {
            $email = 'testload' . $i . '@swipez.in';
            $f_name = '2Test';
            $l_name = 'Last';
            $mobile = '9999999999';
            $company_name = 'Test company' . $i;
            DB::select("call `merchant_register`('" . $email . "','" . $f_name . "','" . $l_name . "','+91','" . $mobile . "','','" . $company_name . "',2,32,0,2)");
        }
        $end = time();
        $total = $end - $start;
        return  $total;
    }

    public function insertQuery()
    {
        $start = time();
        for ($i = 1; $i < 2000; $i++) {
            DB::select("INSERT INTO `swipez`.`customer` (`merchant_id`, `user_id`, `customer_code`, `first_name`, `last_name`, `email`, `mobile`, `address`, `address2`, `city`, `state`, `zipcode`, `customer_group`, `is_active`, `customer_status`, `payment_status`, `email_comm_status`, `sms_comm_status`, `password`, `gst_number`, `balance`, `bulk_id`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES ( 'M000000001', 'U000000003', '23344', 'Paresh', 'Patil', 'pareshhpatil@gmail.com', '9730946150', 'The Metropole, 25 NIBM Road, 5th Floor Office No.03, Kondhwa PUNE-411048 (MS)', '', 'Mumbai', 'Maharashtra', '411111', '[\"{0}\",\"{2}\",\"{10}\",\"{12}\",\"{41}\"]', '1', '2', '2', '1', '1', '', '', '9803.42', '0', 'U000000001', '2016-09-27 23:21:37', 'U000000001', '2021-07-13 15:22:07');");
        }
        $end = time();
        $total = $end - $start;
        return  $total;
    }
    public function updateQuery()
    {
        $start = time();
        for ($i = 1; $i < 2000; $i++) {
            DB::select("UPDATE `swipez`.`customer` SET `customer_code` = 'cust-12', `first_name` = 'Customer2', `last_name` = 'Name2', `email` = 'testload499@swipez.in2', `mobile` = '99999999992', `address` = '2', `address2` = '2', `city` = '2', `state` = '2', `zipcode` = '2', `is_active` = '0', `customer_status` = '1', `payment_status` = '1', `password` = '1', `balance` = '1', `last_update_by` = 'a' WHERE (`customer_id` = '227775');");
        }
        $end = time();
        $total = $end - $start;
        return  $total;
    }

    public function getInvoiceDetails()
    {
        $start = time();
        for ($i = 1; $i < 5; $i++) {
            DB::select("call report_invoice_details('M000000512','2021-07-02','2021-08-02',1,'',0,null,'bill_date','',0,0,0,'','ORDER BY `invoice_id` DESC','')");
        }
        $end = time();
        $total = $end - $start;
        return  $total;
    }
    public function getJoinTables()
    {
        $start = time();
        for ($i = 1; $i < 3; $i++) {
            DB::select("SELECT p.payment_request_id,absolute_cost,bill_date,due_date,first_name,email,mobile,last_name FROM swipez.payment_request p inner join customer c on c.customer_id=p.customer_id where p.merchant_id='M000000512'");
        }
        $end = time();
        $total = $end - $start;
        return  $total;
    }
    public function selectQuery()
    {
        $start = time();
        for ($i = 1; $i < 5; $i++) {
            DB::select("SELECT * FROM swipez.payment_request where merchant_id='M000000512'");
        }
        $end = time();
        $total = $end - $start;
        return  $total;
    }
    public function updateInvoice()
    {
        $start = time();
        for ($i = 1; $i < 500; $i++) {
            $res = DB::select("call `update_invoicevalues`('R000729241','U000000001','2','','','12185625','2021-06-28','2021-06-28','Jun-2021 Bill','','1500.00','0','0','0','0','0','0','1','0','0',null,'U000000001','0','','0','0');");
        }
        $end = time();
        $total = $end - $start;
        return  $total;
    }

    public function createFile($data)
    {
        $path = storage_path('app/public/');

        $fileName = 'Mysql-report.csv';

        $file = fopen($path . $fileName, 'w');

        $columns = array('PORT', 'Task', 'Time');

        fputcsv($file, $columns);
        foreach ($data as $d) {
            fputcsv($file, $d);
        }

        fclose($file);
    }
}
