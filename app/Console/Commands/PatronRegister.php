<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\MigrateModel;
use Excel;
use Illuminate\Support\Facades\Storage;
use App\Libraries\MailWrapper;

class PatronRegister extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'patron:register {file_name} {merchant_id} {email_template}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Patron Register';

    public $rows = array();
    public $columns = array();

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
        $model = new MigrateModel();
        $mail = new MailWrapper();

        $company_name = null;
        $from_email = null;
        $file_name = $this->argument('file_name');
        $merchant_id = $this->argument('merchant_id');
        $email_template = $this->argument('email_template');

        if ($email_template != 'patron-welcome' && $email_template != 'patron-credentials') {
            print "Error: Invalid email template. Choose between patron-welcome,patron-credentials";
            return;
        }
        if (strlen($merchant_id) == 10) {
            $merchant = $model->getTableRow('merchant', 'merchant_id', $merchant_id);
            if ($merchant == null) {
                print "Error: Merchant id does not exist";
                return;
            }
            $merchant_setting = $model->getTableRow('merchant_setting', 'merchant_id', $merchant_id);
            $company_name = $merchant->company_name;
            $from_email = ($merchant_setting->from_email != '') ? $merchant_setting->from_email : null;
        }
        $subject = 'Your bills from ' . $company_name . ' will be available online';
        if (Storage::disk('local')->exists($file_name)) {
            $inputFile = Storage::disk('local')->path($file_name);
            $csvfile = fopen($inputFile, "r");
            $int = 1;
            $success_count = 0;
            echo 'Opening File: ' . $file_name . PHP_EOL;
            while (!feof($csvfile)) {
                $row_ = fgetcsv($csvfile);
                if ($int > 1) {
                    $merchant_id = trim($row_[0]);
                    $fname = trim($row_[1]);
                    $lname = trim($row_[2]);
                    $email = trim($row_[3]);
                    $mobile = trim($row_[4]);
                    if ($fname == '' || $email == '') {
                        print $int . " Row Customer name or email is empty" . PHP_EOL;
                    } else {
                        $retObj = $model->getTableRow('user', 'email_id', $email);
                        if ($retObj == null) {
                            $password = $this->getPassword();
                            $response = $model->savePatron($merchant_id, $email, $fname, $lname, $mobile, password_hash($password, PASSWORD_DEFAULT));
                            if ($response->Message == 'success') {
                                $data['email_id'] = $email;
                                $data['password'] = $password;
                                $data['company_name'] = $company_name;
                                $mail->from_email = $from_email;
                                $mail->from_name = $company_name;
                                $mail->sendmail($email, $email_template, $data, $subject);
                                $success_count++;
                            } else {
                                print $int . " Error registration failed. " . $response->Message . PHP_EOL;
                            }
                        } else {
                            print $int . " Row Customer email is already exist" . PHP_EOL;
                        }
                    }
                }
                $int++;
            }
            fclose($csvfile);
            if ($success_count > 0) {
                print 'Total ' . $success_count . ' registration success';
            }
        } else {
            print "File does not exist";
        }
    }
    function getPassword($length = 6, $type = 3)
    {
        if ($type == 1) {
            $combinamtion = '0123456789';
        } elseif ($type == 2) {
            $combinamtion = 'bcdfghjklmnpqrstvwxyz';
        } else {
            $combinamtion = '0123456789bcdfghjklmnpqrstvwxyz';
        }
        return substr(str_shuffle(str_repeat($x = $combinamtion, ceil($length / strlen($x)))), 1, $length);
    }
}
