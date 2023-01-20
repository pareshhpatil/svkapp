<?php

namespace App\Model\Merchant\SubUser;

use App\Constants\Models\IColumn;
use App\Constants\Models\ITable;
use App\Model\Base;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

/**
 * App\Models\CSM\Permission
 *
 * @property int                             $id
 * @property string                          $name
 * @property string                          $slug
 * @property string|null                     $group
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static Builder|Permission whereCreatedAt($value)
 * @method static Builder|Permission whereGroup($value)
 * @method static Builder|Permission whereId($value)
 * @method static Builder|Permission whereName($value)
 * @method static Builder|Permission whereSlug($value)
 * @method static Builder|Permission whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Permission extends Base
{
    protected $table = ITable::BRIQ_PERMISSIONS;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        IColumn::NAME,
        IColumn::SLUG,
        IColumn::GROUP
    ];

}
