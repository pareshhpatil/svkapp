<?php

require 'config.php';
// DB table to use
$table = 'payment_request';

// Table's primary key
$primaryKey = 'payment_request_id';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
define('LIB', '../../legacy_app/lib/');
require_once "../../legacy_app/util/ConfigReader.php";



$configReader = new ConfigReader();
$dbArr = $configReader->getDBConfig();
$columns = array(
    array('db' => 'invoice_id', 'dt' => 0),
    array('db' => 'invoice_number', 'dt' => 1),
    array('db' => 'customer_code', 'dt' => 2),
    array('db' => 'customer_name', 'dt' => 3),
    array('db' => 'cycle_name', 'dt' => 4),
    array('db' => 'sent_date', 'dt' => 5, 'datatype' => 'specialDate'),
    array('db' => 'bill_date', 'dt' => 6, 'datatype' => 'date'),
    array('db' => 'due_date', 'dt' => 7, 'datatype' => 'date'),
    array('db' => 'status', 'dt' => 8),
    array('db' => 'invoice_amount', 'dt' => 9, 'datatype' => 'money')
);

$int = 10;


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
require( 'moveinvoice.class.php' );

SSP::$user_id = $merchant_id;
SSP::$franchise_id = $_SESSION['_franchise_id'];
SSP::$vendor_id = $_SESSION['_vendor_id'];
SSP::$from_date = $_SESSION['_from_date'];
SSP::$to_date = $_SESSION['_to_date'];
SSP::$cycle_id = $_SESSION['_cycle_name'];
SSP::$action_coll = 0;

SSP::$status = $_SESSION['_status'];
SSP::$aging_by = 'bill_date';
SSP::$is_setteled = $_SESSION['_is_settle'];
SSP::$group = $_SESSION['_group'];
SSP::$billing_profile_id = $_SESSION['_billing_profile_id'];
SSP::$invoice_type = $_SESSION['_invoice_type'];
SSP::$customer_id = ($_SESSION['_customer_id'] > 0) ? $_SESSION['_customer_id'] : 0;
$column_name = $_SESSION['column_select'];
$column_name = implode('~', $column_name);

echo json_encode(
        SSP::simple($_POST, $sql_details, $column_name, $table, $primaryKey, $columns)
);
?>