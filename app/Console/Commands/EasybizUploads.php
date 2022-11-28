<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\MigrateModel;

class EasybizUploads extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'easybiz:uploads';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Easybiz uploads';

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
        $dir = 'public/tmp/uploads/documents';
        $folders = scandir($dir);
        foreach ($folders as $name) {
            if (strlen($name) == 10) {
                $detail = $model->getTableRow('e_merchant', 'merchant_id', $name);
                if (!empty($detail)) {
                    rename($dir . "/" . $name, $dir . "/" . $detail->new_merchant_id);
                } else {
                    echo 'Empty details Docs ' . $dir . $name;
                }
            }
        }

        $dir = 'public/tmp/uploads/Excel';
        $folders = scandir($dir);
        foreach ($folders as $name) {
            if (strlen($name) == 10) {
                $detail = $model->getTableRow('e_merchant', 'merchant_id', $name);
                if (!empty($detail)) {
                    rename($dir . "/" . $name, $dir . "/" . $detail->new_merchant_id);
                } else {
                    echo 'Empty details Excel ' . $name;
                }
            }
        }

        $dir = 'public/tmp/uploads/images/logos';
        $logos = scandir($dir);
        foreach ($logos as $name) {
            if (strlen($name) > 10) {
                rename($dir . "/" . $name, $dir . "/" . 'easybiz_' . $name);
            }
        }
        
        $dir = 'public/tmp/uploads/images/landing';
        $logos = scandir($dir);
        foreach ($logos as $name) {
            if (strlen($name) > 10) {
                rename($dir . "/" . $name, $dir . "/" . 'easybiz_' . $name);
            }
        }
    }

}
