<?php

namespace App\Model\Merchant\SubUser;

use App\Constants\Models\IColumn;
use App\Constants\Models\ITable;
use App\Model\Base;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Session;

/**
 * App\Model\Merchant\SubUser\Role
 *
 * @property int                             $id
 * @property string                          $name
 * @property mixed                           $description
 * @property string                          $merchant_id
 * @property string                          $created_by
 * @property string                          $last_updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static Builder|Role whereCreatedAt($value)
 * @method static Builder|Role whereId($value)
 * @method static Builder|Role whereName($value)
 * @method static Builder|Role whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Role extends Base
{
    use SoftDeletes;

    protected $table = ITable::BRIQ_ROLES;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        IColumn::NAME,
        IColumn::DESCRIPTION,
        IColumn::MERCHANT_ID,
        IColumn::CREATED_BY,
        IColumn::LAST_UPDATE_BY
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function usersRoles()
    {
        return $this->hasMany(SubUsersRoles::class, 'role_id', 'id');
    }
}