<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
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
        return ['firebase', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
//    public function toMail($notifiable)
//    {
//        return (new MailMessage)
//                    ->line('The introduction to the notification.')
//                    ->action('Notification Action', url('/'))
//                    ->line('Thank you for using our application!');
//    }

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
            ->withPriority('high')->asMessage($this->User->fcm_token);
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
            'user_id' => $this->User->user_id,
            'payment_request_id' => $this->paymentRequestID,
            'invoice_number' => $this->invoiceNumber,
        ];
    }
}
