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


$configReader = new ConfigReader();
$dbArr = $configReader->getDBConfig();
$columns = array(
    array('db' => 'payment_request_id', 'dt' => 0),
    //array('db' => 'invoice_number', 'dt' => 1),
    //array('db' => 'customer_code', 'dt' => 2),
    array('db' => 'name', 'dt' => 1),
    array('db' => 'invoice_type', 'dt' => 2),
    array('db' => 'project_name', 'dt' => 3),
    //array('db' => 'payment_request_type', 'dt' => 3),
    array('db' => 'absolute_cost', 'dt' => 4, 'datatype' => 'money'),
    array('db' => 'absolute_cost', 'dt' => 5, 'datatype' => 'cost'),
    array('db' => 'due_date', 'dt' => 6, 'datatype' => 'date'),
    array('db' => 'send_date', 'dt' => 7, 'datatype' => 'specialDate'),
    array('db' => 'status', 'dt' => 8),
);

$int = 9;
foreach ($_SESSION['display_column'] as $col) {
    $col = str_replace('.', '', $col);
    $col = str_replace(' ', '_', $col);
    $col = '__' . $col;
    $columns[] = array('db' => $col, 'dt' => $int);
    $int++;
}
$columns[] = array('db' => 'payment_request_id', 'dt' => $int);

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
require( 'paymentrequest.class.php' );

SSP::$merchant_id = $merchant_id;
SSP::$franchise_id = $_SESSION['sub_franchise_id'];
SSP::$from_date = $_SESSION['_from_date'];
SSP::$to_date = $_SESSION['_to_date'];
SSP::$cycle_id = $_SESSION['_cycle_name'];
SSP::$action_coll = $int;
SSP::$bulk_id = ($_SESSION['_bulk_id'] > 0) ? $_SESSION['_bulk_id'] : 0;
SSP::$type = ($_SESSION['_type'] > 0) ? $_SESSION['_type'] : 1;
SSP::$invoice_type = ($_SESSION['_invoice_type'] > 0) ? $_SESSION['_invoice_type'] : 0;
SSP::$invoice_status = $_SESSION['_invoice_status'];
$column_name = $_SESSION['display_column'];
$column_name = implode('~', $column_name);

echo json_encode(
        SSP::simple($_POST, $sql_details, $column_name, $table, $primaryKey, $columns,$headers)
);
?>