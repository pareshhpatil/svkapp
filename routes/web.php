<?php

header('Cache-Control: max-age=604800');

/*
  |--------------------------------------------------------------------------
  | Web Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register web routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | contains the "web" middleware group. Now create something great!
  |
 */

Route::get('/', 'HomeController@homepage')->name('home');
Route::get('merchant/register', 'HomeController@register')->name('home.register');
Route::get('login', 'HomeController@login')->name('home.login');
Route::get('billing-software', 'HomeController@billing')->name('home.billing');
Route::get('bulk-invoicing', 'HomeController@bulkinvoicing')->name('home.billing.feature.bulkinvoicing');
Route::get('payment-reminder', 'HomeController@paymentreminder')->name('home.billing.feature.paymentreminder');
Route::get('payment-collections', 'HomeController@paymentcollections')->name('home.paymentcollections');
Route::get('gst-filing-software', 'HomeController@gstfiling')->name('home.gstfiling');
Route::get('gst-reconciliation-software', 'HomeController@gstrecon')->name('home.gstrecon');
Route::get('payouts', 'HomeController@payouts')->name('home.payouts');
Route::get('expense-management-software', 'HomeController@expenses')->name('home.expenses');
Route::get('event-registration', 'HomeController@event')->name('home.event');
Route::get('venue-booking-software', 'HomeController@venuebooking')->name('home.booking');
Route::get('website-builder', 'HomeController@websitebuilder')->name('home.websitebuilder');
Route::get('online-form-builder', 'HomeController@formbuilder')->name('home.formbuilder');
Route::get('url-shortener', 'HomeController@urlshortener')->name('home.urlshortener');
Route::get('inventory-management-software', 'HomeController@inventorymanagement')->name('home.inventorymanagement');
Route::get('woocommerce-invoicing', 'HomeController@woocommerce_invoicing')->name('home.woocommerce.invoice');
Route::get('woocommerce-payment-gateway', 'HomeController@woocommerce_paymentgateway')->name('home.woocommerce.paymentgateway');
Route::get('e-invoicing', 'HomeController@einvoicing')->name('home.einvoicing');
Route::get('download-invoice-format', 'DownloadInvoiceFormatsController@index')->name('home.invoiceformats');
Route::get('invoice-template', 'DownloadInvoiceFormatsController@invoiceTemplate')->name('home.invoicetemplates');
Route::get('invoice-template-word', 'DownloadInvoiceFormatsController@invoiceTemplateWord')->name('home.wordinvoicetemplates');
Route::get('invoice-template-excel', 'DownloadInvoiceFormatsController@invoiceTemplateExcel')->name('home.excelinvoicetemplates');
Route::get('invoice-template-pdf', 'DownloadInvoiceFormatsController@invoiceTemplatePDF')->name('home.pdfinvoicetemplates');
Route::get('payment-link', 'HomeController@paymentLink')->name('home.paymentlink');

Route::get('sitemap.xml', 'SitemapController@index');

Route::get('download-isp-invoice-format', 'DownloadInvoiceFormatsController@IspInvoiceFormat')->name('home.ispinvoiceformat');
Route::get('download-sales-invoice-format', 'DownloadInvoiceFormatsController@SalesInvoiceFormat')->name('home.salesinvoiceformat');
Route::get('download-cable-invoice-format', 'DownloadInvoiceFormatsController@CableInvoiceFormat')->name('home.cableinvoiceformat');
Route::get('download-travel-ticket-invoice-format', 'DownloadInvoiceFormatsController@TravelTicketInvoiceFormat')->name('home.travelticketinvoiceformat');
Route::get('download-travel-car-invoice-format', 'DownloadInvoiceFormatsController@TravelCarInvoiceFormat')->name('home.travelcarinvoiceformat');
Route::get('download-consultant-freelancer-invoice-format', 'DownloadInvoiceFormatsController@ConsultantInvoiceFormat')->name('home.consultantinvoiceformat');
Route::get('download-housing-society-invoice-format', 'DownloadInvoiceFormatsController@SocietyInvoiceFormat')->name('home.housinginvoiceformat');

Route::get('free-invoice-generator', 'DownloadInvoiceFormatsController@FreeInvoiceFormat')->name('home.freeinvoicegenerator');
Route::get('gst-bill-format', 'DownloadInvoiceFormatsController@GSTBillFormat')->name('home.gstbillformat');

Route::get('download-proforma-invoice-format', 'DownloadInvoiceFormatsController@ProformaFormat')->name('home.proformainvoice');
Route::get('download-estimate-format', 'DownloadInvoiceFormatsController@EstimateFormat')->name('home.estimate');


Route::any('export-invoice-formats', 'DownloadInvoiceFormatsController@download')->middleware('throttle:5,1');

Route::get('billing-software-for-cable-operator', 'HomeController@cable')->name('home.industry.cable');
Route::get('billing-software-for-franchise-business', 'HomeController@franchise')->name('home.industry.franchise');
Route::get('billing-software-for-isp-telcos', 'HomeController@isp')->name('home.industry.isp');

