CREATE DEFINER=`root`@`localhost` PROCEDURE `getinvoice_details`(_merchant_id char(10), _paymentrequest_id varchar(10))
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
	show errors;
		BEGIN
		END; 

SET @payment_req=_paymentrequest_id;
set @patron_id='';
set @merchant_id='';
SET @patron_user_id='';
SET @domain='';
SET @display_url='';
SET @pg_count=0;
SET @main_company_name='';
SET @customer_id=0;
SET @registration_number='';

SELECT 
    invoice_number,
    invoice_total,
    grand_total,
    advance_received,
    swipez_total,
    DATE_FORMAT(bill_date, '%d %b %Y'),
    previous_due,
    basic_amount,
    tax_amount,
    narrative,
    absolute_cost,
    late_payment_fee,
    expiry_date,
    DATE_FORMAT(due_date, '%d %b %Y'),
    customer_id,
    merchant_id,
    user_id,
    billing_cycle_id,
    payment_request_status,
    payment_request_type,
    converted_request_id,
    invoice_type,
    template_id,
    notify_patron,
    has_custom_reminder,
    short_url,
    franchise_id,
    vendor_id,
    autocollect_plan_id,
    paid_amount,
    plugin_value,
    document_url,
    gst_number,
    billing_profile_id
INTO  @invoice_number , @invoice_total , @grand_total , @advance , @swipez_total , @bill_date , @previous_due , @basic_amount , @tax_amount ,  @narrative , @absolute_cost , @late_fee , @expiry_date , @due_date , @customer_id , @merchant_id , @merchant_user_id , @cycle_id , @status , @payment_request_type,@converted_request_id,@invoice_type , @template_id , @notify_patron ,   @has_custom_reminder , @short_url , @franchise_id , @vendor_id,  @autocollect_plan_id,@paid_amount,@plugin_value,@document_url,@merchant_gst_number,@billing_profile_id FROM
    payment_request
WHERE
    payment_request_id = @payment_req
        AND (_merchant_id = 'customer'
        OR merchant_id = _merchant_id);

if(@customer_id>0)then

    select customer_code, concat(first_name,' ',last_name),concat(address,address2),city,zipcode,state,user_id,email,mobile into @customer_code,@customer_name,@customer_address,@customer_city
    ,@customer_zip,@customer_state,@customer_user_id,@customer_email,@customer_mobile from customer where customer_id=@customer_id;
    
 
SELECT 
    image_path,
    banner_path,
    template_name,
    template_type,
    particular_column,
    particular_total,
    tax_total,
    properties,
    tnc
INTO @image_path , @banner_path , @template_name , @template_type,@particular_column,@particular_total,@tax_total,@properties,@tnc  FROM
    invoice_template
WHERE
    template_id = @template_id;
 
SELECT 
    display_url,
    merchant_domain,
    merchant_type,
    is_legal_complete,
    disable_online_payment,
    merchant_website
INTO @display_url , @merchant_domain , @merchant_type , @legal_complete , @disable_online_payment , @merchant_website   FROM
    merchant
WHERE
    merchant_id = @merchant_id;
 
SELECT 
    statement_enable,
    show_ad,
    promotion_id,
    from_email,
    settlements_to_franchise,
    sms_gateway,
    sms_gateway_type,
    sms_name
INTO @statement_enable , @show_ad , @promotion_id , @from_email , @settlements_to_franchise , @sms_gateway , @sms_gateway_type , @sms_name FROM
    merchant_setting
WHERE
    merchant_id = @merchant_id;
 

 
 
SELECT 
    cycle_name
INTO @cycle_name FROM
    billing_cycle_detail
WHERE
    billing_cycle_id = @cycle_id;
 
SELECT 
    config_value
INTO @domain FROM
    config
WHERE
    config_type = 'merchant_domain'
        AND config_key = @merchant_domain;
 
if(@disable_online_payment=0 and @legal_complete=1)then 
SELECT 
    COUNT(fee_detail_id)
INTO @pg_count FROM
    merchant_fee_detail
WHERE
    merchant_id = @merchant_id and franchise_id=0
        AND is_active = 1;
 end if;
 
 
 if(@pg_count<1 or @disable_online_payment=1 or @legal_complete=0)then
 SET @legal_complete=0;
 end if;
 
 
 if(@billing_profile_id>0)then
 SELECT 
    address, business_email, business_contact,state,company_name,company_name,gst_number, pan, tan, cin_no
INTO @merchant_address , @business_email , @business_contact,@merchant_state,@company_name,@display_name,@gst_number , @pan , @tan , @cin_no  FROM
    merchant_billing_profile where id = @billing_profile_id;
 else
 SELECT 
    address, business_email, business_contact,state,company_name,company_name,gst_number, pan, tan, cin_no
