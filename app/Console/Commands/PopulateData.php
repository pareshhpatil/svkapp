<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\PaymentRequest;
use App\PaymentTransaction;
use App\XWayTransaction;
use App\MerchantBillingProfile;
use App\Customer;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

class PopulateData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'populate:data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Populate database with dummy data.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */

    public function getInvoiceNumber($invoice_number){
        $invoice = DB::select("select generate_sequence(".$invoice_number->auto_invoice_id.") as value");
        return array_shift($invoice)->value;
    }

    public function handle()
    {
        $counter = 1;
        $dataNumber = 2;
        $merchantss = explode(',',env('MERCHANTS'));
        $merchants = DB::table('merchant')->whereIn('merchant_id', $merchantss)->get();
        foreach($merchants as $merchant){
            $merchant_billing_profile = factory(MerchantBillingProfile::class, $dataNumber)->create([
                'merchant_id' => $merchant->merchant_id,
                'created_by' => $merchant->merchant_id,
                'last_update_by' => $merchant->merchant_id,
            ]);
            $merchant_tax = DB::table('merchant_tax')->where('merchant_id', $merchant->merchant_id)->where('percentage', 5)->first();
            $template = DB::table('invoice_template')->where('merchant_id', $merchant->merchant_id)->first();
            $invoice_column = DB::table('invoice_column_metadata')->where('template_id', $template->template_id)->where('column_name', 'Invoice No.')->first();
            $customers = factory(Customer::class,$dataNumber)->create([
                'merchant_id' => $merchant->merchant_id,
                'created_by' => $merchant->user_id,
                'last_update_by' => $merchant->user_id
            ]);
            DB::table('customer_column_metadata')->updateOrInsert([
                'merchant_id' => $merchant->merchant_id,
                'column_datatype' => 'gst',
                'position' => 'L',
                'column_name' => 'GST number',
                'column_type' => 'column',
                'is_active' => 1,
                'created_by' => $merchant->merchant_id,
                'last_update_by' => $merchant->merchant_id,
            ]);
            $invoice_number = DB::table('merchant_auto_invoice_number')->where('merchant_id', $merchant->merchant_id)->first();
            foreach($customers as $customer){
                
                DB::table('customer_column_values')->insert([
                    'customer_id' => $customer->customer_id,
                    'column_id' => 62,
                    'value' => '18AABCU9603R1ZM',
                    'is_active' => 1,
                    'created_by' => $merchant->user_id,
                    'last_update_by' => $merchant->user_id
                ]);
                $paymentRequests = factory(PaymentRequest::class,$dataNumber)->create([
                    'user_id' => $merchant->user_id,
                    'invoice_number' => $this->getInvoiceNumber($invoice_number),
                    'template_id' => $template->template_id,
                    'merchant_id' => $merchant->merchant_id,
                    'customer_id' => $customer->customer_id,
                    'created_by' => $merchant->user_id,
                    'last_update_by' => $merchant->user_id,
                ]);

                foreach($paymentRequests as $paymentRequest){
                    DB::table('invoice_tax')->insert([
                        'payment_request_id' => $paymentRequest->payment_request_id,
                        'tax_id' => $merchant_tax->tax_id,
                        'tax_percent' => 5,
                        'applicable' => $paymentRequest->basic_amount,
                        'tax_amount' => $paymentRequest->tax_amount,
                        'is_active' => 1,
                        'created_by' => $merchant->user_id,
                        'last_update_by' => $merchant->user_id,
                    ]);
                    
                    $item_name = Str::random(8);
                    $product = DB::table('merchant_product')->where('merchant_id', $merchant->merchant_id)->where('product_name', $item_name)->first();
                    if($product == null){
                        $item = DB::table('merchant_product')->insertGetId([
                            'merchant_id' => $merchant->merchant_id,
                            'product_name' => $item_name,
                            'category_id' => 0,
                            'gst_percent' => 5,
                            'price' => $paymentRequest->basic_amount,
                            'unit_type_id' => 0,
                            'vendor_id' => 0,
                            'purchase_cost' => 0.00,
                            'has_stock_keeping' => 0,
                            'available_stock' => 0.00,
                            'minimum_order' => 0.00,
                            'minimum_stock' => 0.00,
                            'is_active' => 1,
                            'created_by' => $merchant->user_id,
                            'last_update_by' => $merchant->user_id, 
                        ]);
                    }
                    $product = DB::table('merchant_product')->where('product_id', $item)->first();

                    DB::table('invoice_particular')->insert([
                        'payment_request_id' => $paymentRequest->payment_request_id,
                        'item' => $product->product_name,
                        'product_id' => $product->product_id,
                        'qty' => 1.00,
                        'rate' => $paymentRequest->basic_amount,
                        'gst' => 5,
                        'tax_amount' => 0,
                        'discount' => 0.00,
                        'total_amount' => $paymentRequest->basic_amount,
                        'created_by' => $merchant->user_id,
                        'last_update_by' => $merchant->user_id,

                    ]);

                    DB::table('invoice_column_values')->insert([
                        'payment_request_id' => $paymentRequest->payment_request_id,
                        'column_id' => $invoice_column->column_id,
                        'value' => $paymentRequest->invoice_number,
                        'created_by' => $merchant->user_id,
                        'last_update_by' => $merchant->user_id,
                    ]);
                    if($counter%2 == 0){
                        $paymentTransaction = factory(PaymentTransaction::class)->create([
                            'payment_request_id' => $paymentRequest->payment_request_id,
                            'customer_id' => $customer->customer_id,
                            'merchant_id' => $merchant->merchant_id,
                            'merchant_user_id' => $merchant->user_id,
                            'amount' => $paymentRequest->absolute_cost,
                            'created_by' => $merchant->user_id,
                            'last_update_by' => $merchant->user_id,
                        ]);
                        $counter++;
                    }
                    else{
                        $xwayTransaction = factory(XWayTransaction::class)->create([
                            'merchant_id' => $merchant->merchant_id,
                            'account_id' => $merchant->merchant_id,
                            'amount' => $paymentRequest->absolute_cost,
                            'absolute_cost' => $paymentRequest->absolute_cost,
                            'customer_code' => $customer->customer_code,
                            'name' => $customer->first_name.' '.$customer->last_name,
                            'address' => $customer->address,
                            'city' => $customer->city,
                            'state' => $customer->state,
                            'postal_code' => $customer->zipcode,
                            'phone' => $customer->mobile,
                            'email' => $customer->email,
                            'payment_request_id' => $paymentRequest->payment_request_id
                        ]);
                        $counter++;
                    }
                }
            }
        }
    }
}
