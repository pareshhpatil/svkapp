<?php

namespace App\Console\Commands;

use App\Constants\Models\IColumn;
use App\Constants\Models\ITable;
use App\Model\Merchant\SubUser\Role;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AssignAdminRoleToExistingUsersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assign:user:roles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign user roles to already existing users';

    /**
     * Execute the console command.
     *
     * @return mixed
     */

    public function handle()
    {
        $UserIDs = DB::table(ITable::USER)
            ->pluck(IColumn::USER_ID)
            ->toArray();

        foreach ($UserIDs as $userID) {
            $checkRole = DB::table(ITable::BRIQ_USER_ROLES)
                            ->where(IColumn::USER_ID, $userID)
                            ->exists();

            if (!$checkRole) {
                /** @var Role $Role */
                $Role = Role::query()
                            ->where(IColumn::NAME, 'Admin')
                            ->first();

                DB::table(ITable::BRIQ_USER_ROLES)
                    ->insert([
                        'user_id' => $userID,
                        'role_id' => $Role->id,
                        'role_name' => $Role->name,
                        'created_by' => '',
                        'updated_by' => '',
                        IColumn::CREATED_AT  => Carbon::now()->toDateTimeString(),
                        IColumn::UPDATED_AT  => Carbon::now()->toDateTimeString()
                    ]);
            }

        }

    }
}
