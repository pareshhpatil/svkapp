<?php

namespace App\Model\Merchant\SubUser;

use App\Constants\Models\IColumn;
use App\Constants\Models\ITable;
use App\Model\Merchant\SubUser\Permission;
use App\Model\Base;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Model\Merchant\SubUser\UserRoles
 *
 * @property int                             $id
 * @property string                          $user_id
 * @property mixed                           $role_id
 * @property string                          $role_name
 * @property string                          $created_by
 * @property string                          $last_updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static Builder|SubUsersRoles whereCreatedAt($value)
 * @method static Builder|SubUsersRoles whereId($value)
 * @method static Builder|SubUsersRoles whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SubUsersRoles extends Base
{
    use SoftDeletes;
    
    protected $table = ITable::BRIQ_USER_ROLES;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        IColumn::USER_ID,
        IColumn::ROLE_ID,
        IColumn::ROLE_NAME,
        IColumn::CREATED_BY,
        IColumn::LAST_UPDATE_BY
    ];
}