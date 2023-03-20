<?php

class SSP
{

    static $franchise_id = 0;
    static $vendor_id = 0;
    static $from_date = '';
    static $to_date = '';
    static $merchant_id = '';
    static $cycle_id = '';
    static $customer_id = 0;
    static $status = '';
    static $aging_by = '';
    static $is_setteled = '';
    static $group = '';
    static $action_coll = 1;
    static $invoice_type = 1;
    static $billing_profile_id = 0;

    /**
     * Create the data output array for the DataTables rows
     *
     *  @param  array $columns Column information array
     *  @param  array $data    Data from the SQL get
     *  @return array          Formatted data in a row based format
     */
    static function data_output($columns, $data)
    {
        $encrypt = new Encryption();
        $out = array();
        for ($i = 0, $ien = count($data); $i < $ien; $i++) {
            $row = array();
            for ($j = 0, $jen = count($columns); $j < $jen; $j++) {
                $column = $columns[$j];
                // Is there a formatter?
                if (isset($column['datatype'])) {
                    if ($column['datatype'] == 'datetime') {
                        $row[$column['dt']] = date('d/M/y h:i A', strtotime($data[$i][$column['db']]));
                    } elseif ($column['datatype'] == 'date') {
                        $row[$column['dt']] = formatDateString($data[$i][$column['db']]); 
                    } elseif ($column['datatype'] == 'specialDate') {
                        $row[$column['dt']] = formatTimeString2($data[$i][$column['db']]);
                    } elseif ($column['datatype'] == 'money') {
                        //$row[$column['dt']] = moneyFormatIndia($data[$i][$column['db']]);
                        if($data[$i]['currency_icon'] == '$'){
                            $row[$column['dt']] = $data[$i]['currency_icon'] . number_format($data[$i][$column['db']]) . '</span>';
                        }else{
                            $row[$column['dt']] = $data[$i]['currency_icon'] . moneyFormatIndia($data[$i][$column['db']]) . '</span>';
                        }
                        
                    }
                } else {
                    $value = $data[$i][$columns[$j]['db']];
                    $link = $encrypt->encode($data[$i]['invoice_id']);
                    $status = $data[$i]['payment_request_status'];
                    $custom_invoice_status = (isset($_SESSION['_custom_invoice_status']) && $_SESSION['_custom_invoice_status']!=null) ? $_SESSION['_custom_invoice_status'] : [];
                    if ($column['db'] == 'status') {
                        if ($status == '1') {
                            $value = (array_key_exists($status, $custom_invoice_status)) ? strtoupper($custom_invoice_status[$status]) : 'PAID ONLINE';
                        } else if ($status == '2') {
                            $value = (array_key_exists($status, $custom_invoice_status)) ? strtoupper($custom_invoice_status[$status]) : 'PAID OFFLINE';
                        } else if ($status == '6') {
                            $value = (array_key_exists($status, $custom_invoice_status)) ? strtoupper($custom_invoice_status[$status]) : 'PAID ONLINE';
                        } else if ($status == '7') {
                            $value = (array_key_exists($status, $custom_invoice_status)) ? strtoupper($custom_invoice_status[$status]) : 'PART PAID';
                        } else if ($status == '11') {
                            $value = (array_key_exists($status, $custom_invoice_status)) ? strtoupper($custom_invoice_status[$status]) : 'DRAFT';
                        } else if ($status == '12') {
                            $value = (array_key_exists($status, $custom_invoice_status)) ? strtoupper($custom_invoice_status[$status]) : 'CANCELLED';
                        } else if ($status == '13') {
                            $value = (array_key_exists($status, $custom_invoice_status)) ? strtoupper($custom_invoice_status[$status]) : 'DELETED';
                        } else if ($status == '33') {
                            $value = (array_key_exists($status, $custom_invoice_status)) ? strtoupper($custom_invoice_status[$status]) : 'PROCESSING';
                        } else if ($status == '9') {
                            $value = (array_key_exists($status, $custom_invoice_status)) ? strtoupper($custom_invoice_status[$status]) : 'REFUNDED';
                        } else if($status == '4') {
                            $value = (array_key_exists($status, $custom_invoice_status)) ? strtoupper($custom_invoice_status[$status]) : 'FAILED';
                        } else if($status == '3') {
                            $value = (array_key_exists($status, $custom_invoice_status)) ? strtoupper($custom_invoice_status[$status]) : 'REJECTED';
                        } else if($status == '14') {
                            $value = (array_key_exists($status, $custom_invoice_status)) ? strtoupper($custom_invoice_status[$status]) : 'SAVED';
                        } else if($status == '0') {
                            $value = (array_key_exists($status, $custom_invoice_status)) ? strtoupper($custom_invoice_status[$status]) : 'SUBMITTED';
                        }
                    }

                    if ($column['dt'] == self::$action_coll) {
                        $check_box = '';
                        if ($i < 50) {
                            $check_box = '<input type="hidden" value="' . $value . '" name="pdf_req[]">';
                        }
                        $row[$column['dt']] = $check_box . ' <a target="_BLANK" href="/merchant/paymentrequest/view/' . $link . '">
                                            ' . $value . ' </a>';
                    } else {
                        $row[$column['dt']] = $value;
                    }
                }
            }
            $out[] = $row;
        }
        // print_r($out);
        //print_r($data);
        // die();
        return $out;
    }

