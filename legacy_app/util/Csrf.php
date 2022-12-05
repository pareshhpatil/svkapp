<?php

use Symfony\Component\Security\Csrf\CsrfTokenManager;
use Symfony\Component\Security\Csrf\CsrfToken;

/**
 *  PHP custom CSRF token to validate post request
 *
 */
class CSRF {
    /*
     * Create csrf token 
     * return string
     */

    public static function create($name) {
        $csrf = new CsrfTokenManager();
        $token = $csrf->getToken($name);
        return '<input type="hidden" name="_token" value="' . $token . '">';
    }

    /*
     * Validate post csrf token
     * return boolean
     */

    public static function validate($url) {
        $url = str_replace(array('/merchant/', '/patron/'), '', $url);
        /*
         * Set post url array which we need to validate token
         */
        $validate_url = array('customer/structuresave' => 'customer_structure',
            //'customer/customersave/popup' => 'customer_create',
            'customer/upload' => 'customer_upload',
            'customer/updatesave' => 'customer_update',
            'customer/savecustomergroup' => 'customer_group',
            'customer/register' => 'customer_login',
            'template/saved' => 'template_create',
            'template/saveupdate' => 'template_update',
            'invoice/invoicesave' => 'invoice',
            'invoice/invoiceupdate' => 'invoice',
            'bulkupload/upload' => 'invoice_upload',
            'subscription/invoicesave' => 'subscription_create',
            'subscription/invoiceupdate' => 'subscription_update',
            'directpaylink/save' => 'directpay_save',
            'directpaylink' => 'directpay_link',
            'promotions/savepromotion' => 'promotion_save',
            'promotions/savetemplate' => 'promotion_template',
            'gst/gstconnection' => 'gst_connection',
            'gst/upload' => 'gst_invoice_upload',
            'gst/gst3b' => 'gst_3b_summary',
            'gst/gst3bupload' => 'gst_3b_upload',
            //'gst/gstdraft' => 'gst_draft',
            'gst/gstsubmit' => 'gst_submit_status',
            'profile/updatesetting' => 'profile_setting',
            'supplier/suppliersave' => 'supplier_save',
            'supplier/saveupdate' => 'supplier_update',
            'product/productsave' => 'product_save',
            'product/saveupdate' => 'product_update',
            'tax/taxsave' => 'tax_save',
            'tax/saveupdate' => 'tax_update',
            'plan/plansave' => 'plan_create',
            'plan/saveupdate' => 'plan_update',
            'coupon/save' => 'coupon_save',
            'subuser/saved' => 'subuser_save',
            'subuser/saverole' => 'role_save',
            'subuser/roleupdatesaved' => 'role_update',
            'coveringnote/save' => 'coveringnote_save',
            'coveringnote/saveupdate' => 'coveringnote_update',
            'profile/accesskey' => 'profile_accesskey',
            'profile/update' => 'profile_update',
            'profile/gstsave' => 'gst_save',
            'companyprofile' => 'companyprofile',
            'companyprofile/policies' => 'companyprofile',
            'companyprofile/aboutus' => 'companyprofile',
            'companyprofile/contactus' => 'companyprofile',
            '/profile/resetpassword' => 'password_reset',
            'event/saved' => 'event_create',
            'event/updatesaved' => 'event_update',
            'vendor/offlinetransfersave' => 'vendor_transfer',
            'vendor/transfersave' => 'vendor_transfer_online',
            'vendor/upload' => 'vendor_upload',
            'vendor/vendorsave' => 'vendor_save',
            'vendor/saveupdate' => 'vendor_update',
            'franchise/upload' => 'franchise_upload',
            'bookings/savecalendar' => 'calender_save',
            'bookings/savecategory/web' => 'booking_category_save',
            'bookings/updatecategorysave' => 'booking_category_update',
            'bookings/savemembership' => 'booking_membership_save',
            'bookings/updatemembershipsave' => 'booking_membership_update',
            'bookings/updatecalendar' => 'booking_calender_update',
            'bookings/saveslots' => 'booking_slot_save',
            'bookings/updatesetting' => 'booking_setting',
            'bookings/membershiprespond' => 'booking_respond',
            'profile/send' => 'merchant_seggest',
            '/mybills' => 'mybills',
            'register/saved' => 'merchant_register',
            '/login/otpvalidate' => 'otp_validate',
        );
        if (isset($validate_url[$url])) {
            $token_name = $validate_url[$url];
            $csrf = new CsrfTokenManager();
            $token = new CsrfToken($token_name, $_POST['_token']);
            $valid = $csrf->isTokenValid($token);
            if ($valid == true) {
                //$csrf->removeToken($token_name);
            }
            return $valid;
        } else {
            return true;
        }
    }

}

?>