<?php

namespace App\Console\Commands;

use App\Constants\Models\ITable;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class UpdateParticularColumnCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:particular:columns';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'update particular columns';

    /**
     * Execute the console command.
     *
     * @return mixed
     */

    public function handle()
    {
        $columns = DB::table(ITable::INVOICE_TEMPLATE)
                    ->where('template_type', 'construction')
                    ->get(['template_id', 'particular_column'])
                    ->toArray();

        foreach ($columns as $column) {

            DB::table(ITable::INVOICE_TEMPLATE)
                ->where('template_id', $column->template_id)
                ->update([
                    'particular_column' => '{"bill_code":"Bill Code","description":"Desc","bill_type":"Bill Type","cost_type":"Cost Type","original_contract_amount":"Original Contract Amount","approved_change_order_amount":"Approved Change Order Amount","current_contract_amount":"Current Contract Amount","previously_billed_percent":"Previously Billed Percent","previously_billed_amount":"Previously Billed Amount","current_billed_percent":"Current Billed Percent","current_billed_amount":"Current Billed Amount","previously_stored_materials":"Previously Stored Materials","current_stored_materials":"Current Stored Materials","stored_materials":"Materials Presently Stored","total_billed":"Total Billed (including this draw)","retainage_percent":"Retainage %","retainage_amount_previously_withheld":"Retainage Amount Previously Withheld","retainage_amount_for_this_draw":"Retainage amount for this draw","net_billed_amount":"Net Billed Amount","retainage_release_amount":"Retainage Release Amount","total_outstanding_retainage":"Total outstanding retainage","project":"Project","cost_code":"Cost Code","group":"Group","bill_code_detail":"Bill code detail"}'
                ]);
        }
    }
}