Route::get('billing-software-for-school', 'HomeController@education')->name('home.industry.education');
Route::get('billing-software-for-travel-and-tour-operator', 'HomeController@travelntour')->name('home.industry.travelntour');
Route::get('event-registration-for-entertainment-event', 'HomeController@entertainmentevent')->name('home.industry.entertainmentevent');
Route::get('event-registration-for-hospitality-event', 'HomeController@hospitalityevent')->name('home.industry.hospitalityevent');
Route::get('venue-booking-management-for-health-and-fitness', 'HomeController@venuebookingfitness')->name('home.industry.bookingfitness');
Route::get('billing-and-venue-booking-software-for-housing-societies', 'HomeController@societybooking')->name('home.industry.societybooking');
Route::get('milk-dairy-billing-software', 'HomeController@milkdairy')->name('home.industry.milkdairy');

//Route::get('short-url-solution-for-utility-provider', 'HomeController@utilityprovider')->name('home.industry.utilityprovider');
Route::get('short-url-solution-for-enterprise', 'HomeController@enterprises')->name('home.industry.enterprises');
Route::get('invoicing-software-for-freelancers-and-consultants', 'HomeController@freelancers')->name('home.industry.freelancers');

Route::get('pricing', 'HomeController@pricing')->name('home.pricing');
Route::get('billing-software-pricing', 'HomeController@billingpricing')->name('home.pricing.billing');
Route::get('event-registration-pricing', 'HomeController@eventpricing')->name('home.pricing.event');
Route::get('venue-booking-software-pricing', 'HomeController@bookingcalendarpricing')->name('home.pricing.bookingcalendar');
Route::get('website-builder-pricing', 'HomeController@websitebuilderpricing')->name('home.pricing.websitebuilder');
Route::get('url-shortener-pricing', 'HomeController@urlshortenerpricing')->name('home.pricing.urlshortener');
Route::get('payment-gateway-charges', 'HomeController@paymentgatewaycharges')->name('home.pricing.onlinetransactions');

Route::get('package/{link}', 'UserController@paymentLogin');

Route::get('terms', 'HomeController@terms')->name('home.footer.terms');
Route::get('privacy', 'HomeController@privacy')->name('home.footer.privacy');
Route::get('terms-popup', 'HomeController@termspopup')->name('home.footer.terms.popup');
Route::get('terms-popup/{merchant_id}', 'HomeController@termspopup')->name('home.footer.terms.popup.merchant');
Route::get('privacy-popup', 'HomeController@privacypopup')->name('home.footer.privacy.popup');
Route::get('privacy-popup/{merchant_id}', 'HomeController@privacypopup')->name('home.footer.privacy.popup.merchant');
Route::get('partner', 'HomeController@partner')->name('home.footer.partner');
Route::get('in-the-news', 'HomeController@inthenews')->name('home.footer.inthenews');
Route::get('aboutus', 'HomeController@aboutus')->name('home.footer.aboutus');
Route::get('contactus', 'HomeController@contactus')->name('home.footer.contactus');
Route::get('workfromhome', 'HomeController@workfromhome')->name('home.footer.workfromhome');
Route::get('customerstories', 'HomeController@customerstories')->name('home.footer.customerstories');
Route::get('tradeindia', 'HomeController@tradeindia')->name('home.footer.tradeindia');
Route::get('boost360', 'HomeController@boost360')->name('home.footer.boost360');
Route::get('yatra', 'HomeController@partnerPage');
Route::get('nsrcel', 'HomeController@partnerPage');
Route::get('taxprint', 'HomeController@partnerPage');

Route::get('moneycontrol/easybiz/{link}', 'UserController@easybizsetpassword');
Route::get('moneycontrol/easybiz', 'UserController@easybizlanding');
Route::get('collect-it', 'HomeController@collectit')->name('home.collectit');
Route::get('collect-it-billing-app', 'HomeController@collectit')->name('home.collectit.billing');

Route::get('getintouch/{subject}', 'HomeController@getintouch');
Route::get('404', 'HomeController@pagenotfound');
Route::get('lp/{page}/{company}', 'HomeController@landingpage');


Route::get('/m/{url}', 'MerchantPagesController@merchantIndex')->name('mpages.home');
Route::get('/m/{url}/paymybill', 'MerchantPagesController@merchantBills')->name('mpages.paymybill');
Route::post('/m/{url}/paymybill', 'MerchantPagesController@merchantBills')->name('merchant.get.bills');
Route::get('/m/{url}/payment-link', 'MerchantPagesController@merchantDirectPay')->name('mpages.paymentlink');
Route::get('/m/{url}/payment-link/{link}', 'MerchantPagesController@merchantDirectPay');
Route::get('/m/{url}/policies', 'MerchantPagesController@merchantPolicies')->name('mpages.policies');
Route::get('/m/{url}/aboutus', 'MerchantPagesController@merchantAboutus')->name('merchant.aboutus');
Route::get('/m/{url}/contactus', 'MerchantPagesController@merchantContactus')->name('merchant.contactus');
Route::post('/m/connectmail', 'MerchantPagesController@connectMail')->name('connect.mail');


