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
    array('db' => 'customer_name', 'dt' => 2),
    array('db' => 'company_name', 'dt' => 3),
    array('db' => 'mode', 'dt' => 4),
    array('db' => 'source', 'dt' => 5),
    array('db' => 'amount', 'dt' => 6, 'datatype' => 'money')
);



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
require( 'collections.class.php' );

SSP::$merchant_id = $merchant_id;
SSP::$from_date = $_SESSION['_from_date'];
SSP::$to_date = $_SESSION['_to_date'];
SSP::$action_coll = 0;

SSP::$mode = ($_SESSION['_mode'] != '') ? $_SESSION['_mode'] : '';
SSP::$source = ($_SESSION['_source'] != '') ? $_SESSION['_source'] : '';


echo json_encode(
        SSP::simple($_POST, $sql_details, $columns)
);
