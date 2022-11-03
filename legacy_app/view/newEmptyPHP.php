if ($_POST['template_type'] == 'simple') {
                $bill_datekey = array_search('4', $_POST['col_position']);
                $due_datekey = array_search('5', $_POST['col_position']);
                $amt_datekey = array_search('8', $_POST['col_position']);
                $late_datekey = array_search('9', $_POST['col_position']);
                $_POST['bill_date'] = $_POST['requestvalue'][$bill_datekey];
                $_POST['due_date'] = $_POST['requestvalue'][$due_datekey];
                $_POST['totalcost'] = $_POST['requestvalue'][$amt_datekey];
                $_POST['late_fee'] = $_POST['requestvalue'][$late_datekey];
                $billdate = new DateTime($_POST['bill_date']);
                $_POST['bill_cycle_name'] = $billdate->format('Y M');
                $bill_date_col = 4;
                $due_date_col = 5;
            } else {
                $cycle_datekey = array_search('4', $_POST['col_position']);
                $bill_datekey = array_search('5', $_POST['col_position']);
                $due_datekey = array_search('6', $_POST['col_position']);
                $_POST['bill_cycle_name'] = $_POST['requestvalue'][$cycle_datekey];
                $_POST['bill_date'] = $_POST['requestvalue'][$bill_datekey];
                $_POST['due_date'] = $_POST['requestvalue'][$due_datekey];
                $_POST['late_fee'] = 0;
                $bill_date_col = 5;
                $due_date_col = 6;
            }

            require_once PACKAGE . 'swipez/function/DataFunction.php';
            $int = 0;

            foreach ($invoicevalues['header'] as $column) {
                if ($column['function_id'] > 0) {
                    $function_details = $this->common->getSingleValue('column_function', 'function_id', $column['function_id']);
                    $function = new $function_details['php_class']();
                    if ($validate == NULL) {
                        $function->req_type = 'Invoice';
                    } else {
                        $function->req_type = 'Invoice_update';
                    }
                    $function->function_id = $column['function_id'];
                    $mapping_details = $this->common->getfunctionMappingDetails($column['column_id'], $column['function_id']);
                    if (!empty($mapping_details)) {
                        $function->param_name = $mapping_details['param'];
                        $function->param_value = $mapping_details['value'];
                    }
                    if ($column['column_position'] == $bill_date_col) {
                        $function->set($_POST['bill_date']);
                        $_POST['bill_date'] = $function->get('value');
                    }
                    if ($column['column_position'] == $due_date_col) {
                        $function->set($_POST['due_date']);
                        $_POST['due_date'] = $function->get('value');
                    }
                    if ($column['table_name'] == 'metadata') {
                        if ($req_id == NULL) {
                            $function->set($_POST['newvalues'][$int]);
                            $_POST['newvalues'][$int] = $function->get('value');
                        } else {
                            $function->set($_POST['existvalues'][$int]);
                            $_POST['existvalues'][$int] = $function->get('value');
                        }
                    }
                }
                if ($column['table_name'] == 'metadata') {
                    $int++;
                }
            }