<?php

require 'config.php';

// DB table to use
$table = 'payment_transaction';

// Table's primary key
$primaryKey = 'pay_transaction_id';


$configReader = new ConfigReader();
$dbArr = $configReader->getDBConfig();
$columns = array(
    array('db' => 'date', 'dt' => 0, 'datatype' => 'specialDate'),
    array('db' => 'pay_transaction_id', 'dt' => 1),
    array('db' => 'invoice_number', 'dt' => 2),
    array('db' => 'customer_code', 'dt' => 3),
    array('db' => 'customer_name', 'dt' => 4),
    array('db' => 'company_name', 'dt' => 5),
    array('db' => 'cycle_name', 'dt' => 6),
    array('db' => 'ref_no', 'dt' => 7),
    array('db' => 'status', 'dt' => 8),
    array('db' => 'late_payment', 'dt' => 9),
    array('db' => 'deduct_amount', 'dt' => 10),
    array('db' => 'amount', 'dt' => 11, 'datatype' => 'money')
);

$int = 12;
foreach ($_SESSION['_column_select'] as $col) {
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
require( 'paymentreceived.class.php' );

SSP::$merchant_id = $merchant_id;
SSP::$from_date = $_SESSION['_from_date'];
SSP::$to_date = $_SESSION['_to_date'];
SSP::$action_coll = 0;

SSP::$customer_id = ($_SESSION['_customer_id'] > 0) ? $_SESSION['_customer_id'] : 0;
$column_name = $_SESSION['_column'];
$column_name = implode('~', $column_name);

echo json_encode(
        SSP::simple($_POST, $sql_details, $column_name, $table, $primaryKey, $columns)
);
?>