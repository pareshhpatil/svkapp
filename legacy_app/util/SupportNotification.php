<?php

use App\Jobs\SupportTeamNotification;

class SupportNotification {

    public function notificationJob($subject, $message,$type='SUPPORT') {
        try {
            SupportTeamNotification::dispatch($subject, $message,$type)->onQueue(env('SQS_MERCHANT_REGISTRATION_NOTIFICATION_QUEUE'));
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, 'Error while SupportNotification dispatch job : ' . $e->getMessage());
        }
    }

}
