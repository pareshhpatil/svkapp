<?php

namespace App;

use App\Constants\Models\IColumn;
use App\Constants\Models\ITable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;

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
    
    public function hasPermission($permission): bool
    {
        if (empty($this->role())) {
            return false;
        }
        
        return DB::table(ITable::BRIQ_ROLES_PERMISSIONS)
            ->where('role_id', $this->role()->role_id)
            ->where('permission_slug', $permission)
            ->exists();
    }

    public function permissions(): array
    {
        if (empty($this->role())) {
            return [];
        }

        return DB::table(ITable::BRIQ_ROLES_PERMISSIONS)
            ->where(IColumn::ROLE_ID, $this->role()->role_id)
            ->pluck(IColumn::PERMISSION_SLUG)
            ->toArray();
    }

    public function role()
    {
        return DB::table(ITable::BRIQ_USER_ROLES)
                   ->where(IColumn::USER_ID, $this->user_id)
                   ->first();
    }

}
