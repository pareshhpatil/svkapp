<?php

namespace App\Notifications;

use App\Libraries\Encrypt;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\DB;

class ChangeOrderMailNotification extends Notification
{
    use Queueable;

    protected $orderID;
    protected $orderNumber;
    protected $User;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($orderID, $orderNumber, $User)
    {
        $this->orderID = $orderID;
        $this->orderNumber = $orderNumber;
        $this->User = $User;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        $channels = [];

        $preferences = DB::table('preferences')
            ->where('user_id', $notifiable->user_id)
            ->first();

        if (empty($preferences)) {
            return $channels;
        }

        if($preferences->send_email == 1) {
            $channels[] = 'mail';
        }

        return $channels;
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  User  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject($this->orderNumber . 'Requested for approval')
            ->markdown('emails.order.approve', [
                'user_id' => $notifiable->user_id,
                'order_id' => Encrypt::encode($this->orderID),
                'order_number' => $this->orderNumber
            ]);
    }
}
