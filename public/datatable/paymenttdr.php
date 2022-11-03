<?php

require 'config.php';

// DB table to use
$table = 'payment_transaction';

// Table's primary key
$primaryKey = 'pay_transaction_id';


$configReader = new ConfigReader();
$dbArr = $configReader->getDBConfig();
$columns = array(
    array('db' => 'transaction_id', 'dt' => 0),
    array('db' => 'cap_date', 'dt' => 1, 'datatype' => 'specialDate'),
    array('db' => 'patron_name', 'dt' => 2),
    array('db' => 'company_name', 'dt' => 3),
    array('db' => 'payment_method', 'dt' => 4),
    array('db' => 'bank_reff', 'dt' => 5),
    array('db' => 'captured', 'dt' => 6),
    array('db' => 'refunded', 'dt' => 7),
    array('db' => 'chargeback', 'dt' => 8),
    array('db' => 'tdr', 'dt' => 9),
    array('db' => 'service_tax', 'dt' => 10),
    array('db' => 'surcharge', 'dt' => 11),
    array('db' => 'surcharge_service_tax', 'dt' => 12),
    array('db' => 'net_amount', 'dt' => 13)
);

$int = 14;
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
require( 'paymenttdr.class.php' );

SSP::$merchant_id = $user_id;
SSP::$franchise_id = ($_SESSION['sub_franchise_id'] > 0) ? $_SESSION['sub_franchise_id'] : 0;
SSP::$from_date = $_SESSION['_from_date'];
SSP::$to_date = $_SESSION['_to_date'];
SSP::$action_coll = 0;

$column_name = $_SESSION['_column_select'];
$column_name = implode('~', $column_name);

echo json_encode(
        SSP::simple($_POST, $sql_details, $column_name, $table, $primaryKey, $columns)
);
?>