    /**
     * Database connection
     *
     * Obtain an PHP PDO connection from a connection details array
     *
     *  @param  array $conn SQL connection details. The array should have
     *    the following properties
     *     * host - host name
     *     * db   - database name
     *     * user - user name
     *     * pass - user password
     *  @return resource PDO connection
     */
    static function db($conn)
    {
        if (is_array($conn)) {
            return self::sql_connect($conn);
        }
        return $conn;
    }

    /**
     * Paging
     *
     * Construct the LIMIT clause for server-side processing SQL query
     *
     *  @param  array $request Data sent to server by DataTables
     *  @param  array $columns Column information array
     *  @return string SQL limit clause
     */
    static function limit($request, $columns)
    {
        $limit = '';
        if (isset($request['start']) && $request['length'] != -1) {
            $limit = "LIMIT " . intval($request['start']) . ", " . intval($request['length']);
        }
        return $limit;
    }

    /**
     * Ordering
     *
     * Construct the ORDER BY clause for server-side processing SQL query
     *
     *  @param  array $request Data sent to server by DataTables
     *  @param  array $columns Column information array
     *  @return string SQL order by clause
     */
    static function order($request, $columns)
    {
        $order = '';
        if (isset($request['order']) && count($request['order'])) {
            $orderBy = array();
            $dtColumns = self::pluck($columns, 'dt');
            for ($i = 0, $ien = count($request['order']); $i < $ien; $i++) {
                // Convert the column index into the column data property
                $columnIdx = intval($request['order'][$i]['column']);
                $requestColumn = $request['columns'][$columnIdx];
                $columnIdx = array_search($requestColumn['data'], $dtColumns);
                $column = $columns[$columnIdx];
                if ($requestColumn['orderable'] == 'true') {
                    $dir = $request['order'][$i]['dir'] === 'asc' ?
                        'ASC' :
                        'DESC';
                    $orderBy[] = '`' . $column['db'] . '` ' . $dir;
                }
            }
            $order = 'ORDER BY ' . implode(', ', $orderBy);
        }
        return $order;
    }

