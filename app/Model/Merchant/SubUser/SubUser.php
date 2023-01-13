<?php

namespace App\Model\Merchant\SubUser;

use App\Constants\Models\IColumn;
use App\Constants\Models\ITable;
use App\Model\Base;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\Model\Merchant\SubUser\SubUser
 *
 * @property int                             $id
 * @property mixed                           $user_id
 * @property string                          $name
 * @property string                          $email_id
 * @property mixed                           $mobile_no
 * @property mixed                           $mob_country_code
 * @property mixed                          $password
 * @property string                          $first_name
 * @property string                          $last_name
 * @property int                             $user_status
 * @property int                             $prev_status
 * @property mixed                           $group_id
 * @property int                             $user_group_type
 * @property int                             $user_type
 * @property int                             $franchise_id
 * @property string                          $customer_group
 * @property mixed                           $created_by
 * @property mixed                           $last_updated_by
 * @property \Illuminate\Support\Carbon|null $created_date
 * @property \Illuminate\Support\Carbon|null $last_updated_date
 * @method static Builder|SubUser whereCreatedAt($value)
 * @method static Builder|SubUser whereId($value)
 * @method static Builder|SubUser whereName($value)
 * @mixin \Eloquent
 */
class SubUser extends Base
{
    protected $table = ITable::USER;

    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'last_updated_date';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        IColumn::NAME,
        IColumn::EMAIL_ID,
        IColumn::FIRST_NAME,
        IColumn::LAST_NAME
    ];
}