Route::redirect('/m/{url}/paymentlink', '/m/{url}/payment-link', 301);
Route::redirect('/m/{url}/directpay', '/m/{url}/payment-link', 301);
Route::redirect('/m/{url}/directpay/{link}', '/m/{url}/payment-link/{link}', 301);
Route::redirect('/m/{url}/paymybills', '/m/{url}/paymybill', 301);

//Route::any('oauth2callback', 'HomeController@googleLogin');
Route::get('googlelogintoken/{token}/{service_id}', 'UserController@googleLogin');

Route::redirect('/patron/register', '/merchant/register', 301);

Route::redirect('/couponing-system', '/billing-software', 301);
Route::redirect('/expenses', '/expense-management-software', 301);
Route::redirect('/booking-calendar', '/venue-booking-software', 301);
Route::redirect('/event-management-software', '/event-registration', 301);
Route::redirect('/invoicing-system', '/billing-software', 301);
Route::redirect('/invoicing-software', '/billing-software', 301);
Route::redirect('/reseller', '/partner', 301);
Route::redirect('/work-from-home', '/partner', 301);
Route::redirect('/booking-calendar-pricing', '/venue-booking-software-pricing', 301);
Route::redirect('/billing-software-for-education', '/billing-software-for-school', 301);
Route::redirect('/billing-software-for-educational-institute', '/billing-software-for-school', 301);
Route::redirect('/gst-filing', '/gst-filing-software', 301);
Route::redirect('/faq', 'https://helpdesk.swipez.in/help', 301);
Route::redirect('/sitemap', '/', 301);


Route::get('merchant/getting-started', 'GettingStarted@index')->name('gettingstarted.index');

Route::post('/merchant/registersave', 'GettingStarted@merchantRegister');

/*
  Route::get('/', HomeController@homepage () {
  return view('home/index');
  });
 */

Route::post('/autocollect/subscription/payment', 'AutocollectController@paymentstatus');

