<?php

namespace App\Model;

use App\Constants\Models\ITable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\DatabaseNotification;

/**
 * App\Models\Notification
 *
 * @property string               $id
 * @property int|null             $notifiable_id
 * @property string|null          $type
 * @property string|null          $notifiable_type
 * @property array                $data
 * @property \Carbon\Carbon       $read_at
 * @property \Carbon\Carbon|null  $created_at
 * @property \Carbon\Carbon|null  $updated_at
 * @property-read Model|\Eloquent $notifiable
 * @mixin \Eloquent
 */
class Notification extends DatabaseNotification
{
    protected $table = ITable::NOTIFICATIONS;
}