    /**
     * Searching / Filtering
     *
     * Construct the WHERE clause for server-side processing SQL query.
     *
     * NOTE this does not match the built-in DataTables filtering which does it
     * word by word on any field. It's possible to do here performance on large
     * databases would be very poor
     *
     *  @param  array $request Data sent to server by DataTables
     *  @param  array $columns Column information array
     *  @param  array $bindings Array of values for PDO bindings, used in the
     *    sql_exec() function
     *  @return string SQL where clause
     */
    static function filter($request, $columns, &$bindings)
    {
        $globalSearch = array();
        $columnSearch = array();
        $dtColumns = self::pluck($columns, 'dt');
        if (isset($request['search']) && $request['search']['value'] != '') {
            $str = $request['search']['value'];
            for ($i = 0, $ien = count($request['columns']); $i < $ien; $i++) {
                $requestColumn = $request['columns'][$i];
                $columnIdx = array_search($requestColumn['data'], $dtColumns);
                $column = $columns[$columnIdx];
                if ($requestColumn['searchable'] == 'true') {
                    $binding = self::bind($bindings, '%' . $str . '%', PDO::PARAM_STR);

                    $globalSearch[] = "`" . $column['db'] . "` LIKE " . "~%" . $str . "%~";
                }
            }
        }
        // Individual column filtering
        if (isset($request['columns'])) {
            for ($i = 0, $ien = count($request['columns']); $i < $ien; $i++) {
                $requestColumn = $request['columns'][$i];
                $columnIdx = array_search($requestColumn['data'], $dtColumns);
                $column = $columns[$columnIdx];
                $str = $requestColumn['search']['value'];
                if (
                    $requestColumn['searchable'] == 'true' &&
                    $str != ''
                ) {
                    $binding = self::bind($bindings, '%' . $str . '%', PDO::PARAM_STR);
                    $columnSearch[] = "`" . $column['db'] . "` LIKE " . "~%" . $str . "%~";
                }
            }
        }
        // Combine the filters into a single string
        $where = "";
        if (count($globalSearch)) {
            $where = '(' . implode(' OR ', $globalSearch) . ')';
        }
        if (self::$group != '') {
            if ($where == '') {
                $where = " customer_group like ~%" . '{' . self::$group . '}' . '%~';
            } else {
                $where .= " AND customer_group like ~%" . '{' . self::$group . '}' . '%~';
            }
        } else {
            $grptext = '';
            if (!empty($_SESSION['login_customer_group'])) {
                foreach ($_SESSION['login_customer_group'] as $grpname) {
                    if ($grptext == '') {
                        $grptext = " (customer_group like ~%" . '{' . $grpname . '}' . '%~';
                    } else {
                        $grptext .= " or customer_group like ~%" . '{' . $grpname . '}' . '%~';
                    }
                }
                $grptext .= ')';

                if ($where == '') {
                    $where = $grptext;
                } else {
                    $where .= " AND " . $grptext;
                }
            }
        }

        if (self::$group != '') {
            if ($where == '') {
                $where = " customer_group like ~%" . '{' . self::$group . '}' . '%~';
            } else {
                $where .= " AND customer_group like ~%" . '{' . self::$group . '}' . '%~';
            }
        }

        if (self::$billing_profile_id > 0) {
            if ($where == '') {
                $where = " billing_profile_id =" . self::$billing_profile_id;
            } else {
                $where .= " AND billing_profile_id =" . self::$billing_profile_id;
            }
        }

        if (count($columnSearch)) {
            $where = $where === '' ?
                implode(' AND ', $columnSearch) :
                $where . ' AND ' . implode(' AND ', $columnSearch);
        }


        if ($where !== '') {
            $where = 'WHERE ' . $where;
        }
        return $where;
    }

    /**
     * Perform the SQL queries needed for an server-side processing requested,
     * utilising the helper functions of this class, limit(), order() and
     * filter() among others. The returned array is ready to be encoded as JSON
     * in response to an SSP request, or can be modified if needed before
     * sending back to the client.
     *
     *  @param  array $request Data sent to server by DataTables
     *  @param  array|PDO $conn PDO connection resource or connection parameters array
     *  @param  string $table SQL table to query
     *  @param  string $primaryKey Primary key of the table
     *  @param  array $columns Column information array
     *  @return array          Server-side processing response array
     */
    static function simple($request, $conn, $column_name, $table, $primaryKey, $columns)
    {
        $bindings = array();
        $db = self::db($conn);
        // Build the SQL query string from the request
        $limit = self::limit($request, $columns);
        $order = self::order($request, $columns);
        $where = self::filter($request, $columns, $bindings);
        // Main query to actually get the data
        $data = self::sql_exec(
            $db,
            $bindings,
            "call report_invoice_details('" . self::$merchant_id . "','" . self::$from_date . "',"
                . "'" . self::$to_date . "'," . self::$invoice_type . ",'" . self::$cycle_id . "','" . self::$customer_id . "','" . self::$status . "','" . self::$aging_by . "','" . $column_name . "','" . self::$is_setteled . "','" . self::$franchise_id . "','" . self::$vendor_id . "','" . $where . "','" . $order . "','" . $limit . "');"
        );

        $recordsFiltered = $data[0]['@fcount'];
        // Total data set length

        $recordsTotal = $data[0]['@count'];
        $totalSum = $data[0]['@totalSum'];
        /*
         * Output
         */

        return array(
            "draw" => isset($request['draw']) ?
                intval($request['draw']) :
                0,
            "totalSum" => intval($totalSum),
            "recordsTotal" => intval($recordsTotal),
            "recordsFiltered" => intval($recordsFiltered),
            "data" => self::data_output($columns, $data)
        );
    }

