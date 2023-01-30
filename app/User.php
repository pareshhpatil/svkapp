<?php

namespace App;

use App\Constants\Models\IColumn;
use App\Constants\Models\ITable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

/**
 * @property mixed $user_id
 */
class User extends Authenticatable {

    use Notifiable;

    /**
     * Changed auth table users to user
     *
     * @var string
     */
    protected $table = 'user';
    protected $primaryKey = 'id';

    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'last_updated_date';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'email_id', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * @return Model|Builder|object|null
     * @author Nitish
     */
    public function role()
    {
        return DB::table(ITable::BRIQ_ROLES)
                   ->where(IColumn::ID, $this->roleID())
                   ->first();
    }

    /**
     * @return mixed
     * @author Nitish
     */
    public function roleID()
    {
        return DB::table(ITable::BRIQ_USER_ROLES)
                ->where(IColumn::USER_ID, $this->user_id)
                ->pluck(IColumn::ROLE_ID)
                ->first();
    }

}
