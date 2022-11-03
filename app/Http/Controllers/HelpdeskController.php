<?php

namespace App\Http\Controllers;

use App\Libraries\Helpers;

class HelpdeskController extends Controller
{

    public function handle($param1, $param2 = null)
    {
        $url = $param1;
        if ($param2 != null) {
            $url .= '/' . $param2;
        }
        $redirect_url = $this->getURL($url);
        return redirect($redirect_url, 301);
    }

    function getURL($key)
    {
        $array['what-is-swipez'] = 'https://helpdesk.swipez.in/help/what-is-swipez-billing';
        $array['what-type-of-businesses-can-use-swipez-to-collect-payments'] = 'https://helpdesk.swipez.in/help/what-types-of-businesses-can-use-swipez';
        $array['how-will-swipez-benefit-my-customers'] = 'https://helpdesk.swipez.in/help/how-will-swipez-benefit-my-customers';
        $array['what-do-you-mean-by-a-transaction-charge'] = 'https://helpdesk.swipez.in/help/what-are-transaction-charges-or-tdr-s';
        $array['how-do-i-add-a-payment-gateway-to-my-website'] = 'https://helpdesk.swipez.in/help/how-do-i-add-a-payment-gateway-to-my-website';
        $array['how-do-i-enable-online-payment-for-my-website'] = 'https://helpdesk.swipez.in/help/how-do-i-add-a-payment-gateway-to-my-website';
        $array['does-swipez-provide-multiple-logins'] = 'https://helpdesk.swipez.in/help/how-to-add-a-sub-merchant-and-provide-them-access-to-the-dashboard';
        $array['advantages-of-creating-a-company-profile'] = 'https://helpdesk.swipez.in/help/how-to-update-view-your-company-profile';
        $array['can-i-add-individuals-to-use-few-features-on-the-merchant-dashboard'] = 'https://helpdesk.swipez.in/help/how-to-add-a-sub-merchant-and-provide-them-access-to-the-dashboard';
        $array['what-are-the-transaction-charges-applicable-for-online-payment-collection'] = 'https://helpdesk.swipez.in/help/what-are-transaction-charges-or-tdr-s';
        $array['can-i-submit-KYC-documents-online'] = 'https://helpdesk.swipez.in/help/how-to-fill-your-kyc-form-to-collect-online-payments';
        $array['how-to-collect-money-online'] = 'https://helpdesk.swipez.in/help/billing-software-overview';
        $array['what-if-i-am-a-merchant-a-customer-do-i-need-different-logins'] = 'https://helpdesk.swipez.in/help/what-if-i-am-a-merchant-a-customer-do-i-need-different-logins';
        $array['can-i-use-swipez-without-registering'] = 'https://helpdesk.swipez.in/help/can-i-use-swipez-without-registering';
        $array['can-i-use-swipez-if-i-dont-have-KYC-documents'] = 'https://helpdesk.swipez.in/help/billing-software-overview';
        $array['how-do-i-register-on-swipez'] = 'https://helpdesk.swipez.in/help/how-do-i-register-on-swipez';
        $array['how-much-does-it-cost-to-sign-up'] = 'https://helpdesk.swipez.in/help/how-much-does-it-cost-to-sign-up';
        $array['i-dont-have-a-website-how-can-i-collect-payments-online'] = 'https://helpdesk.swipez.in/help/billing-software-overview';
        $array['how-long-does-the-application-process-take'] = 'https://helpdesk.swipez.in/help/after-registering-how-long-before-i-can-collect-online-payments';
        $array['what-do-i-need-to-collect-payments-online/'] = 'https://helpdesk.swipez.in/help/how-to-fill-your-kyc-form-to-collect-online-payments';

        $array['article/can-i-carbon-copy-invoice-emails'] = 'https://helpdesk.swipez.in/help/cc-invoices-on-email';
        $array['article/how-can-i-found-out-the-payment-details-of-each-customer'] = 'https://helpdesk.swipez.in/help/how-can-i-check-payment-details-of-all-my-invoices-and-estimates';
        $array['article/can-consumers-change-their-communication-preferences'] = 'https://helpdesk.swipez.in/help/can-consumers-change-their-communication-preferences';
        $array['fixedfee'] = 'https://helpdesk.swipez.in/help/create-an-invoice-format';
        $array['how-can-i-view-invoices-sent-to-a-customer-after-i-have-automated-the-payment-subscription'] = 'https://helpdesk.swipez.in/help/how-to-modify-subscription-based-invoices';
        $array['can-i-resend-the-invoice-of-a-particular-customer-which-was-send-via-bulk-upload-feature'] = 'https://helpdesk.swipez.in/help/how-to-edit-bulk-uploaded-invoices';
        $array['duration-payment-template'] = 'https://helpdesk.swipez.in/help/create-an-invoice-format';
        $array['multiplier-payment-template'] = 'https://helpdesk.swipez.in/help/create-an-invoice-format';
        $array['adding-a-new-row-in-the-taxes-section'] = 'https://helpdesk.swipez.in/help/how-to-add-your-tax-values-to-simplify-your-billing';
        $array['adding-a-new-row-in-particulars-section'] = 'https://helpdesk.swipez.in/help/create-an-invoice-format';
        $array['customising-header'] = 'https://helpdesk.swipez.in/help/create-an-invoice-format';
        $array['standard-payment-template'] = 'https://helpdesk.swipez.in/help/create-an-invoice-format';
        $array['simple-payment-template'] = 'https://helpdesk.swipez.in/help/create-an-invoice-format';
        $array['can-i-notify-my-suppliers-for-incoming-payments'] = 'https://helpdesk.swipez.in/help/notify-suppliers-on-invoice-payment';
        $array['where-can-i-view-invoice-templates-already-created-on-the-dashboard'] = 'https://helpdesk.swipez.in/help/add-custom-fields-to-your-invoice';
        $array['how-do-i-view-the-payment-templates-that-are-already-created-by-me'] = 'https://helpdesk.swipez.in/help/add-custom-fields-to-your-invoice';
        $array['notify-a-vendor-when-a-customer-payment-has-been-made'] = 'https://helpdesk.swipez.in/help/notify-suppliers-on-invoice-payment';
        $array['where-do-i-add-additional-charges-such-as-service-charge'] = 'https://helpdesk.swipez.in/help/how-to-add-your-tax-values-to-simplify-your-billing-process';
        $array['how-do-i-modify-an-existing-template'] = 'https://helpdesk.swipez.in/help/add-custom-fields-to-your-invoice';
        $array['how-to-automate-recurring-payment-requests-to-my-customers'] = 'https://helpdesk.swipez.in/help/how-to-send-auto-recurring-invoices-with-frequencies-of-your-choice';
        $array['rebatestemplate'] = 'https://helpdesk.swipez.in/help/event-registrations';
        $array['promotion-simple-template'] = 'https://helpdesk.swipez.in/help/event-registrations';
        $array['event-with-package-payment-template'] = 'https://helpdesk.swipez.in/help/event-registrations';
        $array['event-simple-payment-template'] = 'https://helpdesk.swipez.in/help/event-registrations';
        $array['how-do-i-create-a-discount-coupon'] = 'https://helpdesk.swipez.in/help/how-to-create-an-event';
        $array['where-can-i-view-customer-payments-made-for-a-particular-event'] = 'https://helpdesk.swipez.in/help/how-to-view-a-list-of-transactions-for-multiple-events';
        $array['how-do-i-edit-an-event-that-is-already-created'] = 'https://helpdesk.swipez.in/help/how-can-i-check-and-edit-existing-events';
        $array['how-do-i-add-a-coupon-to-my-event'] = 'https://helpdesk.swipez.in/help/how-to-create-an-event';
        $array['create-a-payment-link-for-your-event'] = 'https://helpdesk.swipez.in/help/how-to-create-an-event';
        $array['create-event-with-different-pricing-packages'] = 'https://helpdesk.swipez.in/help/how-to-create-an-event';
        $array['where-can-i-view-my-events'] = 'https://helpdesk.swipez.in/help/how-can-i-check-and-edit-existing-events';
        $array['how-can-i-create-an-event-and-collect-payments'] = 'https://helpdesk.swipez.in/help/how-to-fill-your-kyc-form-to-collect-online-payments';
        $array['how-do-i-view-my-revenue-after-online-payment-charges'] = 'https://helpdesk.swipez.in/help/how-can-i-check-payments-that-have-been-received';
        $array['how-do-i-view-the-payment-request-details-send-from-my-dashboard'] = 'https://helpdesk.swipez.in/help/how-do-i-view-and-edit-unpaid-invoices-or-estimates';
        $array['can-i-send-multiple-payment-requests-in-one-go'] = 'https://helpdesk.swipez.in/help/how-to-create-multiple-invoices-at-the-same-time';
        $array['how-do-i-send-payment-requests-after-creating-a-template'] = 'https://helpdesk.swipez.in/help/how-to-create-an-invoice';
        $array['what-if-the-customer-raises-a-dispute-after-paying'] = 'https://helpdesk.swipez.in/help/what-if-the-customer-raises-a-dispute-after-paying-83786a57';
        $array['how-to-send-reminders-or-payment-notifications-to-my-customers'] = 'https://helpdesk.swipez.in/help/configure-payment-due-reminder-schedule';
        $array['i-have-got-a-payment-notification-but-the-payment-is-not-reflecting-in-the-bank-account'] = 'https://helpdesk.swipez.in/help/i-have-got-a-payment-notification-but-the-payment-is-not-reflecting-in-the-bank-account';
        $array['what-happens-when-a-transaction-fails'] = 'https://helpdesk.swipez.in/help/what-happens-when-a-transaction-fails';
        $array['how-fast-are-the-payments-processed'] = 'https://helpdesk.swipez.in/help/i-have-got-a-payment-notification-but-the-payment-is-not-reflecting-in-the-bank-account';
        $array['how-will-I-know-if-a-customer-has-made-a-payment'] = 'https://helpdesk.swipez.in/help/how-can-i-check-payments-that-have-been-received';
        $array['what-type-of-online-payments-options-will-i-be-able-to-accept'] = 'https://helpdesk.swipez.in/help/what-type-of-online-payments-options-will-i-be-able-to-accept-through-swipez';
        $array['is-there-a-charge-for-using-transaction-reports'] = 'https://helpdesk.swipez.in/help/how-can-i-check-payment-details-of-all-my-invoices';
        $array['what-information-is-included-in-the-transactions-report'] = 'https://helpdesk.swipez.in/help/how-can-i-check-payment-details-of-all-my-invoices';
        $array['what-kind-of-reports-can-i-view'] = 'https://helpdesk.swipez.in/help/swipez-reporting-overview';
        $array['do-you-offer-any-reports-to-manage-my-business'] = 'https://helpdesk.swipez.in/help/swipez-reporting-overview';
        $array['what-is-the-function-of-the-avail-feature-in-the-event-transactions-page'] = 'https://helpdesk.swipez.in/help/how-to-use-the-qr-code-reader-to-simplify-event-entry';
        $array['what-is-the-my-transactions-tab'] = 'https://helpdesk.swipez.in/help/how-to-check-details-of-customer-transactions';
        $array['can-i-upload-keep-my-customer-data-online-for-repeat-sending'] = 'https://helpdesk.swipez.in/help/business-contact-management-overview';
        $array['how-do-i-track-and-reconcile-offline-transaction'] = 'https://helpdesk.swipez.in/help/how-to-view-edit-invoices-and-estimates';
        $array['how-can-i-avoid-getting-sms-email-for-each-transaction'] = 'https://helpdesk.swipez.in/help/how-to-manage-general-account-settings';
        $array['article/create-your-own-website-in-minutes'] = 'https://helpdesk.swipez.in/help';
        $array['article/create-your-own-brand-website-in-minutes'] = 'https://helpdesk.swipez.in/help';

        if (isset($array[$key])) {
            return $array[$key];
        } else {
            return 'https://helpdesk.swipez.in/help';
        }
    }
}
