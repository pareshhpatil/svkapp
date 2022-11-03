CREATE DEFINER=`root`@`localhost` PROCEDURE `geteventinvoice_details`(_paymentrequest_id varchar(20))
BEGIN 
 DECLARE EXIT HANDLER FOR SQLEXCEPTION 
show errors;
		BEGIN
            
		END; 

SET @payment_req=_paymentrequest_id;
set @merchant_id='';
SET @patron_user_id='';
SET @count_transaction=0;
SET @domain='';
SET @display_url='';
SET @coupon_code='';
SET @c_descreption='';
SET @c_type='';
SET @c_percent='';
SET @c_fixed_amount='';
SET @c_start_date='';
SET @c_end_date='';
SET @c_limit='';
SET @c_available='';
SET @pg_count=0;

SELECT 
    duration,
    occurence,
    franchise_id,
    vendor_id,
    user_id,
    template_id,
    event_name,
    venue,
    description,
    event_from_date,
    event_to_date,
    tax_text,
    tax,
    mobile_capture,
    age_capture,
    event_type,
    coupon_code,
    is_active,
    capture_attendee_details,
    custom_capture_detail,
    custom_capture_title,
    short_url,
    has_season_package,
    tnc,
    cancellation_policy,
    artist,
	artist_label,
    unit_type,
    title,
    short_description,
    payee_capture,
    stop_booking_time,
    attendees_capture    
INTO @duration , @occurence , @franchise_id ,@vendor_id, @merchant_user_id , @template_id , @event_name , @venue , @description , @event_from_date 
, @event_to_date ,@tax_text, @tax , @mobile_capture , @age_capture , @event_type , @coupon_id , @is_active , @capture_attendee_details 
, @custom_capture_detail , @custom_capture_title , @short_url,@has_season_package,@terms,@privacy,@artist,@artist_label,@unit_type,@title
,@short_description,@payee_capture,@stop_booking_time,@attendees_capture FROM
    event_request
WHERE
    event_request_id = @payment_req;


 
SELECT 
    image_path, banner_path, template_name, template_type
INTO @image_path , @banner_path , @template_name , @template_type FROM
    invoice_template
WHERE
    template_id = @template_id;
 
SELECT 
    display_url,
    merchant_domain,
    merchant_type,
    is_legal_complete,
	disable_online_payment,
    merchant_id
INTO @display_url , @merchant_domain , @merchant_type , @legal_complete,@disable_online_payment , @merchant_id FROM
    merchant
WHERE
    user_id = @merchant_user_id;
 
 
SELECT 
    settlements_to_franchise
INTO @settlements_to_franchise FROM
    merchant_setting
WHERE
    merchant_id = @merchant_id;
 
SELECT 
    address, business_email, business_contact,company_name,company_name
INTO @merchant_address , @business_email , @business_contact,@company_name,@display_name FROM
    merchant_billing_profile
WHERE
    merchant_id = @merchant_id and is_default=1;
 
SELECT 
    config_value
INTO @domain FROM
    config
WHERE
    config_type = 'merchant_domain'
        AND config_key = @merchant_domain;
  
SELECT 
    coupon_code,
    descreption,
    type,
    percent,
    fixed_amount,
    start_date,
    end_date,
    `limit`,
    available
INTO @coupon_code , @c_descreption , @c_type , @c_percent , @c_fixed_amount , @c_start_date , @c_end_date , @c_limit , @c_available FROM
    coupon
WHERE
    coupon_id = @coupon_id
        AND start_date <= CURDATE()
        AND end_date >= CURDATE()
        AND (available > 0 OR `limit` = 0);


 
 if(@disable_online_payment=1)then
 SET @legal_complete=0;
 end if;

 if(@display_name<>'')then
 SET @company_name=@display_name;
 end if;
 
 if(@franchise_id>0)then
 SET @main_company_name=@company_name;
SELECT 
    franchise_name
INTO @company_name FROM
    franchise
WHERE
    franchise_id = @franchise_id;
    
    if(@settlements_to_franchise=1)then
	SELECT 
    COUNT(fee_detail_id)
INTO @pg_count FROM
    merchant_fee_detail
WHERE
    merchant_id = @merchant_id and franchise_id=@franchise_id
	AND is_active = 1;
 end if;
 
 end if;
 
 
SELECT 
    @domain AS 'merchant_domain',
    @display_url AS 'display_url',
    @merchant_user_id AS 'merchant_user_id',
    @merchant_id AS 'merchant_id',
    @event_name AS 'event_name',
    @venue AS 'venue',
    @description AS 'description',
    @event_from_date AS 'event_from_date',
    @event_to_date AS 'event_to_date',
    @tax_text AS 'tax_text',
    @tax AS 'tax',
    @main_company_name AS 'main_company_name',
    @mobile_capture AS 'mobile_capture',
    @age_capture AS 'age_capture',
    @company_name AS 'company_name',
    @merchant_type AS 'merchant_type',
    @legal_complete AS 'legal_complete',
    @image_path AS 'image_path',
    @banner_path AS 'banner_path',
    @event_name AS 'event_name',
    @merchant_address AS 'merchant_address',
    @business_email AS 'business_email',
    @business_contact AS 'business_contact',
    @seats_available AS 'seats_available',
    @coupon_code AS 'coupon_code',
    @c_descreption AS 'c_descreption',
    @c_type AS 'c_type',
    @c_percent AS 'c_percent',
    @c_fixed_amount AS 'c_fixed_amount',
    @c_start_date AS 'c_start_date',
    @c_end_date AS 'c_end_date',
    @c_limit AS 'c_limit',
    @c_available AS 'c_available',
    @coupon_id AS 'coupon_id',
    @duration AS 'duration',
    @occurence AS 'occurence',
    @event_type AS 'event_type',
    @is_active AS 'is_active',
    @capture_attendee_details AS 'capture_details',
    @short_url AS 'short_url',
    @custom_capture_detail AS 'custom_capture_detail',
    @custom_capture_title AS 'custom_capture_title',
    @settlements_to_franchise as 'pg_to_franchise',
    @franchise_id AS 'franchise_id',
    @vendor_id as 'vendor_id',
    @has_season_package as 'has_season_package',
    @terms as 'terms',
    @artist as 'artist',
    @artist_label as 'artist_label',
    @unit_type as 'unit_type',
    @privacy as 'privacy',
    @title as 'title',
    @payee_capture as 'payee_capture',
    @attendees_capture as 'attendees_capture',
    @stop_booking_time as 'stop_booking_time',
	@short_description as 'short_description';

END