Route::group(['prefix' => 'merchant', 'middleware' => 'auth'], function () {
  Route::get('beneficiary/create', 'PayoutController@beneficiarycreate')->name('merchant.payout.create');
  Route::get('beneficiary/viewlist', 'PayoutController@beneficiarylist')->name('merchant.payout.beneficiarylist');
  Route::get('payout/transfer', 'PayoutController@transfer')->name('merchant.payout.transfer');
  Route::get('payout/nodal', 'PayoutController@nodal')->name('merchant.payout.nodal');
  Route::any('cashgram/list', 'PayoutController@cashgramlist')->name('merchant.payout.cashgram.list');
  Route::get('cashgram/create', 'PayoutController@createcashgram')->name('merchant.payout.cashgram.create');
  Route::post('cashgram/save', 'PayoutController@cashgramsave');
  Route::get('cashgram/delete/{id}', 'PayoutController@deletecashgram');

  Route::get('beneficiary/delete/{id}', 'PayoutController@deleteBeneficiary');
  Route::any('payout/transaction', 'PayoutController@transactions')->name('merchant.payout.transactions');
  Route::post('payout/transfersave', 'PayoutController@transfersave');
  Route::post('beneficiary/save', 'PayoutController@beneficiarysave');
  Route::post('payout/withdraw', 'PayoutController@withdraw');


  Route::get('autocollect/plan/create', 'AutocollectController@planCreate')->name('merchant.autocollect.create.plan');;
  Route::get('autocollect/subscription/create', 'AutocollectController@subscriptionCreate')->name('merchant.autocollect.create.subscription');
  Route::get('autocollect/plans', 'AutocollectController@planList')->name('merchant.autocollect.plans');
  Route::get('autocollect/subscriptions', 'AutocollectController@subscriptionList')->name('merchant.autocollect.subscriopnlist');
  Route::post('autocollect/plan/save', 'AutocollectController@planSave');
  Route::post('autocollect/subscription/save', 'AutocollectController@subscriptionSave');
  Route::get('autocollect/plan/delete/{id}', 'AutocollectController@deletePlan');
  Route::get('autocollect/subscription/delete/{id}', 'AutocollectController@deleteSubscription');
  Route::any('autocollect/transactions', 'AutocollectController@transactionList')->name('merchant.autocollect.transactionlist');

  Route::get('expense/create', 'ExpenseController@create')->name('create.expense');
  Route::get('expense/po/create', 'ExpenseController@createpo')->name('create.po');
  Route::post('expense/save', 'ExpenseController@expensesave');
  Route::post('po/save', 'ExpenseController@posave');
  Route::post('expense/updatesave', 'ExpenseController@updatesave');
  Route::post('expense/bulkupdatesave', 'ExpenseController@bulkupdatesave');
  Route::post('expense/updatepayment', 'ExpenseController@updatepayment');
  Route::any('expense/viewlist/{type}', 'ExpenseController@viewlist')->name('list.expense');
  Route::get('expense/view/{id}', 'ExpenseController@view')->name('merchant.expense.view');
  Route::get('expense/resend/{id}', 'ExpenseController@resend');
  Route::get('expense/update/{id}', 'ExpenseController@update')->name('update.expense');;
  Route::any('vendor/bulklist/expense/{link}', 'ExpenseController@bulklist');
  Route::any('vendor/expense/{link}', 'ExpenseController@bulkexpense');
  Route::get('expense/bulkview/{id}', 'ExpenseController@bulkview');
  Route::get('expense/bulkupdate/{id}', 'ExpenseController@bulkupdate');
  Route::get('expense/convert/{id}', 'ExpenseController@convertexpense');
  Route::any('expense/pending', 'ExpenseController@pendingExpense')->name('merchant.expense.pending');
  Route::get('expense/approve/{id}', 'ExpenseController@approveexpense');
  Route::get('expense/bulk/save', 'ExpenseController@expensebulksave');
  Route::get('expense/category/create', 'ExpenseController@createcategory');
  Route::post('expense/categorysave', 'ExpenseController@categorysave');
  Route::get('expense/category', 'ExpenseController@listcategory')->name('list.category.expense');
  Route::get('expense/category/update/{id}', 'ExpenseController@updatecategory');
  Route::post('expense/categoryupdatesave', 'ExpenseController@categoryupdatesave');
  Route::get('expense/department/create', 'ExpenseController@createdepartment');
  Route::post('expense/departmentsave', 'ExpenseController@departmentsave');
  Route::get('expense/department', 'ExpenseController@listdepartment')->name('list.department.expense');
  Route::get('expense/department/update/{id}', 'ExpenseController@updatedepartment');
  Route::post('expense/departmentupdatesave', 'ExpenseController@departmentupdatesave');
  Route::get('expense/{table}/delete/{id}', 'ExpenseController@deleteMaster');
  Route::get('expense/exportformat', 'ExpenseController@exportFormat');



  Route::get('creditnote/create', 'CreditDebitNoteController@createCreditNote')->name('merchant.creditnote.create');
  Route::get('debitnote/create', 'CreditDebitNoteController@createDebitNote')->name('merchant.debitnote.create');
  Route::get('creditnote/update/{id}', 'CreditDebitNoteController@creditnoteupdate')->name('merchant.creditnote.update');
  Route::get('debitnote/update/{id}', 'CreditDebitNoteController@debitnoteupdate')->name('merchant.debitnote.update');;
  Route::post('creditnote/save', 'CreditDebitNoteController@creditnotesave');
  Route::post('creditnote/updatesave', 'CreditDebitNoteController@updatesave');
  Route::any('creditnote/viewlist', 'CreditDebitNoteController@creditviewlist')->name('merchant.creditnote.credit-note.list');
  Route::any('debitnote/viewlist', 'CreditDebitNoteController@debitviewlist')->name('merchant.debitnote.debit-note.list');
  Route::get('{type}note/view/{id}', 'CreditDebitNoteController@view')->name('merchant.debitnote.view');
  Route::get('{type}note/download/{id}', 'CreditDebitNoteController@download');
  Route::get('tallyexport/download/{id}', 'CreditDebitNoteController@tallyfiledownload');

  //Lookup controller made for Trade India employees to check registrations of merchants
  Route::match(['get', 'post'], 'lookup/registration', 'LookupController@registration');

  Route::get('getting-started/welcome', 'GettingStarted@welcome')->name('gettingstarted.welcome');
  Route::get('getting-started/features', 'GettingStarted@featurelist')->name('gettingstarted.features');
  Route::get('getting-started/set-industry', 'GettingStarted@industry')->name('gettingstarted.industry');

  Route::post('preferencesave', 'GettingStarted@preferencesave');
  Route::post('industrysave', 'GettingStarted@industrysave');
  Route::get('benefits', 'BenefitsController@index')->name('benefitslist');
  Route::post('benefit/apply', 'BenefitsController@apply');
  Route::get('dashboard/remindmelater/{type}', 'GettingStarted@remindmelater');

  Route::any('invoiceformat/create/{link}', 'InvoiceFormatController@create')->name('create.invoiceformat');
  Route::any('invoiceformat/update/{link}', 'InvoiceFormatController@update')->name('update.invoiceformat');
  Route::post('invoiceformat/save/', 'InvoiceFormatController@save');
  Route::post('invoiceformat/savePluginValue/', 'InvoiceFormatController@savePluginValue');

  Route::any('invoice/create', 'InvoiceController@create')->name('create.invoice');
  Route::any('invoice/create/{type}', 'InvoiceController@create')->name('create.invoice.type');
  Route::any('invoice/update/{link}', 'InvoiceController@update')->name('update.invoice');
  Route::any('invoice/update/{link}/{type}', 'InvoiceController@update')->name('update.invoice.type');
  Route::any('subscription/update/{link}', 'InvoiceController@update')->name('update.invoice.link');
  Route::any('estimate/create/{type}', 'InvoiceController@estimateSubscription');
  Route::get('invoice/revision/{id}', 'InvoiceController@revision');

  Route::redirect('subscription/create', '/merchant/invoice/create/subscription', 301);


  Route::get('unit-type/index', 'UnitTypeController@index')->name('merchant.unit-type.list');
  Route::post('unit-type/store', 'UnitTypeController@store');
  Route::get('unit-type/delete/{id}', 'UnitTypeController@destroy');
  Route::post('unit-type/update', 'UnitTypeController@update');

  Route::get('product-category/index', 'ProductCategoryController@index')->name('merchant.product-category.list');
  Route::post('product-category/store', 'ProductCategoryController@store');
  Route::get('product-category/delete/{id}', 'ProductCategoryController@destroy');
  Route::post('product-category/update', 'ProductCategoryController@update');

  Route::get('product-attribute/index', 'ProductAttributeController@index')->name('merchant.product-attribute.list');
  Route::get('product-attribute/create', 'ProductAttributeController@create')->name('merchant.product-attribute.create');
  Route::post('product-attribute/store', 'ProductAttributeController@store');
  Route::get('product-attribute/delete/{id}', 'ProductAttributeController@destroy');
  Route::get('product-attribute/edit/{id}', 'ProductAttributeController@edit')->name('merchant.product-attribute.update');
  Route::post('product-attribute/update', 'ProductAttributeController@update');

  Route::get('product/createnew', 'ProductController@create')->name('merchant.product.createnew');
  Route::post('product/store', 'ProductController@store');
  Route::get('product/delete/{id}', 'ProductController@destroy');
  Route::get('product/edit/{id}', 'ProductController@edit')->name('product.update');
  Route::post('product/update', 'ProductController@update')->name('merchant.product.update');
  Route::get('product/getAllUnits/{unit_type}', 'ProductController@getAllUnits');
  Route::get('product/index', 'ProductController@index')->name('inventory');
  Route::get('product/updateProductDB', 'ProductController@updateProductDB');
  Route::get('product/{type}/{id}', 'ProductController@show')->name('product.show');
  Route::any('product/dashboard', 'ProductController@dashboard')->name('merchant.product.dashboard');
  //Route::post('product/mapFiters', 'ProductController@mapFiters');

  Route::get('product/getExpenseList/{exp_type}', 'ProductController@getExpenseList');
  Route::post('uppyfileupload/uploadImage', 'UppyFileUploadController@uploadImage');
  Route::post('uppyfileupload/uploadImage/{type}', 'UppyFileUploadController@uploadImage');

  Route::get('hsn-sac-code/index', 'HsnsaccodeController@index');
  Route::get('hsn-sac-code/create', 'HsnsaccodeController@create');
  Route::post('hsn-sac-code/store', 'HsnsaccodeController@store');
  Route::get('hsn-sac-code/delete/{id}', 'HsnsaccodeController@destroy');
  Route::get('hsn-sac-code/edit/{id}', 'HsnsaccodeController@edit');
  Route::post('hsn-sac-code/update', 'HsnsaccodeController@update');

  Route::get('master/{type}/list', 'MasterController@list');
  Route::get('master/{type}/create', 'MasterController@create');
  Route::post('master/{type}/save', 'MasterController@save');
  Route::get('master/{type}/delete/{id}', 'MasterController@delete');
  Route::get('master/{type}/update/{id}', 'MasterController@update');
  Route::post('master/{type}/updatesave', 'MasterController@updatesave');
  Route::get('collect-payments',  'CompanyProfileController@collect_payment_landingpage')->name("collectlandingpage");
  Route::get('pay-my-bills',  'CompanyProfileController@pay_your_bills')->name("payyourbills");
  Route::any('einvoice/list',  'EinvoiceController@einvoice')->name("einvoicelist");
  Route::get('einvoice/view/{id}',  'EinvoiceController@view')->name("einvoiceview");
  Route::get('einvoice/view/{id}/{type}',  'EinvoiceController@view')->name("einvoicedownload");
  Route::get('einvoice/recreate/{id}',  'EinvoiceController@recreateeInvoice');
  Route::get('einvoice/delete/{id}',  'EinvoiceController@deleteeInvoice');
  Route::get('einvoice/cancel/{id}',  'EinvoiceController@canceleInvoice');
  Route::get('einvoice/errors/{id}',  'EinvoiceController@errorseInvoice');


  //added by ganesh
  Route::get('invoice/view/{link}', 'InvoiceController@view');
  Route::get('invoice/viewg702/{link}', 'InvoiceController@view_g702');
  Route::get('invoice/viewg703/{link}', 'InvoiceController@view_g703');
  Route::get('invoice/document/download/{link}', 'InvoiceController@downloadSingle');
  Route::get('invoice/document/download/all/{link}', 'InvoiceController@downloadZip');
  Route::get('invoice/document/{link}', 'InvoiceController@documents');
  Route::get('invoice/document/{link}/{name}', 'InvoiceController@documents');
  Route::get('invoice/document/{link}/{parent}/{sub}', 'InvoiceController@documents');
  Route::get('invoice/document/{link}/{parent}/{sub}/{name}', 'InvoiceController@documents');

  
  Route::get('invoice/bulkview/{link}', 'InvoiceController@bulkview');
  Route::get('invoice/download/{link}', 'InvoiceController@download');
  Route::get('invoice/download/{link}/{id}', 'InvoiceController@download');
  Route::get('invoice/download/{link}/{id}/{type}', 'InvoiceController@download');

  Route::get('invoiceformat/choose-design/{from}/{link}', 'InvoiceFormatController@chooseDesign')->name('choose.design.invoiceformat');
  Route::get('invoiceformat/choose-color/{from}/{design}/{color}/{link}', 'InvoiceFormatController@chooseColor')->name('choose.color.invoiceformat');

  //project screen CRUD routes
  Route::get('project/list', 'MasterController@projectlist'); 
  Route::get('project/delete/{link}', 'MasterController@projectdelete');
  Route::get('project/create', 'MasterController@projectcreate');
  Route::post('project/store', 'MasterController@projectsave');
  Route::get('project/edit/{link}', 'MasterController@projectupdate');
  Route::post('project/updatestore', 'MasterController@projectupdatestore');
  //code
  Route::get('code/list/{id}', 'MasterController@codeList'); 
  Route::get('code/getlist/{id}', 'MasterController@getbillcode'); 
  Route::get('code/delete/{project_id}/{link}', 'MasterController@projectCodeDelete');
  Route::any('/billcode/update/', 'ContractController@billcodeupdate');
  //contract
  Route::any('contract/create', 'ContractController@create')->name('create.contract');
  Route::any('contract/create{version}', 'ContractController@create')->name('create.contractv2');
  Route::any('contract/update/{link}', 'ContractController@update')->name('update.contract');
  Route::any('contract/save', 'ContractController@save')->name('save.contract');
  Route::any('contract/saveV4', 'ContractController@saveV4')->name('save.contractV4');
  Route::any('contract/saveV5', 'ContractController@saveV5')->name('save.contractV4');
  Route::any('contract/list', 'ContractController@list')->name('list.contract');
  Route::any('contract/delete/{link}', 'ContractController@delete')->name('delete.contract');
  Route::any('contract/getProjectDetails/{project_id}', 'ContractController@getprojectdetails')->name('getprojectdetails.contract');
  Route::any('contract/updatesave/', 'ContractController@updatesave')->name('updatesave.contract');
  Route::any('contract/updatesaveV4/', 'ContractController@updatesaveV4')->name('updatesave.contractV4');
  Route::any('contract/updatesaveV5/', 'ContractController@updatesaveV5')->name('updatesave.contractV5');
  Route::any('/billcode/create/', 'ContractController@billcodesave')->name('billcodesave.contract');
  
  //order
  Route::any('order/create', 'OrderController@create')->name('create.order');
  Route::any('order/create{version}', 'OrderController@create')->name('create.orderv2');
  Route::any('order/update/{link}', 'OrderController@update')->name('update.order');
  Route::any('order/approved/{link}', 'OrderController@approved')->name('approved.order');
  Route::any('order/save', 'OrderController@save')->name('save.order');
  Route::any('order/list', 'OrderController@list')->name('list.order');
  Route::any('order/delete/{link}', 'OrderController@delete')->name('delete.order');
  Route::any('order/approve/', 'OrderController@approve')->name('approve.order');
  Route::any('order/getProjectDetails/{project_id}', 'OrderController@getprojectdetails')->name('getprojectdetails.order');
  Route::any('order/updatesave/', 'OrderController@updatesave')->name('updatesave.order');
  Route::any('/billcode/create/', 'OrderController@billcodesave')->name('billcodesave.order');
   
  //covering note
  Route::any('getSingleCoverNote/{covering_note_id}', 'CoveringNote@getSingleCoverNote');
  //region setting
  Route::get('/regionSettings', 'RegionSettingController@index')->name('merchant.region-setting.index');
  Route::any('/setting/region/save/', 'RegionSettingController@saveChanges');

});

