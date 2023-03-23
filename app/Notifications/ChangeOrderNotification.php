<?php

namespace App\Notifications;

use App\Libraries\Encrypt;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Kutia\Larafirebase\Messages\FirebaseMessage;

class ChangeOrderNotification extends Notification
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
        $channels = ['database'];

        $preferences = DB::table('preferences')
            ->where('user_id', $notifiable->user_id)
            ->first();

        if (empty($preferences)) {
            return $channels;
        }

        if($preferences->send_push == 1) {
            if (!empty($this->User->fcm_token)) {
                $channels[] = 'firebase';
            }
        }

        return $channels;
    }

    /**
     * Get the firebase representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toFirebase($notifiable)
    {
        return (new FirebaseMessage())
            ->withTitle($this->orderNumber)
            ->withBody($this->orderNumber . ' Change Order Pending for approval')
            ->withPriority('low')->asMessage($this->User->fcm_token);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'type' => 'change-order',
            'user_id' => $this->User->user_id,
            'order_id' => Encrypt::encode($this->orderID),
            'order_number' => $this->orderNumber
        ];
    }
}
