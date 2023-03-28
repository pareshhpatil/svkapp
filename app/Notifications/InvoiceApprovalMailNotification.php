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

class InvoiceApprovalMailNotification extends Notification
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
            ->subject($this->invoiceNumber . ' Requested for approval')
            ->markdown('emails.invoice.approve', [
                'user_id' => $notifiable->user_id,
                'payment_request_id' => Encrypt::encode($this->paymentRequestID),
                'invoice_number' => $this->invoiceNumber
            ]);
    }
}