Route::group(['prefix' => 'patron'], function () {
  Route::get('paymentlink/view/{payment_request_id}', 'PaymentLinkController@view');
  Route::get('paymentlink/payeeinfo/{payment_request_id}', 'PaymentLinkController@payeeinfo');
  Route::get('paymentlink/receipt/{transaction_id}', 'PaymentLinkController@paymentReceipt');
  Route::get('paymentlink/failed/{transaction_id}', 'PaymentLinkController@paymentReceipt');
  Route::get('paymentlink/reportlink/{payment_request_id}', 'PaymentLinkController@reportLink');
  Route::post('paymentlink/reportthankyou', 'PaymentLinkController@reportUnsubscribe');
  Route::get('paymentlink/build/{payment_request_id}', 'PaymentLinkController@build');
  //patron added by ganesh
  Route::get('invoice/view/{link}/{type}', 'InvoiceController@patronView703');
  Route::get('invoice/view/{link}', 'InvoiceController@patronView');
  Route::get('invoice/document/download/{link}', 'InvoiceController@downloadSingle');
  Route::get('invoice/document/download/all/{link}', 'InvoiceController@downloadZip');
  Route::get('invoice/download/{link}', 'InvoiceController@downloadPatron');
  Route::get('invoice/download/{link}/{id}', 'InvoiceController@downloadPatron');
  Route::get('invoice/download/{link}/{id}/{type}', 'InvoiceController@downloadPatron');
  Route::get('invoice/document/{link}', 'InvoiceController@documentsPatron');
  Route::get('invoice/document/{link}/{name}', 'InvoiceController@documentsPatron');
  Route::get('invoice/document/{link}/{parent}/{sub}', 'InvoiceController@documentsPatron');
  Route::get('invoice/document/{link}/{parent}/{sub}/{name}', 'InvoiceController@documentsPatron');


});

