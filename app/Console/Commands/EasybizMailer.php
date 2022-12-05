<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\MigrateModel;
use App\Libraries\Encrypt;
class EasybizMailer extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'easybiz:mailer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Easybiz mailer';

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
        $model = new MigrateModel();
        $users = $model->getEasybizUsers();
        $fp = fopen('public/tmp/mailer.csv', 'w');
        fputcsv($fp, array('Email', 'Link', 'Name'));
        foreach ($users as $row) {
            echo '.';
            $array=array();
            $link='https://www.swipez.in/moneycontrol/easybiz/'.Encrypt::encode($row->new_merchant_id.$row->new_user_id);
            $name=$row->first_name.' '.$row->last_name;
            $array=array($row->email_id,$link,$name);
            fputcsv($fp, $array);
        }
        fclose($fp);
    }

}