    /**
     * The difference between this method and the `simple` one, is that you can
     * apply additional `where` conditions to the SQL queries. These can be in
     * one of two forms:
     *
     * * 'Result condition' - This is applied to the result set, but not the
     *   overall paging information query - i.e. it will not effect the number
     *   of records that a user sees they can have access to. This should be
     *   used when you want apply a filtering condition that the user has sent.
     * * 'All condition' - This is applied to all queries that are made and
     *   reduces the number of records that the user can access. This should be
     *   used in conditions where you don't want the user to ever have access to
     *   particular records (for example, restricting by a login id).
     *
     *  @param  array $request Data sent to server by DataTables
     *  @param  array|PDO $conn PDO connection resource or connection parameters array
     *  @param  string $table SQL table to query
     *  @param  string $primaryKey Primary key of the table
     *  @param  array $columns Column information array
     *  @param  string $whereResult WHERE condition to apply to the result set
     *  @param  string $whereAll WHERE condition to apply to all queries
     *  @return array          Server-side processing response array
     */

    /**
     * Connect to the database
     *
     * @param  array $sql_details SQL server connection details array, with the
     *   properties:
     *     * host - host name
     *     * db   - database name
     *     * user - user name
     *     * pass - user password
     * @return resource Database connection handle
     */
    static function sql_connect($sql_details)
    {
        try {
            $db = @new PDO(
                "mysql:host={$sql_details['host']};dbname={$sql_details['db']};charset=utf8",
                $sql_details['user'],
                $sql_details['pass'],
                array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
            );
        } catch (PDOException $e) {
            self::fatal(
                "An error occurred while connecting to the database. " .
                    "The error reported by the server was: " . $e->getMessage()
            );
        }
        return $db;
    }

    /**
     * Execute an SQL query on the database
     *
     * @param  resource $db  Database handler
     * @param  array    $bindings Array of PDO binding values from bind() to be
     *   used for safely escaping strings. Note that this can be given as the
     *   SQL query string if no bindings are required.
     * @param  string   $sql SQL query to execute.
     * @return array         Result from the query (all rows)
     */
    static function sql_exec($db, $bindings, $sql = null)
    {
        // Argument shifting
        if ($sql === null) {
            $sql = $bindings;
        }
        $stmt = $db->prepare($sql);
        // Bind parameters
        // Execute
        try {
            $stmt->execute();
        } catch (PDOException $e) {
            self::fatal("An SQL error occurred: " . $e->getMessage());
        }
        // Return all
        return $stmt->fetchAll(PDO::FETCH_BOTH);
    }

    /*     * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
     * Internal methods
     */

    /**
     * Throw a fatal error.
     *
     * This writes out an error message in a JSON string which DataTables will
     * see and show to the user in the browser.
     *
     * @param  string $msg Message to send to the client
     */
    static function fatal($msg)
    {
        echo json_encode(array(
            "error" => $msg
        ));
        exit(0);
    }

    /**
     * Create a PDO binding key which can be used for escaping variables safely
     * when executing a query with sql_exec()
     *
     * @param  array &$a    Array of bindings
     * @param  *      $val  Value to bind
     * @param  int    $type PDO field type
     * @return string       Bound key to be used in the SQL where this parameter
     *   would be used.
     */
    static function bind(&$a, $val, $type)
    {
        $key = ':binding_' . count($a);
        $a[] = array(
            'key' => $key,
            'val' => $val,
            'type' => $type
        );
        return $key;
    }

    /**
     * Pull a particular property from each assoc. array in a numeric array, 
     * returning and array of the property values from each item.
     *
     *  @param  array  $a    Array to get data from
     *  @param  string $prop Property to read
     *  @return array        Array of property values
     */
    static function pluck($a, $prop)
    {
        $out = array();
        for ($i = 0, $len = count($a); $i < $len; $i++) {
            $out[] = $a[$i][$prop];
        }
        return $out;
    }

    /**
     * Return a string from an array or a string
     *
     * @param  array|string $a Array to join
     * @param  string $join Glue for the concatenation
     * @return string Joined string
     */
    static function _flatten($a, $join = ' AND ')
    {
        if (!$a) {
            return '';
        } else if ($a && is_array($a)) {
            return implode($join, $a);
        }
        return $a;
    }
}
