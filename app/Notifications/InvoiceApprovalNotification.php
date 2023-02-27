<?php

namespace App\Notifications;

use App\Libraries\Encrypt;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Kutia\Larafirebase\Messages\FirebaseMessage;

class InvoiceApprovalNotification extends Notification
{
    use Queueable;

    protected $invoiceNumber;
    protected $paymentRequestID;
    protected $User;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($invoiceNumber, $paymentRequestID, $User)
    {
        $this->invoiceNumber = $invoiceNumber;
        $this->paymentRequestID = $paymentRequestID;
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
            return ['database'];
        }

//        if($preferences->send_email == 1) {
//            $channels[] = 'mail';
//        }

        if($preferences->send_push == 1) {
            $channels[] = 'firebase';
        }

        $channels[] = 'database';

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
            ->subject($this->invoiceNumber . ' Requested for approval')
            ->markdown('emails.invoice.approve', [
                'user_id' => $notifiable->user_id,
                'payment_request_id' => Encrypt::encode($this->paymentRequestID),
                'invoice_number' => $this->invoiceNumber
            ]);

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
            ->withTitle($this->invoiceNumber)
            ->withBody($this->invoiceNumber . ' Invoice Pending for approval')
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
            'type' => 'invoice',
            'user_id' => $this->User->user_id,
            'payment_request_id' => Encrypt::encode($this->paymentRequestID),
            'invoice_number' => $this->invoiceNumber,
        ];
    }
}
