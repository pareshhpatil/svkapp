<?php

require 'config.php';

// DB table to use
$table = 'payment_request';

// Table's primary key
$primaryKey = 'payment_request_id';


$configReader = new ConfigReader();
$dbArr = $configReader->getDBConfig();
$columns = array(
    array('db' => 'invoice_id', 'dt' => 0),
    array('db' => 'invoice_number', 'dt' => 1),
    array('db' => 'bill_date', 'dt' => 2, 'datatype' => 'date'),
    array('db' => 'customer_code', 'dt' => 3),
    array('db' => 'customer_name', 'dt' => 4),
    array('db' => 'company_name', 'dt' => 5),
    array('db' => 'cycle_name', 'dt' => 6),
    array('db' => 'sent_date', 'dt' => 7, 'datatype' => 'specialDate'),
    array('db' => 'due_date', 'dt' => 8, 'datatype' => 'date'),
    array('db' => 'status', 'dt' => 9),
    array('db' => 'invoice_amount', 'dt' => 10, 'datatype' => 'money')
);

$int = 11;
foreach ($_SESSION['display_column'] as $col) {
    $col = str_replace('.', '', $col);
    $col = str_replace(' ', '_', $col);
    $col = '__' . $col;
    $columns[] = array('db' => $col, 'dt' => $int);
    $int++;
}

// SQL server connection information
$sql_details = array(
    'user' => $dbArr['USER'],
    'pass' => $dbArr['CRED'],
    'db' => $dbArr['SCHEMA'],
    'host' => $dbArr['URL']
);


/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */
require( 'invoicedetail.class.php' );

SSP::$merchant_id = $merchant_id;
SSP::$franchise_id = $_SESSION['_franchise_id'];
SSP::$vendor_id = $_SESSION['_vendor_id'];
SSP::$billing_profile_id = $_SESSION['_billing_profile_id'];
SSP::$from_date = $_SESSION['_from_date'];
SSP::$to_date = $_SESSION['_to_date'];
SSP::$cycle_id = $_SESSION['_cycle_name'];
SSP::$action_coll = 0;

SSP::$status = $_SESSION['_status'];
SSP::$aging_by = $_SESSION['_aging_by'];
SSP::$is_setteled = $_SESSION['_is_settle'];
SSP::$group = $_SESSION['_group'];
SSP::$invoice_type = $_SESSION['_invoice_type'];
SSP::$customer_id = ($_SESSION['_customer_id'] > 0) ? $_SESSION['_customer_id'] : 0;
$column_name = $_SESSION['column_select'];
$column_name = implode('~', $column_name);

echo json_encode(
        SSP::simple($_POST, $sql_details, $column_name, $table, $primaryKey, $columns)
);
?>