Route::get('invoice/sendmail/{link}/{subject}', 'InvoiceController@sendEmail');
Route::post('merchant/register/validate', 'UserController@sendOTP')->middleware('throttle:5,1');
Route::post('merchant/register/validateotp', 'UserController@validateOTP')->middleware('throttle:5,1');

Route::post('login/passwordsave', 'UserController@passwordsave')->middleware('throttle:5,1');

Auth::routes(['register' => false]);
Route::get('register', 'HomeController@register')->name('register');


Route::get('/error', 'HomeController@legacyerror');
Route::get('/error/{type}', 'HomeController@legacyerror');
Route::get('/accessdenied', 'HomeController@accessdenied');
Route::get('/home', 'HomeController@homepage');
Route::get('/logout', 'UserController@logoutUser');
Route::get('/login/token/{token}', 'UserController@tokenLogin');
Route::get('/login/token/{token}/{service_id}', 'UserController@tokenLogin');
Route::any('/zohoredirect', 'UserController@zohoredirect');
//Route::get('/login/forgot', 'UserController@forgotpassword');

Route::post('/jwt/verify', 'JwtController@verify');
Route::post('/jwt/verify/{type}', 'JwtController@verify');


Route::get('faq/{param1}', 'HelpdeskController@handle');
Route::get('faq/{param1}/{param2}', 'HelpdeskController@handle');


