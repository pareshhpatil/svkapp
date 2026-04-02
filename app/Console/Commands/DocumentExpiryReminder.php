<?php

namespace App\Console\Commands;

use App\Http\Controllers\ApiController;
use App\Models\DocumentModel;
use App\Models\ParentModel;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DocumentExpiryReminder extends Command
{
    protected $signature = 'document:expiry-reminder';

    protected $description = 'Send 30/15/7-day compliance document expiry reminders (WhatsApp + app push)';

    public function handle(): int
    {
        $adminIds = config('compliance.reminder_admin_user_ids', [1]);
        $whatsappTemplate = config('compliance.whatsapp_template', 'document_expiry_reminder');

        $docs = DB::table('ridetrack_compliance_document as d')
            ->leftJoin('driver as dr', 'dr.id', '=', 'd.driver_id')
            ->where('d.is_active', 1)
            ->whereNotNull('d.expiry_date')
            ->whereDate('d.expiry_date', '>', Carbon::today()->toDateString())
            ->select('d.*', 'dr.name as driver_display_name')
            ->get();

        $api = new ApiController();
        $model = new ParentModel();

        $windowColumns = [
            '30d' => 'reminder_30d_sent_at',
            '15d' => 'reminder_15d_sent_at',
            '7d' => 'reminder_7d_sent_at',
        ];

        foreach ($docs as $doc) {
            $expiry = Carbon::parse($doc->expiry_date)->startOfDay();
            $today = Carbon::today();
            $daysUntil = (int) $today->diffInDays($expiry, false);

            $window = null;
            if ($daysUntil === 30) {
                $window = '30d';
            } elseif ($daysUntil === 15) {
                $window = '15d';
            } elseif ($daysUntil === 7) {
                $window = '7d';
            }

            if ($window === null) {
                continue;
            }

            $col = $windowColumns[$window];
            if (!empty($doc->{$col})) {
                continue;
            }

            $docTypeLabel = DocumentModel::subtypeLabel($doc->document_category ?? null, $doc->document_type ?? null);
            $docNameAndType = ($doc->name ?? '') !== ''
                ? trim($doc->name . ' — ' . $docTypeLabel)
                : $docTypeLabel;
            $expiryFormatted = $expiry->format('d M Y');
            $daysText = match ($window) {
                '30d' => 'in 30 days (1 month)',
                '15d' => 'in 15 days',
                '7d' => 'in 7 days',
                default => '',
            };

            $assigneeName = ($doc->document_category === DocumentModel::CATEGORY_DRIVER && !empty($doc->driver_display_name))
                ? $doc->driver_display_name
                : ($doc->name ?? 'Document');

            $recipients = [];
            $name='User';
            if (!empty($doc->assigned_user_id)) {
                $recipients[(int) $doc->assigned_user_id] = false;
                $userdetial = DB::table('users')->where('id', $doc->assigned_user_id)->where('is_active', 1)->first();
                $name=$userdetial->name;
            }
            foreach ($adminIds as $aid) {
                $aid = (int) $aid;
                if ($aid < 1) {
                    continue;
                }
                if (!array_key_exists($aid, $recipients)) {
                    $recipients[$aid] = true;
                }
            }

            if ($recipients === []) {
                continue;
            }

            $sentAny = false;

            foreach ($recipients as $userId => $useDriverLabel) {
                $user = DB::table('users')->where('id', $userId)->where('is_active', 1)->first();
                if (!$user) {
                    Log::warning('DocumentExpiryReminder: user not found', ['user_id' => $userId, 'document_id' => $doc->id]);
                    continue;
                }

                // Meta template document_expiry_reminder: exactly 3 body params ({{1}} {{2}} {{3}})
                $nameParam = $useDriverLabel ? 'Driver' : $assigneeName;
                $paramsWhatsapp = [
                    ['type' => 'text', 'text' => $name],
                    ['type' => 'text', 'text' => $docNameAndType],
                    ['type' => 'text', 'text' => $expiryFormatted],
                ];

                $title = 'Document expiry reminder';
                $body = $nameParam . ': ' . $docNameAndType . ' expires on ' . $expiryFormatted . ' (' . $daysText . ').';

                $allowPush = (int) ($user->app_notification ?? 1) === 1;

                if (!empty($user->token) && $allowPush) {
                    try {
                        $ok = $api->sendNotificationToDevice((string) $user->token, $title, $body, '', '');
                        if ($ok !== false) {
                            $sentAny = true;
                        }
                    } catch (\Throwable $e) {
                        Log::error('DocumentExpiryReminder: FCM failed', ['user_id' => $userId, 'e' => $e->getMessage()]);
                    }
                }

                $mobile = $user->mobile ?? '';
                if (is_string($mobile) && strlen($mobile) === 10 && $whatsappTemplate !== '') {
                    try {
                        $wa = $api->sendWhatsappMessage($mobile, 'mobile', $whatsappTemplate, $paramsWhatsapp, null, 'en', 0);
                        if ($wa !== false && $wa !== null) {
                            $sentAny = true;
                        }
                    } catch (\Throwable $e) {
                        Log::error('DocumentExpiryReminder: WhatsApp failed', ['user_id' => $userId, 'e' => $e->getMessage()]);
                    }
                }
            }

            if ($sentAny) {
                $now = date('Y-m-d H:i:s');
                $update = [
                    'last_reminder_sent_at' => $now,
                    $col => $now,
                ];
                $model->updateArray('ridetrack_compliance_document', 'id', (int) $doc->id, $update);
                $this->line("Reminder sent for document #{$doc->id} ({$window}).");
            }
        }

        return 0;
    }
}
