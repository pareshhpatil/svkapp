<?php

class SSP
{

    static $franchise_id = 0;
    static $from_date = '';
    static $to_date = '';
    static $merchant_id = '';
    static $cycle_id = '';
    static $bulk_id = 0;
    static $type = 1;
    static $invoice_type = 0;
    static $invoice_status = 0;
    static $action_coll = 1;

    /**
     * Create the data output array for the DataTables rows
     *
     *  @param  array $columns Column information array
     *  @param  array $data    Data from the SQL get
     *  @return array          Formatted data in a row based format
     */
    static function data_output($columns, $data, $privilegesArray)
    {
        $encrypt = new Encryption();
        $out = array();
        $customer_code_text = (isset($_SESSION['_customer_code_text'])) ? $_SESSION['_customer_code_text'] : 'CUSTOMER CODE';
        $hasAllPrivileges = false;
        if (in_array('all', array_keys($privilegesArray))) {
            $hasAllPrivileges = true;
        }

        for ($i = 0, $ien = count($data); $i < $ien; $i++) {
            $row = array();
            for ($j = 0, $jen = count($columns); $j < $jen; $j++) {
                $column = $columns[$j];
                // Is there a formatter?
                if (isset($column['datatype'])) {
                    if ($column['datatype'] == 'datetime') {
                        $row[$column['dt']] = date('d/M/y h:i A', strtotime($data[$i][$column['db']]));
                    } elseif ($column['datatype'] == 'date') {
                        // $value = formatDateString($data[$i][$column['db']]);
                        if ($data[$i][$column['db']] < date("Y-m-d") && !in_array($data[$i]['payment_request_status'],array(1,2,11,14))) {
                            $value = formatDateString($data[$i][$column['db']]);
                            $row[$column['dt']] = '<span style="color:#B82020;">'.$value.'</span>';
                        } else {
                            $row[$column['dt']] = $data[$i][$column['db']];
                        }
                        
                    } elseif ($column['datatype'] == 'specialDate') {
                        //$row[$column['dt']] = formatTimeString($data[$i][$column['db']]);

                        if ($data[$i]['payment_request_type'] == 1) {
                            $value = 'Single';
                        } else if ($data[$i]['payment_request_type'] == 3) {
                            $value = 'Bulk';
                            $encrypted_bulk_id = $encrypt->encode($data[$i]['bulk_id']);
                            $value = '<a target="_BLANK" href="/merchant/bulkupload/bulkrequest/' . $encrypted_bulk_id . '">' . $value . '</a>';
                        } else if ($data[$i]['payment_request_type'] == 5) {
                            $value = 'Subscription';
                            $encrypted_subscription_id = $encrypt->encode($data[$i]['parent_request_id']);
                            $value = '<a target="_BLANK" href="/merchant/subscription/viewlist/' . $encrypted_subscription_id . '">' . "Subscription" . '</a>';
                        } else if ($data[$i]['payment_request_type'] == 6) {
                            $value = 'Woocommerce';
                        }
                        $row[$column['dt']] = formatTimeString2($data[$i][$column['db']]);
                    } elseif ($column['datatype'] == 'money') {
                        if ($data[$i][$column['db']] < 0) {
                            $row[$column['dt']] = $data[$i]['currency_icon'] . ' ' . '(' . number_format(str_replace('-', '', $data[$i][$column['db']])) . ')' . '</span>';
                        } else {
                            $row[$column['dt']] = $data[$i]['currency_icon'] . ' ' . number_format($data[$i][$column['db']]) . '</span>';
                        }
                    } else if ($column['datatype'] == 'cost') {
                        if ($data[$i][$column['db']] < 0) {
                            $row[$column['dt']] = $data[$i]['currency_icon'] . ' ' . '(' . number_format(str_replace('-', '', $data[$i][$column['db']])) . ')';
                        } else {
                            $row[$column['dt']] = $data[$i]['currency_icon'] . ' ' . number_format($data[$i][$column['db']]);
                        }
                    }
                } else {
                    $value = $data[$i][$columns[$j]['db']];
                    $link = $encrypt->encode($data[$i]['payment_request_id']);
                    $estimatelink = $encrypt->encode($data[$i]['converted_request_id']);
                    $copy_link = 'https://' . $_SERVER['SERVER_NAME'] . '/patron/invoice/view/' . $link . '/702';

                    if (!empty($data[$i]['short_url'])) {
                        $copy_link = $data[$i]['short_url'];
                    }

                    $status = $data[$i]['payment_request_status'];
                    $request_type = $data[$i]['invoice_type'];
                    $invoice_type = ($data[$i]['invoice_type'] == 2) ? 'Estimate' : 'Invoice';

                    $custom_invoice_status = (isset($_SESSION['_custom_invoice_status']) && $_SESSION['_custom_invoice_status'] != null) ? $_SESSION['_custom_invoice_status'] : [];
                    if ($column['db'] == 'invoice_type') {
                        $invoicenoStr = '';
                        if (!empty($data[$i]['invoice_number']) || $data[$i]['revision_no'] != '') {
                            $invoiceno = '';
                            if ($data[$i]['invoice_number'] != '') {
                                $invoiceno =  strtoupper($invoice_type) . ' # : <span class="text-gray-900">' . $data[$i]['invoice_number'];
                            }

                            if ($data[$i]['revision_no'] != '') {
                                if ($data[$i]['invoice_number'] != '') {
                                    $pipe = ' | ';
                                }
                                $invoiceno .= $pipe . '<a  onclick="callRevisionSidePanel(' . "'" . $link . "'" . ');">
                                REV # ' . $data[$i]['revision_no'] . '</a>';
                            }
                            $invoicenoStr = '<br><span class="text-gray-400 text-font-12">'  . $invoiceno . '</span></span>';
                        }
                        $value = strtoupper($invoice_type) . $invoicenoStr;
                    }
                    if ($column['db'] == 'project_name') {


                        if (!empty($data[$i]['project_code'])) {
                            $value = $data[$i]['project_name'] . ' <br><span class="text-gray-400 text-font-12">PROJECT NO : <span class="text-gray-900">' . $data[$i]['project_code'] . '</span></span>';
                        } else {
                            $value = 'NA';
                        }
                    }

                    if ($column['db'] == 'name') {
                        if (empty($data[$i]['company_name'])) {
                            $value = '<a style="font-size: 1.2rem;" target="_BLANK" href="/merchant/paymentrequest/view/' . $link . '">' . $data[$i]['name'] . '</a> <br><span class="text-gray-400 text-font-12">' . $customer_code_text . ' : <span class="text-gray-900">' . $data[$i]['customer_code'] . '</span></span>';
                        } else {
                            $value = '<a style="font-size: 1.2rem;" target="_BLANK" href="/merchant/paymentrequest/view/' . $link . '">' . $data[$i]['company_name'] . '</a> <br><span class="text-gray-400 text-font-12">' . $customer_code_text . ' : <span class="text-gray-900">' . $data[$i]['customer_code'] . '</span></span>';
                        }
                    }

                    if ($column['db'] == 'status') {
                        if ($status == '1') {
                            $custom_invoice_status = (array_key_exists($status, $custom_invoice_status)) ? strtoupper($custom_invoice_status[$status]) : 'PAID ONLINE';
                            $value = '<span class="badge badge-pill status paid_online">' . $custom_invoice_status . '</span>';
                        } else if ($status == '2') {
                            $custom_invoice_status = (array_key_exists($status, $custom_invoice_status)) ? strtoupper($custom_invoice_status[$status]) : 'PAID OFFLINE';
                            $value = '<span class="badge badge-pill status paid_offline">' . $custom_invoice_status . '</span>';
                        } else if ($status == '6') {
                            $custom_invoice_status = (array_key_exists($status, $custom_invoice_status)) ? strtoupper($custom_invoice_status[$status]) : 'PAID ONLINE';
                            $value = '<span class="badge badge-pill status converted">' . $custom_invoice_status . '</span>';
                        } else if ($status == '7') {
                            $custom_invoice_status = (array_key_exists($status, $custom_invoice_status)) ? strtoupper($custom_invoice_status[$status]) : 'PART PAID';
                            $value = '<span class="badge badge-pill status partial_paid">' . $custom_invoice_status . '</span>';
                        } else if ($status == '11') {
                            $custom_invoice_status = (array_key_exists($status, $custom_invoice_status)) ? strtoupper($custom_invoice_status[$status]) : 'DRAFT';
                            $value = '<span class="badge badge-pill status draft">' . $custom_invoice_status . '</span>';
                        } else if ($status == '12') {
                            $custom_invoice_status = (array_key_exists($status, $custom_invoice_status)) ? strtoupper($custom_invoice_status[$status]) : 'CANCELLED';
                            $value = '<span class="badge badge-pill status cancelled">' . $custom_invoice_status . '</span>';
                        } else if ($status == '13') {
                            $custom_invoice_status = (array_key_exists($status, $custom_invoice_status)) ? strtoupper($custom_invoice_status[$status]) : 'DELETED';
                            $value = '<span class="badge badge-pill status deleted">' . $custom_invoice_status . '</span>';
                        } else if ($status == '33') {
                            $custom_invoice_status = (array_key_exists($status, $custom_invoice_status)) ? strtoupper($custom_invoice_status[$status]) : 'PROCESSING';
                            $value = '<span class="badge badge-pill status processing">' . $custom_invoice_status . '</span>';
                        } else if ($status == '9') {
                            $custom_invoice_status = (array_key_exists($status, $custom_invoice_status)) ? strtoupper($custom_invoice_status[$status]) : 'REFUNDED';
                            $value = '<span class="badge badge-pill status refunded">' . $custom_invoice_status . '</span>';
                        } else if ($status == '14') {
                            $custom_invoice_status = (array_key_exists($status, $custom_invoice_status)) ? strtoupper($custom_invoice_status[$status]) : 'SAVED';
                            $value = '<span class="badge badge-pill status unpaid">' . $custom_invoice_status . '</span>';
                            if ($hasAllPrivileges && !in_array($data[$i]['payment_request_id'], array_keys($privilegesArray))) {
                                if($privilegesArray['all'] == 'full' || $privilegesArray['all'] == 'approve') {
                                    $value = '<span class="badge badge-pill status unpaid">IN REVIEW</span>';
                                }
                            } elseif($privilegesArray[$data[$i]['payment_request_id']] == 'full' || $privilegesArray[$data[$i]['payment_request_id']] == 'approve') {
                                $value = '<span class="badge badge-pill status unpaid">IN REVIEW</span>';
                            }
                        } else if($status == 0) {
                            $custom_invoice_status = (array_key_exists($status, $custom_invoice_status)) ? strtoupper($custom_invoice_status[$status]) : 'SUBMITTED';
                            $value = '<span class="badge badge-pill status overdue">' . $custom_invoice_status . '</span>';
                            //0 = unpaid, 4=failed ,5= initiated
                            // if ($data[$i]['due_date'] < date("Y-m-d")) {
                            //     $value = '<span class="badge badge-pill status overdue">OVERDUE</span>';
                            // } else {
                            //     $value = '<span class="badge badge-pill status unpaid">UNPAID</span>';
                            // }
                        }
                    }
                    if ($column['dt'] == self::$action_coll) {
                        $row[$column['dt']] = '
                                            <div class="btn-group dropup" style="font-size: 1.5rem;margin-top: -7px;">
                                                <button class="btn btn-xs btn-link dropdown-toggle not-hover" type="button" data-toggle="dropdown" style="padding: 7px 5px 0px;">
                                                    &nbsp;&nbsp;<i class="fa fa-ellipsis-v" style="font-size: 1.5rem;"></i>&nbsp;&nbsp;
                                                </button>
                                                <ul class="dropdown-menu" role="menu">';
                        if ($data[$i]['template_type'] == 'construction') {
                            if ($hasAllPrivileges && !in_array($data[$i]['payment_request_id'], array_keys($privilegesArray))) {
                                if ($privilegesArray['all'] == 'full' || $privilegesArray['all'] == 'view-only' || $privilegesArray['all'] == 'approve') {
                                    $row[$column['dt']] .= '<li>
                                                    <a target="_BLANK" href="/merchant/invoice/viewg702/' . $link . '">
                                                        <i class="fa fa-table"></i> View 702</a>
                                                </li><li>
                                                <a target="_BLANK" href="/merchant/invoice/viewg703/' . $link . '">
                                                    <i class="fa fa-table"></i> View 703</a>
                                            </li>
                                            ';

                                    if ($status == 6) {
                                        $row[$column['dt']] .= '<li>
                                                    <a target="_BLANK" href="/merchant/paymentrequest/view/' . $estimatelink . '">
                                                        <i class="fa fa-table"></i> View Invoice</a>
                                                </li>';
                                    }
                                }

                                if ($privilegesArray['all'] == 'full' || $privilegesArray['all'] == 'edit' || $privilegesArray['all'] == 'approve') {
                                    if ($status == 0 || $status == 4 || $status == 5 || $status == 8 || $status == 11) {
                                        if ($request_type == 1 && $status != 11) {
                                            $row[$column['dt']] .= '<li><a target="_BLANK" href="/merchant/paymentrequest/view/' . $link . '#respond" title="Settle request" ><i class="fa fa-inr"></i> Settle</a></li>';
                                        } elseif ($request_type == 2 && $status != 11) {
                                            $row[$column['dt']] .= '<li><a target="_BLANK" href="/merchant/paymentrequest/view/' . $link . '#convert" title="Convert to Invoice" ><i class="fa fa-exchange"></i> Convert to Invoice</a></li>';
                                            $row[$column['dt']] .= '<li><a target="_BLANK" href="/merchant/paymentrequest/view/' . $link . '#settleestimate" title="Settle" ><i class="fa fa-inr"></i> Settle</a></li>';
                                        }
                                    } else if ($status == 2) {
                                        $row[$column['dt']] .= '<li><a target="_BLANK" href="/merchant/paymentrequest/view/' . $link . '#respond" title="Update Transaction" ><i class="fa fa-inr"></i> Update Transaction</a></li>';
                                    } else if ($status == 7) {
                                        $row[$column['dt']] .= '<li><a target="_BLANK" href="/merchant/paymentrequest/view/' . $link . '#respond" title="Settle request" ><i class="fa fa-inr"></i> Settle</a></li>';
                                    }

                                    if (
                                        $status == 0 ||
                                        $status == 4 || $status == 5 || $status == 8 || $status == 11 || $status == 2 || $status == 7 || $status == 14
                                    ) {
                                        $row[$column['dt']] .= '<li><a href="/merchant/invoice/update/' . $link . '" title="Update request" ><i class="fa fa-edit"></i> Edit</a></li>';
                                    }
                                }

                                if ($privilegesArray['all'] == 'full' || $privilegesArray['all'] == 'comment') {
                                    if ($status != 11) {
                                        $row[$column['dt']] .= '<li>
                                                        <a onclick="commentLink(this.id);" id="/merchant/comments/view/' . $link . '" ><i class="fa fa-comment"></i> Comments </a>
                                                    </li>';
                                    }
                                }

                                if ($privilegesArray['all'] == 'full') {
                                    if ($status == 0 || $status == 4 || $status == 5 || $status == 8 || $status == 11 || $status == 2 || $status == 7 || $status == 14) {
                                        $row[$column['dt']] .= '    <li>
                                                        <a title="Delete ' . $invoice_type . '" href="#basic" onclick="document.getElementById(' . "'" . 'deleteanchor' . "'" . ').href = ' . "'" . '/merchant/paymentrequest/delete/' . $link . "'" . '"
                                                           data-toggle="modal" ><i class="fa fa-remove"></i> Delete</a>  
                                                    </li>';
                                    }
                                }
                            } else {
                                if ($privilegesArray[$data[$i]['payment_request_id']] == 'full' || $privilegesArray[$data[$i]['payment_request_id']] == 'view-only' || $privilegesArray[$data[$i]['payment_request_id']] == 'approve' || $privilegesArray[$data[$i]['payment_request_id']] == 'edit') {
                                    $row[$column['dt']] .= '<li>
                                                    <a target="_BLANK" href="/merchant/invoice/viewg702/' . $link . '">
                                                        <i class="fa fa-table"></i> View 702</a>
                                                </li><li>
                                                <a target="_BLANK" href="/merchant/invoice/viewg703/' . $link . '">
                                                    <i class="fa fa-table"></i> View 703</a>
                                            </li>
                                            ';

                                    if ($status == 6) {
                                        $row[$column['dt']] .= '<li>
                                                    <a target="_BLANK" href="/merchant/paymentrequest/view/' . $estimatelink . '">
                                                        <i class="fa fa-table"></i> View Invoice</a>
                                                </li>';
                                    }
                                }

                                if ($privilegesArray[$data[$i]['payment_request_id']] == 'full' || $privilegesArray[$data[$i]['payment_request_id']] == 'edit' || $privilegesArray[$data[$i]['payment_request_id']] == 'approve') {
                                    if ($status == 0 || $status == 4 || $status == 5 || $status == 8 || $status == 11) {
                                        if ($request_type == 1 && $status != 11) {
                                            $row[$column['dt']] .= '<li><a target="_BLANK" href="/merchant/paymentrequest/view/' . $link . '#respond" title="Settle request" ><i class="fa fa-inr"></i> Settle</a></li>';
                                        } elseif ($request_type == 2 && $status != 11) {
                                            $row[$column['dt']] .= '<li><a target="_BLANK" href="/merchant/paymentrequest/view/' . $link . '#convert" title="Convert to Invoice" ><i class="fa fa-exchange"></i> Convert to Invoice</a></li>';
                                            $row[$column['dt']] .= '<li><a target="_BLANK" href="/merchant/paymentrequest/view/' . $link . '#settleestimate" title="Settle" ><i class="fa fa-inr"></i> Settle</a></li>';
                                        }
                                    } else if ($status == 2) {
                                        $row[$column['dt']] .= '<li><a target="_BLANK" href="/merchant/paymentrequest/view/' . $link . '#respond" title="Update Transaction" ><i class="fa fa-inr"></i> Update Transaction</a></li>';
                                    } else if ($status == 7) {
                                        $row[$column['dt']] .= '<li><a target="_BLANK" href="/merchant/paymentrequest/view/' . $link . '#respond" title="Settle request" ><i class="fa fa-inr"></i> Settle</a></li>';
                                    }

                                    if (
                                        $status == 0 ||
                                        $status == 4 || $status == 5 || $status == 8 || $status == 11 || $status == 2 || $status == 7 || $status == 14
                                    ) {
                                        $row[$column['dt']] .= '<li><a href="/merchant/invoice/update/' . $link . '" title="Update request" ><i class="fa fa-edit"></i> Edit</a></li>';
                                    }
                                }

                                if ($privilegesArray[$data[$i]['payment_request_id']] == 'full' || $privilegesArray[$data[$i]['payment_request_id']] == 'comment') {
                                    if ($status != 11) {
                                        $row[$column['dt']] .= '<li>
                                                        <a onclick="commentLink(this.id);" id="/merchant/comments/view/' . $link . '" ><i class="fa fa-comment"></i> Comments </a>
                                                    </li>';
                                    }
                                }

                                if ($privilegesArray[$data[$i]['payment_request_id']] == 'full') {
                                    if ($status == 0 || $status == 4 || $status == 5 || $status == 8 || $status == 11 || $status == 2 || $status == 7 || $status == 14) {
                                        $row[$column['dt']] .= '    <li>
                                                        <a title="Delete ' . $invoice_type . '" href="#basic" onclick="document.getElementById(' . "'" . 'deleteanchor' . "'" . ').href = ' . "'" . '/merchant/paymentrequest/delete/' . $link . "'" . '"
                                                           data-toggle="modal" ><i class="fa fa-remove"></i> Delete</a>  
                                                    </li>';
                                    }
                                }
                            }
                        }
                        if ($status != 11) {
                            $row[$column['dt']] .= ' <li class="divider"></li>
                                                <li>
                                                    <div style="font-size: 0px;"><abcd' . $i . '>' . $copy_link . '</abcd' . $i . '></div>
                                                    <a href="javascript:;" class="btn bs_growl_show" data-clipboard-action="copy"  data-clipboard-target="abcd' . $i . '"><i class="fa fa-clipboard"></i> Copy ' . $invoice_type . ' Link</a>
                                                </li>
                                                <li>
                                                    <a href="https://api.whatsapp.com/send?text=' . $copy_link . '" target="_BLAK" class="btn"><i class="fa fa-whatsapp"></i> Share on Whatsapp</a>
                                                </li>';
                        }

                        '</ul>
                        </div>';
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
                    $globalSearch[] = "`customer_code` LIKE " . "~%" . $str . "%~";
                    $globalSearch[] = "`invoice_number` LIKE " . "~%" . $str . "%~";
                    $globalSearch[] = "`company_name` LIKE " . "~%" . $str . "%~";
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
        if (self::$franchise_id > 0) {
            if ($where == '') {
                $where = " franchise_id=" . self::$franchise_id;
            } else {
                $where .= " AND franchise_id=" . self::$franchise_id;
            }
        }
        if (self::$invoice_type > 0) {
            $invoice_type = self::$invoice_type;
            if ($where == '') {
                $where = " invoice_type=" . $invoice_type;
            } else {
                $where .= " AND invoice_type=" . $invoice_type;
            }
        }

        if (self::$invoice_status != '') {
            $invoice_status = self::$invoice_status;
            if ($where == '') {
                $where = " is_paid=" . $invoice_status;
            } else {
                $where .= " AND is_paid=" . $invoice_status;
            }
        }


        if (count($columnSearch)) {
            $where = $where === '' ?
                implode(' AND ', $columnSearch) :
                $where . ' AND ' . implode(' AND ', $columnSearch);
        }

        //        if (!empty($_SESSION['payment_request_ids'])) {
        //            if ($where == '') {
        //                $where = ' payment_request_id in("' . implode('", "', $_SESSION['payment_request_ids']) . '")';
        //            } else {
        //                $where .= ' AND payment_request_id in("' . implode('", "', $_SESSION['payment_request_ids']) . '")';
        //            }
        //        }

        if (!$_SESSION['has_invoice_list_access']) {
            if ($where == '') {
                $where = ' payment_request_id in("' . implode('", "', $_SESSION['payment_request_ids']) . '")';
            } else {
                $where .= ' AND payment_request_id in("' . implode('", "', $_SESSION['payment_request_ids']) . '")';
            }
        }

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
    static function simple($request, $conn, $column_name, $table, $primaryKey, $columns, $headers)
    {
        $bindings = array();
        $db = self::db($conn);
        // Build the SQL query string from the request
        $limit = self::limit($request, $columns);
        $order = self::order($request, $columns);
        $where = self::filter($request, $columns, $bindings);
        // Main query to actually get the data
        $merchant_id = \App\Libraries\Encrypt::decode($_SESSION['merchant_id']);
        $user_id = \App\Libraries\Encrypt::decode($_SESSION['userid']);

        if ($_SESSION['user_role'] == 'Admin') {
            $privilegesArray = ['all' => 'full'];
        } else {
            $privilegesArray = json_decode($_SESSION['invoice_privileges_ids'], true);
            //            $paymentRequestPrivilieges = self::sql_exec(
            //                $db,
            //                "SELECT type_id, access
            //			 FROM   `briq_privileges` WHERE type = 'invoice' AND is_active = 1 AND merchant_id='$merchant_id' AND user_id='$user_id'"
            //            );
            //
            //            $privilegesArray = [];
            //            foreach ($paymentRequestPrivilieges as $paymentRequestPriviliege) {
            //                $privilegesArray[$paymentRequestPriviliege['type_id']] = $paymentRequestPriviliege['access'];
            //            }
        }

        $data = self::sql_exec(
            $db,
            $bindings,
            "call get_merchant_viewrequest('" . self::$merchant_id . "','" . self::$from_date . "','" . self::$to_date . "','" . self::$cycle_id . "'," . self::$bulk_id . ",'" . $where . "','" . $order . "','" . $limit . "');"
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
            "data" => self::data_output($columns, $data, $privilegesArray)
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
