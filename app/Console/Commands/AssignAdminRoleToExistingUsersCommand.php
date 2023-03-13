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
    protected $signature = 'assign:admin:roles';

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
        $Users = DB::table(ITable::USER)
            ->select([IColumn::USER_ID, IColumn::GROUP_ID])
            ->get();

        foreach ($Users as $user) {

            $isUserRoleExists = DB::table(ITable::BRIQ_USER_ROLES)
                            ->where(IColumn::USER_ID, $user->user_id)
                            ->exists();

            if (!$isUserRoleExists) {
                $merchant = DB::table('merchant')
                                ->where('group_id', $user->group_id)
                                ->first();
                if(!empty($merchant)) {
                    /** @var Role $Role */
                    $Role = Role::query()
                        ->where(IColumn::NAME, 'Admin')
                        ->where(IColumn::MERCHANT_ID, $merchant->merchant_id)
                        ->first();

                    if(!$Role) {
                        DB::table(ITable::BRIQ_ROLES)
                            ->insert([
                                'merchant_id' => $merchant->merchant_id,
                                'name' => "Admin",
                                'description' => "Can create / edit users and any objects (invoice. contract, co) created by admin will not go through approval process",
                                'created_by' => $user->user_id,
                                'last_updated_by' => $user->user_id,
                                IColumn::CREATED_AT  => Carbon::now()->toDateTimeString(),
                                IColumn::UPDATED_AT  => Carbon::now()->toDateTimeString()
                            ]);

                        /** @var Role $Role */
                        $Role = Role::query()
                            ->where(IColumn::NAME, 'Admin')
                            ->where(IColumn::MERCHANT_ID, $merchant->merchant_id)
                            ->first();
                    }

                    DB::table(ITable::BRIQ_USER_ROLES)
                        ->insert([
                            'user_id' => $user->user_id,
                            'role_id' => $Role->id,
                            'role_name' => $Role->name,
                            'created_by' => '',
                            'updated_by' => '',
                            IColumn::CREATED_AT  => Carbon::now()->toDateTimeString(),
                            IColumn::UPDATED_AT  => Carbon::now()->toDateTimeString()
                        ]);
                } else {
                    $this->info('merchant not exists for '. $user->user_id);
                }
            }

        }

    }
}