Route::get('loyalty/{merchant}/login', 'LoyaltyController@loyaltylogin');
Route::get('loyalty/{merchant}/home', 'LoyaltyController@home');
Route::get('loyalty/{merchant}/register', 'LoyaltyController@register');
Route::get('loyalty/{merchant}/otp', 'LoyaltyController@otp');

Route::get('format/invoice', 'DownloadInvoiceFormatsController@tempinvoice');

Route::any('webhook/cashfree/payout', 'PayoutController@cashfreePayout');
Route::any('webhook/payoneer/status', 'PayoutController@payoneerWebhook');
Route::any('webhook/payoneer/account/status', 'IntegrationSetupController@payoneerAccount');

Route::get('merchant/chatnow', 'HomeController@chatnow');

Route::get('/site/company-profile/{id}', 'CompanyProfileController@profile')->middleware("auth")->name('merchant.company-profile.home');
Route::post('/site/merchant/update_toggle', 'CompanyProfileController@updateToggle')->middleware("auth");
Route::post('/merchant/update_toggle', 'CompanyProfileController@updateToggle')->middleware("auth");
Route::post('/site/update-home', 'CompanyProfileController@updateHome')->middleware("auth");
Route::post('/site/set-complete-company-page', 'CompanyProfileController@setComplatedCompanyPage')->middleware("auth");

Route::get('/merchant/integrations/stripe', 'IntegrationSetupController@stripeConnect')->name('stripe.connect')->middleware("auth");
Route::get('/merchant/integrations/payoneer', 'IntegrationSetupController@payoneerConnect')->name('payoneer.connect')->middleware("auth");
Route::get('/merchant/integrations/payoneer/response', 'IntegrationSetupController@payoneerResponse')->name('payoneer.response')->middleware("auth");
Route::get('merchant/integrations', 'IntegrationSetupController@index')->name('merchant.integrations')->middleware("auth");
Route::post('merchant/integration-setup/getSetupDetails', 'IntegrationSetupController@getSetupDetails')->middleware("auth");
Route::any('merchant/stripe-setup', 'GettingStarted@integrations')->name('merchant.stripe-setup')->middleware("auth");
Route::any('merchant/stripe-setup/save', 'IntegrationSetupController@stripeSave')->name('merchant.stripe-setup-save')->middleware("auth");

