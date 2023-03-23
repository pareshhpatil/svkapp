<?php

namespace App\Jobs;

use App\Notifications\ChangeOrderMailNotification;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessChangeOrderMailForApproveJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /***
     * @var User $User
     */
    protected $User;

    protected $OrderDetail;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($OrderDetail, $User)
    {
        $this->OrderDetail = $OrderDetail;
        $this->User = $User;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws \Exception
     */
    public function handle()
    {
        $this->User->notify(new ChangeOrderMailNotification($this->OrderDetail->order_id, $this->OrderDetail->order_no, $this->User));
    }
}
