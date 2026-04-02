<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Compliance document expiry reminders (scheduler)
    |--------------------------------------------------------------------------
    |
    | COMPLIANCE_REMINDER_ADMIN_IDS: comma-separated users.id values that also
    | receive reminders; WhatsApp/app copy uses the label "Driver" for them.
    |
    | COMPLIANCE_WHATSAPP_TEMPLATE: Meta template document_expiry_reminder (en) with 3 body
    | variables: (1) greeting name, (2) document name + type (e.g. "MH12 — Driving licence"), (3) expiry date.
    |
    */

    'reminder_admin_user_ids' => array_values(array_filter(array_map(
        'intval',
        explode(',', (string) env('COMPLIANCE_REMINDER_ADMIN_IDS', '1'))
    ), function (int $id) {
        return $id > 0;
    })),

    'whatsapp_template' => env('COMPLIANCE_WHATSAPP_TEMPLATE', 'document_expiry_reminder'),

];