Route::get('/merchant/gst/reconciliation', 'GstController@gstr2aLanding')->name('gstcontroller.gstr2alanding')->middleware("auth");
Route::get('/merchant/gst/reconciliation/landingData/{token}', 'GstController@gstr2aLandingData')->name('gstcontroller.gstr2alandingdata')->middleware("auth");
Route::any('/merchant/gst/reconciliation/summary/{id}', 'GstController@gstr2aSummary')->name('gstcontroller.gstr2asummary')->middleware("auth");
Route::any('/merchant/gst/reconciliation/delete/{id}', 'GstController@deleteJob')->name('gstcontroller.deletejob')->middleware("auth");
Route::any('/merchant/gst/reconciliation/detail/{id}/{supplier}/{status}', 'GstController@gstr2aDetail')->name('gstcontroller.gstr2adetail')->middleware("auth");
Route::post('/merchant/gst/reconciliation/CreateJob', 'GstController@gstr2aCreateJob')->middleware("auth");
Route::get('/merchant/gst/reconciliation/detailData/{id}/{supplier}/{status}', 'GstController@gstr2aDetailData')->name('gstcontroller.gstr2adetaildata')->middleware("auth");
Route::post('/merchant/gst/reconciliation/actionbar', 'GstController@gstr2aActionBar')->middleware("auth");
Route::get('/merchant/gst/reconciliation/ParticulardetailData/{id}', 'GstController@gstr2aParticularDetailData')->name('gstcontroller.gstr2aparticulardetaildata')->middleware("auth");
Route::get('/merchant/gst/reconciliation/exportDetailData/{id}/{supplier}/{status}', 'GstController@gstr2aExportDetailData')->name('gstcontroller.gstr2aexportdetaildata')->middleware("auth");
Route::post('/merchant/gst/reconciliation/validateConnection/', 'GstController@validateConnection')->name('gstcontroller.validateconnection')->middleware("auth");

Route::get('best-free-business-tools', 'FreeBusinessToolsController@free_business_tools')->name('home.freebiztools');
Route::get('online-gst-calculator', 'FreeBusinessToolsController@Gst_Calculator');
Route::get('free-online-simple-interest-calculator', 'FreeBusinessToolsController@simple_interest_calculator');
Route::get('online-compound-interest-calculator', 'FreeBusinessToolsController@compound_interest_calculator');
Route::get('free-online-gst-lookup', 'FreeBusinessToolsController@gst_lookup');
Route::post('free-online-gst-lookup', 'FreeBusinessToolsController@gst_lookup')->middleware("Captcha");
Route::get('online-hsn-code-lookup', 'FreeBusinessToolsController@hsn_code_lookup');
Route::get('payment-page', 'HomeController@paymentpage')->name('home.feature.custom.payment.page');
Route::get('online-invoicing', 'HomeController@invoicing')->name('home.billing.feature.onlineinvoicing');
Route::get('quickbooks-alternative', 'HomeController@quickbooks')->name('home.alternative.quickbooks');
Route::get('partner-benefits', 'HomeController@benifits')->name('home.partnerbenefits');
Route::get('integrations', 'HomeController@integration_landing_page')->name('home.integrations');
Route::get('/partner-benefits/razorpay', 'HomeController@razorpay');
Route::get('/partner-benefits/cashfree', 'HomeController@cashfree');
Route::get('cashfree-payout', 'HomeController@cashfree_payout');
Route::get('/partner-benefits/stripe', 'HomeController@stripe');
Route::get('/partner-benefits/payoneer', 'HomeController@payoneer');
Route::get('/partner-benefits/amazon-web-services', 'HomeController@amazon');
Route::get('/partner-benefits/lending-kart', 'HomeController@lending_kart');
Route::get('/partner-benefits/boot-360', 'HomeController@boot');
Route::get('/partner-benefits/dalal-street', 'HomeController@dalal_street');
Route::get('/partner-benefits/scatter', 'HomeController@scatter_content');
Route::get('/partner-benefits/sms', 'HomeController@swipez_sms');
Route::get('/partner-benefits/website-builder', 'HomeController@website_builder');

Route::post('/setu/webhook/notifications', 'MerchantPagesController@setu');
Route::post('payment-gateway', 'MerchantPagesController@merchantPaymentGateway')->name('mpages.paymentgateway');
Route::post('upipgtrack', 'MerchantPagesController@upipgtrack')->name('mpages.upipgtrack');

Route::get('/patron/booking/cancellation/{transaction_id}', 'BookingCalendarController@landing');
Route::get('/patron/booking/cancellation/{transaction_id}/confirm', 'BookingCalendarController@landingconfirm');

Route::post('/patron/booking/cancellation/{transaction_id}/reciept', 'BookingCalendarController@landingreciept');
Route::any('/merchant/transaction/booking/cancellations', 'BookingCalendarController@cancellationlist')->middleware("auth");
Route::any('/merchant/transaction/booking/cancellations/list/{from}/{to}/{status}', 'BookingCalendarController@cancellationlistData')->middleware("auth");
Route::any('/merchant/transaction/booking/cancellations/denyrefund/{id}', 'BookingCalendarController@cancellationRefund')->middleware("auth");
Route::any('/merchant/transaction/booking/cancellations/refund/{id}', 'BookingCalendarController@cancellationlistDenyRefund')->middleware("auth");