INTO @merchant_address , @business_email , @business_contact,@merchant_state,@company_name,@display_name,@gst_number , @pan , @tan , @cin_no  FROM
    merchant_billing_profile
WHERE
    merchant_id = @merchant_id and is_default=1;
 end if;
 
 SET @sms_name=@company_name;
 
SET @is_expire= 0;
  if(DATE_FORMAT(NOW(),'%Y-%m-%d') >= @expiry_date) then
 SET @is_expire= 1;
 end if;

 if(DATE_FORMAT(NOW(),'%d %b %Y') > @due_date and @absolute_cost>0) then
 SET @absolute_cost= @absolute_cost + @late_fee;
 end if;
 
 

 
SET @message= 'success';
SELECT 
    _paymentrequest_id AS 'payment_request_id',
    @is_expire AS 'is_expire',
    @invoice_number AS 'invoice_number',
    @expiry_date AS 'expiry_date',
    @notify_patron AS 'notify_patron',
    @domain AS 'merchant_domain',
    @display_url AS 'display_url',
    @merchant_id AS 'merchant_id',
    @merchant_user_id AS 'merchant_user_id',
    @particular_column as 'particular_column',
    @particular_total as 'particular_total',
    @tax_total as 'tax_total',
    @plugin_value as 'plugin_value',
    @status AS 'status',
    @narrative AS 'narrative',
    @absolute_cost AS 'absolute_cost',
    @advance AS 'advance',
    @previous_due AS 'previous_due',
    @cycle_name AS 'cycle_name',
    @grand_total AS 'grand_total',
    @due_date AS 'due_date',
    @Previous_dues AS 'Previous_dues',
    @statement_enable AS 'statement_enable',
    @show_ad AS 'show_ad',
    @bill_date AS 'bill_date',
    @basic_amount AS 'basic_amount',
    @tax_amount AS 'tax_amount',
    @swipez_total AS 'swipez_total',
    @invoice_total AS 'invoice_total',
    @message AS 'message',
    @company_name AS 'company_name',
    @merchant_type AS 'merchant_type',
    @legal_complete AS 'legal_complete',
    @image_path AS 'image_path',
    @banner_path AS 'banner_path',
    @template_name AS 'template_name',
    @template_type AS 'template_type',
    @merchant_address AS 'merchant_address',
    @merchant_state as 'merchant_state',
    @business_email AS 'business_email',
    @business_contact AS 'business_contact',
    @late_fee AS 'late_fee',
    @status AS 'payment_request_status',
    @payment_request_type AS 'payment_request_type',
    @invoice_type AS 'invoice_type',
    @template_id AS 'template_id',
    @coupon_id AS 'coupon_id',
    @is_coupon AS 'is_coupon',
    @promotion_id AS 'promotion_id',
    @customer_id AS 'customer_id',
    @customer_code AS 'customer_code',
    @customer_name AS 'customer_name',
    @customer_address AS 'customer_address',
    @customer_city AS 'customer_city',
    @customer_zip AS 'customer_zip',
    @customer_state AS 'customer_state',
    @customer_email AS 'customer_email',
    @customer_mobile AS 'customer_mobile',
    @customer_user_id AS 'customer_user_id',
    @custom_subject AS 'custom_subject',
    @custom_sms AS 'custom_sms',
    @has_custom_reminder AS 'has_custom_reminder',
    @covering_id AS 'covering_id',
    @franchise_id AS 'franchise_id',
    @vendor_id as 'vendor_id',
    @is_franchise AS 'is_franchise',
    @has_vendor as 'has_vendor',
    @merchant_website AS 'merchant_website',
    @gst_number AS 'gst_number',
    @pan AS 'pan',
    @tan AS 'tan',
    @cin_no AS 'cin_no',
    @registration_number AS 'registration_number',
    @from_email AS 'from_email',
    @settlements_to_franchise AS 'pg_to_franchise',
    @is_prepaid AS 'is_prepaid',
    @has_acknowledgement AS 'has_acknowledgement',
    @main_company_name AS 'main_company_name',
    @sms_gateway AS 'sms_gateway',
    @sms_gateway_type AS 'sms_gateway_type',
    @sms_name AS 'sms_name',
    @autocollect_plan_id as 'autocollect_plan_id',
    @paid_amount as 'paid_amount',
    @converted_request_id AS 'converted_request_id',
    @short_url AS 'short_url',
    @document_url AS 'document_url',
    @tnc as 'tnc',
    @properties as 'properties',
    @billing_profile_id as 'billing_profile_id',
    @message as 'message';
else
SET @message= 'success';
select @message as 'message';
end if;

END
