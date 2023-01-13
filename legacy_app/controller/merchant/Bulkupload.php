<?php

use Illuminate\Support\Facades\Redis;

/**
 * This is bulkupload class for uploading invoices by excel sheet
 * @author Paresh
 */
class Bulkupload extends Controller
{

    private $invoice_numbers = array();

    function __construct()
    {
        parent::__construct();
        $this->validateSession('merchant');
        $this->view->selectedMenu = 'uploadlist';
        $this->view->js = array('template');
    }

    #Upload new excel sheet and display invoice template list for export 

    function newupload()
    {
        try {
            $this->validatePackage();
            $this->hasRole(2, 4);
            $this->view->selectedMenu = array(3, 20);
            $list = $this->common->getListValue('invoice_template', 'merchant_id', $this->merchant_id, 1, ' order by template_id desc');

            $list = $this->generic->getEncryptedList($list, 'encrypted_id', 'template_id');
            $this->view->hide_first_col = true;
            $this->smarty->assign("templatelist", $list);
            $this->view->title = 'Bulk upload';
            $this->smarty->assign('title', $this->view->title);

            $breadcrumbs['menu'] = 'collect_payments';
            $breadcrumbs['title'] = $this->view->title;
            $breadcrumbs['url'] = '/merchant/bulkupload/newupload';
            $this->session->set('breadcrumbs', $breadcrumbs);
            if (env('ENV') != 'LOCAL') {
                //menu list
                $mn1 = Redis::get('merchantMenuList' . $this->merchant_id);
                $item_list = json_decode($mn1, 1);

                $row_array['name'] = $this->view->title;
                $row_array['link'] = '/merchant/bulkupload/newupload';
                $item_list[] = $row_array;
                Redis::set('merchantMenuList' . $this->merchant_id, json_encode($item_list));
            }
            //Breadcumbs array start
            $breadcumbs_array = array(
                array('title' => 'Collect Payments ', 'url' => ''),
                array('title' => $this->view->title, 'url' => '')
            );
            $this->smarty->assign("links", $breadcumbs_array);
            //Breadcumbs array end

            $this->view->datatablejs = 'table-no-export';
            $this->view->header_file = ['bulkupload'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/bulkupload/newupload.tpl');
            $this->view->render('footer/bulkupload');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E236]Error while listing template Error: for merchant [' . $this->merchant_id . '] ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    #Export invoice template in excel format for bulk uploading invoice

    function excelExport($link)
    {
        try {
            if ($link == 'invoice') {
                $template_id = $_POST['template_id'];
            } else {
                $template_id = $this->encrypt->decode($link);
            }
            if ($this->session->get('customer_default_column')) {
                $customer_default_column = $this->session->get('customer_default_column');
            }
            $info = $this->common->getSingleValue('invoice_template', 'template_id', $template_id);
            $merchant_setting = $this->common->getSingleValue('merchant_setting', 'merchant_id', $this->merchant_id);
            $billing_profile = $this->common->getListValue('merchant_billing_profile', 'merchant_id', $this->merchant_id, 1);
            $info['template_id'] = $link;
            require_once CONTROLLER . 'InvoiceWrapper.php';
            $invoice = new InvoiceWrapper($this->common);
            $templateinfo = $invoice->getTemplateBreakup($template_id);
            $template_type = $info['template_type'];
            $update_date = $info['last_update_date'];
            $particular = json_decode($info['default_particular'], 1);
            $particular_column = json_decode($info['particular_column'], 1);
            $properties_column = json_decode($info['properties'], 1);

            $plugin = json_decode($info['plugin'], 1);
            $tax = json_decode($info['default_tax'], 1);
            if (empty($particular)) {
                $particular = array('Particular');
            }
            $type = ($_POST['type'] > 0) ? $_POST['type'] : 1;
            $sheet_type = ($_POST['sheet_type'] > 0) ? $_POST['sheet_type'] : 1;
            //v2 for country column lbl
            $link = $this->encrypt->encode($template_id . $update_date . $type . $sheet_type . 'v2');
            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setCreator("swipez")
                ->setLastModifiedBy("swipez")
                ->setTitle("swipez_Template")
                ->setSubject($link)
                ->setDescription("swipez invoice template");
            #create array of excel column
            $first = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
            $column = array();
            $column1 = array();
            $has_gst = 0;
            if ($template_type == 'travel') {
                foreach ($first as $s) {
                    $column[] = $s . '2';
                }
                foreach ($first as $f) {
                    foreach ($first as $s) {
                        $column[] = $f . $s . '2';
                    }
                }

                foreach ($first as $s) {
                    $column1[] = $s . '1';
                }
                foreach ($first as $f) {
                    foreach ($first as $s) {
                        $column1[] = $f . $s . '1';
                    }
                }
            } else {

                foreach ($first as $s) {
                    $column[] = $s . '1';
                }
                foreach ($first as $f) {
                    foreach ($first as $s) {
                        $column[] = $f . $s . '1';
                    }
                }
            }


            $int = 0;
            $currency = $this->session->get('currency');



            if ($sheet_type == 2 || $sheet_type == 4) {
                require_once CONTROLLER . 'merchant/Customer.php';
                $customer = new Customer();
                $customer_columns = $customer->download('columns');
                foreach ($customer_columns as $ccol) {
                    $objPHPExcel->getActiveSheet()->setCellValue($column[$int], $ccol);
                    $int = $int + 1;
                }
            } else {
                $lbl_customer_code = (isset($customer_default_column['customer_code'])) ? $customer_default_column['customer_code'] : 'Customer code';
                $objPHPExcel->getActiveSheet()->setCellValue($column[$int], $lbl_customer_code);
                $int = $int + 1;
            }

            if ($type == 3 || $type == 4) {
                if ($type == 4) {
                    $objPHPExcel->getActiveSheet()->setCellValue($column[$int], 'Estimate title');
                    $int = $int + 1;
                    $objPHPExcel->getActiveSheet()->setCellValue($column[$int], 'Auto-generate invoice (Yes/No)');
                    $int = $int + 1;
                }
                $objPHPExcel->getActiveSheet()->setCellValue($column[$int], 'Mode (Month/Day/Week/Year)');
                $int = $int + 1;
                $objPHPExcel->getActiveSheet()->setCellValue($column[$int], 'Repeat every');
                $int = $int + 1;
                $objPHPExcel->getActiveSheet()->setCellValue($column[$int], 'End mode (Never/Occurence/End date)');
                $int = $int + 1;
                $objPHPExcel->getActiveSheet()->setCellValue($column[$int], 'End value');
                $int = $int + 1;
            }

            if ($type == 2) {
                $objPHPExcel->getActiveSheet()->setCellValue($column[$int], 'Estimate title');
                $int = $int + 1;
                $objPHPExcel->getActiveSheet()->setCellValue($column[$int], 'Auto-generate invoice (Yes/No)');
                $int = $int + 1;
            }

            if ($plugin['has_franchise'] == 1) {
                $objPHPExcel->getActiveSheet()->setCellValue($column[$int], 'Franchise id');
                $int = $int + 1;
                $objPHPExcel->getActiveSheet()->setCellValue($column[$int], 'Franchise name on invoice(1 Or 0)');
                $int = $int + 1;
            }
            if ($plugin['has_vendor'] == 1) {
                $objPHPExcel->getActiveSheet()->setCellValue($column[$int], 'Vendor id');
                $int = $int + 1;
                $objPHPExcel->getActiveSheet()->setCellValue($column[$int], 'Commision type (Percentage/Fixed)');
                $int = $int + 1;
                $objPHPExcel->getActiveSheet()->setCellValue($column[$int], 'Commision value');
                $int = $int + 1;
            }

            foreach ($templateinfo['main_header'] as $row) {
                if ($row['default_column_value'] == 'Custom') {
                    $objPHPExcel->getActiveSheet()->setCellValue($column[$int], $row['column_name']);
                    $int = $int + 1;
                }
            }
            foreach ($templateinfo['header'] as $row) {
                $exit = 0;
                if ($row['function_id'] == 9) {
                    $mapping_details = $this->common->getfunctionMappingDetails($row['column_id'], $row['function_id']);
                    if ($mapping_details['param'] == 'system_generated') {
                        $exit = 1;
                    }
                }
                if ($row['function_id'] == 15) {
                    $einvoice_types = [];
                    $einvoice_type = $this->model->getStatusList('einvoice_type');
                    foreach ($einvoice_type as $crow) {
                        $einvoice_types[] = $crow['config_value'];
                    }
                    $row['column_name'] = $row['column_name'] . '(' . implode(',', $einvoice_types) . ')';
                }

                if ($row['function_id'] == 4) {
                    $mapping_details = $this->common->getfunctionMappingDetails($row['column_id'], $row['function_id']);
                    if ($mapping_details['param'] == 'auto_calculate') {
                        $exit = 1;
                    }
                }
                if ($exit == 0) {
                    if ($row['datatype'] == 'date') {
                        $objPHPExcel->getActiveSheet()->setCellValue($column[$int], $row['column_name']);
                    } else if ($row['datatype'] == 'percent') {
                        $objPHPExcel->getActiveSheet()->setCellValue($column[$int], $row['column_name'] . ' (%)');
                    } else {
                        $objPHPExcel->getActiveSheet()->setCellValue($column[$int], $row['column_name']);
                    }
                    $int = $int + 1;
                }

                //check dynamic billing period & previous due plugin for subscription
                if ($type == 3 || $type == 4) {
                    if ($row['function_id'] == 10) {
                        $objPHPExcel->getActiveSheet()->removeColumn($column[$int], 'Billing period');
                        $int = $int - 1;
                        $objPHPExcel->getActiveSheet()->setCellValue($column[$int], 'Billing period start date');
                        $int = $int + 1;
                        $objPHPExcel->getActiveSheet()->setCellValue($column[$int], 'Add duration');
                        $int = $int + 1;
                        $objPHPExcel->getActiveSheet()->setCellValue($column[$int], 'Duration period (Days/Month)');
                        $int = $int + 1;
                    }
                    if ($row['function_id'] == 4) {
                        $objPHPExcel->getActiveSheet()->setCellValue($column[$int], 'Carry forward dues (Yes/No)');
                        $int = $int + 1;
                    }
                }
            }
            if (count($currency) > 1) {
                $objPHPExcel->getActiveSheet()->setCellValue($column[$int], 'Currency');
                $int = $int + 1;
            }
            if (count($billing_profile) > 1) {
                $objPHPExcel->getActiveSheet()->setCellValue($column[$int], 'Billing profile name');
                $int = $int + 1;
            }
            foreach ($templateinfo['BDS'] as $row) {
                if ($row['datatype'] == 'date') {
                    $objPHPExcel->getActiveSheet()->setCellValue($column[$int], $row['column_name']);
                } else if ($row['datatype'] == 'percent') {
                    $objPHPExcel->getActiveSheet()->setCellValue($column[$int], $row['column_name'] . ' (%)');
                } else {
                    $objPHPExcel->getActiveSheet()->setCellValue($column[$int], $row['column_name']);
                }
                $int = $int + 1;
            }
            if ($template_type == 'travel') {
                $data_int = $int;
                $tint = $int;
                $shades = array('87CEFA', '5ADA5A', '5959D9', 'D9D959', '9FE2BF', 'DE3163');
                $shades_array = array();
                $sint = 0;
                foreach ($properties_column as $key => $pr) {
                    if (isset($pr['column'])) {
                        $rate_exist = 0;
                        $gst_exist = 0;
                        $countcol = 0;
                        foreach ($pr['column'] as $tprk => $tpr) {
                            if ($tprk == 'gst') {
                                $tpr = 'GST [5,12,18,28]';
                                $gst_exist = 1;
                                $has_gst = 1;
                            }
                            if ($tprk != 'sr_no') {
                                if ($key == 'rate') {
                                    $objPHPExcel->getActiveSheet()->setCellValue($column[$int], $tpr);
                                    $int++;
                                    $countcol++;
                                    $rate_exist = 1;
                                } elseif ($key == 'total_amount') {
                                    if ($rate_exist == 0) {
                                        $objPHPExcel->getActiveSheet()->setCellValue($column[$int], $tpr);
                                        $int++;
                                        $countcol++;
                                    }
                                } elseif ($key == 'tax_amount') {
                                    if ($gst_exist == 0) {
                                        $objPHPExcel->getActiveSheet()->setCellValue($column[$int], $tpr);
                                        $int++;
                                        $countcol++;
                                    }
                                } else {
                                    $objPHPExcel->getActiveSheet()->setCellValue($column[$int], $tpr);
                                    $int++;
                                    $countcol++;
                                }
                            }
                        }
                        $objPHPExcel->getActiveSheet()->setCellValue($column1[$tint], $pr['title']);
                        $objPHPExcel->getActiveSheet()->mergeCells($column1[$tint] . ':' . $column1[$tint + $countcol - 1]);
                        $style = array(
                            'alignment' => array(
                                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
                            ),
                            'fill' => array(
                                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                'color' => array('rgb' => $shades[$sint])
                            )
                        );
                        $objPHPExcel->getActiveSheet()->getStyle($column1[$tint] . ":" . $column1[$tint + $countcol - 1])->applyFromArray($style);
                        $shades_array[$shades[$sint]] = $column[$tint] . ":" . $column[$tint + $countcol - 1];
                        $sint++;
                        $tint = $tint + $countcol;
                    }
                }
            } else {
                $data_int = $int;
                foreach ($particular as $row) {
                    $rate_exist = 0;
                    $gst_exist = 0;
                    foreach ($particular_column as $key => $pr) {
                        if ($key == 'gst') {
                            $pr = 'GST [5,12,18,28]';
                            $gst_exist = 1;
                            $has_gst = 1;
                        }
                        if ($key != 'sr_no') {
                            if ($key == 'rate') {
                                $objPHPExcel->getActiveSheet()->setCellValue($column[$int], $pr);
                                $int++;
                                $rate_exist = 1;
                            } elseif ($key == 'total_amount') {
                                if ($rate_exist == 0) {
                                    $objPHPExcel->getActiveSheet()->setCellValue($column[$int], $pr);
                                    $int++;
                                }
                            } elseif ($key == 'tax_amount') {
                                if ($gst_exist == 0) {
                                    $objPHPExcel->getActiveSheet()->setCellValue($column[$int], $pr);
                                    $int++;
                                }
                            } else {
                                $objPHPExcel->getActiveSheet()->setCellValue($column[$int], $pr);
                                $int++;
                            }
                        }
                    }
                }
            }

            foreach ($tax as $tax_id) {
                $tax_det = $this->common->getSingleValue('merchant_tax', 'tax_id', $tax_id);
                if ($has_gst == 0 || !in_array($tax_det['tax_type'], array(1, 2, 3))) {
                    if ($tax_det['tax_type'] != 5) {
                        $column_name = $tax_det['tax_name'] . ' [' . $tax_det['percentage'] . ']';
                        $objPHPExcel->getActiveSheet()->setCellValue($column[$int], $column_name . '/Applicable on');
                        $int++;
                    }
                }
            }

            foreach ($plugin['deductible'] as $row) {
                $column_name = $row['tax_name'];
                $objPHPExcel->getActiveSheet()->setCellValue($column[$int], $column_name . '/Deduct in %');
                $int++;
                $objPHPExcel->getActiveSheet()->setCellValue($column[$int], $column_name . '/Applicable on');
                $int++;
            }

            if ($plugin['has_prepaid'] == 1) {
                $objPHPExcel->getActiveSheet()->setCellValue($column[$int], 'Advance received');
                $int = $int + 1;
            }

            if ($plugin['has_partial'] == 1) {
                $objPHPExcel->getActiveSheet()->setCellValue($column[$int], 'Partial enable (Yes/No)');
                $int = $int + 1;
                $objPHPExcel->getActiveSheet()->setCellValue($column[$int], 'Partial minimum amount');
                $int = $int + 1;
            }

            if ($plugin['has_online_payments'] == 1) {
                $objPHPExcel->getActiveSheet()->setCellValue($column[$int], 'Online payments enable (Yes/No)');
                $int = $int + 1;
            }

            $lbl_customer = (isset($customer_default_column['customer'])) ? $customer_default_column['customer'] : 'Customer';


            $objPHPExcel->getActiveSheet()->setCellValue($column[$int], 'Narrative');

            $int++;
            $objPHPExcel->getActiveSheet()->setCellValue($column[$int], 'Notify ' . $lbl_customer . '(Yes/No)');

            if ($merchant_setting['product_taxation'] == 3) {
                $int++;
                $objPHPExcel->getActiveSheet()->setCellValue($column[$int], 'Product Taxation (inclusive/exclusive)');
            }

            if ($plugin['has_e_invoice'] == 1) {
                $int++;
                $objPHPExcel->getActiveSheet()->setCellValue($column[$int], 'Create e-Invoice(Yes/No)');
                $int = $int + 1;
                $objPHPExcel->getActiveSheet()->setCellValue($column[$int], 'Send e-Invoice to ' . $lbl_customer . '(Yes/No)');
            }


            $objPHPExcel->getDefaultStyle()->getFont()->setName('verdana')
                ->setSize(10);
            $objPHPExcel->getActiveSheet()->setTitle('Invoice template');
            $fcol = 'A1';
            if ($template_type == 'travel') {
                $fcol = 'A2';
            }
            $objPHPExcel->getActiveSheet()->getStyle($fcol . ':' . $column[$int])->applyFromArray(
                array(
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => 'AAAADD')
                    )
                )
            );
            if (!empty($shades_array)) {
                foreach ($shades_array as $k => $v) {
                    $objPHPExcel->getActiveSheet()->getStyle($v)->applyFromArray(
                        array(
                            'fill' => array(
                                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                'color' => array('rgb' => $k)
                            )
                        )
                    );
                }
            }

            $int++;
            $autosize = 0;
            while ($autosize < $int) {
                $objPHPExcel->getActiveSheet()->getColumnDimension(substr($column[$autosize], 0, -1))->setAutoSize(true);
                $autosize++;
            }

            if ($sheet_type == 3 || $sheet_type == 4) {
                require_once MODEL . 'merchant/CustomerModel.php';
                $customer_model = new CustomerModel();
                require_once MODEL . 'merchant/ProductModel.php';
                $product_model = new ProductModel();

                $merchant_setting = $this->common->getSingleValue('merchant_setting', 'merchant_id', $merchant_id);
                $auto_generate = $merchant_setting['customer_auto_generate'];

                $customer_list = $customer_model->getCustomerList($this->merchant_id, '', 0, '');
                $product  = [];
                foreach ($particular as $key1 => $row) {
                    $product_Detail = $product_model->getProductDetailsByName($row, $this->merchant_id);
                    foreach ($particular_column as $key => $pr) {
                        if ($key == 'item') {
                            $product[$key1][$key] = $product_Detail['product_name'];
                        } else if ($key == 'gst') {
                            if ($product_Detail['gst_percent'] == '0') {
                                $product[$key1][$key] = '';
                            } else {
                                $product[$key1][$key] = $product_Detail['gst_percent'];
                            }
                        } else if ($key == 'sac_code') {
                            $product[$key1][$key] = $product_Detail['sac_code'];
                        } else if ($key == 'description') {
                            $product[$key1][$key] = $product_Detail['description'];
                        } else if ($key == 'total_amount') {
                            $product[$key1][$key] = $product_Detail['price'];
                        } else if ($key == 'rate') {
                            $product[$key1][$key] = $product_Detail['price'];
                        } else {
                            $product[$key1][$key] = $product_Detail[$key];
                        }
                    }
                }

                $row = 2;
                if ($template_type == 'travel') {
                    $row = 3;
                }
                foreach ($customer_list as $cust_data) {
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $cust_data["customer_code"]);

                    $new_data_int = $data_int;
                    unset($particular_column["sr_no"]);
                    foreach ($particular as $key1 => $row1) {
                        unset($product[$key1]["sr_no"]);
                        foreach ($particular_column as $key => $pr) {
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($new_data_int, $row, $product[$key1][$key]);
                            $new_data_int++;
                        }
                    }
                    $row++;
                }

                if ($sheet_type == 4) {
                    require_once CONTROLLER . 'merchant/Customer.php';
                    $customer = new Customer();

                    $customer_columns = $customer->download('columns');
                    $row = 2;
                    foreach ($customer_list as $cust_data) {
                        $cust_rows = 0;
                        foreach ($customer_columns as $ccol) {
                            if ($ccol == 'Customer name') {
                                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($cust_rows, $row, $cust_data["name"]);
                                $cust_rows++;
                            } else if ($ccol == 'Email ID') {
                                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($cust_rows, $row, $cust_data["email"]);
                                $cust_rows++;
                            } else if ($ccol == 'Mobile No') {
                                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($cust_rows, $row, $cust_data["mobile"]);
                                $cust_rows++;
                            } else if ($ccol == 'Address') {
                                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($cust_rows, $row, $cust_data["__Address"]);
                                $cust_rows++;
                            } else if ($ccol == 'City') {
                                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($cust_rows, $row, $cust_data["__City"]);
                                $cust_rows++;
                            } else if ($ccol == 'State') {
                                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($cust_rows, $row, $cust_data["__State"]);
                                $cust_rows++;
                            } else if ($ccol == 'Zipcode') {
                                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($cust_rows, $row, $cust_data["__Zipcode"]);
                                $cust_rows++;
                            }
                        }
                        $row++;
                    }
                }
            }

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $info['template_name'] . '.xlsx"');
            header('Cache-Control: max-age=0');
            // If you're serving to IE 9, then the following may be needed
            header('Cache-Control: max-age=1');
            // If you're serving to IE over SSL, then the following may be needed
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
            header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header('Pragma: public'); // HTTP/1.0
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save('php://output');
            $objPHPExcel->disconnectWorksheets();
            unset($objPHPExcel);
            exit;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E235]Error while export excel Error: for template [' . $template_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    #Delete uploaded excel by merchant (change status as delete) and move excel file to delete folder 

    function delete($bulk_id, $type = 1)
    {
        try {
            if ($type == 3) {
                $this->hasRole(3, 20);
                $repath = '/merchant/vendor/bulkupload/vendor';
            } elseif ($type == 4) {
                $this->hasRole(3, 22);
                $repath = '/merchant/vendor/bulkupload/transfer';
            } elseif ($type == 2) {
                $this->hasRole(3, 15);
                $repath = '/merchant/customer/bulkupload';
            } elseif ($type == 'transaction') {
                $this->hasRole(3, 9);
                $repath = '/merchant/transaction/bulkupload';
            } elseif ($type == 6) {
                $this->hasRole(3, 21);
                $repath = '/merchant/franchise/bulkupload';
            } elseif ($type == 7) {
                $this->hasRole(3, 23);
                $repath = '/merchant/gst/bulkupload';
            } elseif ($type == 8) {
                $this->hasRole(3, 23);
                $repath = '/merchant/vendor/bulkupload/beneficiary';
            } elseif ($type == 10) {
                $repath = '/merchant/expense/bulkupload';
            } else {
                $this->hasRole(3, 4);
                $repath = '/merchant/bulkupload/viewlist';
            }

            $merchant_id = $this->session->get('merchant_id');
            $bulk_id = $this->encrypt->decode($bulk_id);
            $folder = 'staging';
            $detail = $this->model->getBulkuploaddetail($merchant_id, $bulk_id);
            if (!empty($detail)) {
                if ($detail['status'] == 1) {
                    $folder = 'error';
                }
                $this->model->updateBulkUploadStatus($detail['bulk_upload_id'], 6);
                $this->model->moveExcelFile($merchant_id, $folder, $detail['system_filename']);
                $this->session->set("successMessage", ' Excel deleted successfully.');
                header('Location: ' . $repath);
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E63]Error while delete bulk upload Error: for merchant [' . $merchant_id . '] and bulk id [' . $bulk_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    #Download uploaded excel by merchant 

    function download($bulk_id)
    {
        try {
            $merchant_id = $this->session->get('merchant_id');
            $bulk_id = $this->encrypt->decode($bulk_id);
            $detail = $this->model->getBulkuploaddetail($merchant_id, $bulk_id);
            if ($detail['status'] == 1) {
                $folder = 'error';
            } elseif ($detail['status'] == 6) {
                $folder = 'deleted';
            } else {
                $folder = 'staging';
            }
            if (!empty($detail)) {
                $base = ($this->view->env == 'LOCAL') ? 'http://' : 'https://';
                $file = $base . $_SERVER['SERVER_NAME'] . '/uploads/Excel/' . $merchant_id . '/' . $folder . '/' . $detail['system_filename'];
                //$download_name = basename($file);
                header("Location: " . $file . "");
                exit;
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E264]Error while download excel Error: for merchant [' . $merchant_id . '] and bulk_id [' . $bulk_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    #Bulk upload list with action 

    public function viewlist()
    {
        try {
            $this->hasRole(1, 4);
            $merchant_id = $this->session->get('merchant_id');
            $this->view->selectedMenu = array(5, 29);
            $last_date = $this->getLast_date();
            $current_date = date('d M Y');

            $getRediscache = Redis::get('merchantSearchCriteria'.$this->merchant_id);
            $redis_items = json_decode($getRediscache, 1); 

            if (isset($_POST['from_date'])) {
                $from_date = $_POST['from_date'];
                $to_date = $_POST['to_date'];

                $redis_items['bulk_invoice_list']['search_param'] = $_POST;
                Redis::set('merchantSearchCriteria'.$this->merchant_id, json_encode($redis_items));
            } else {
                $from_date = $last_date;
                $to_date = $current_date;
            }

            //find last search criteria into Redis 
            if(isset($redis_items['bulk_invoice_list']['search_param']) && $redis_items['bulk_invoice_list']['search_param']!=null) {
                $from_date = $redis_items['bulk_invoice_list']['search_param']['from_date'];
                $to_date = $redis_items['bulk_invoice_list']['search_param']['to_date'];
            }
            //$this->view->showLastRememberSearchCriteria = false;
            
            $fromdate = new DateTime($from_date);
            $todate = new DateTime($to_date);
            $list = $this->model->getBulkuploadList($merchant_id, $fromdate->format('Y-m-d'), $todate->format('Y-m-d'));
            $int = 0;
            foreach ($list as $item) {
                $list[$int]['bulk_id'] = $this->encrypt->encode($item['bulk_upload_id']);
                $list[$int]['created_at'] = $this->generic->formatTimeString($item['created_date']);
                $int++;
            }
            $this->view->hide_first_col = true;
            $this->smarty->assign("from_date", $this->generic->formatDateString($from_date));
            $this->smarty->assign("to_date", $this->generic->formatDateString($to_date));
            $this->smarty->assign("session_date_format", $this->session->get('default_date_format'));
            $_SESSION["session_date_format"] = $this->session->get('default_date_format');
            $this->smarty->assign("bulklist", $list);
            $this->view->title = 'Bulk uploaded invoices';
            $this->view->list_name = 'bulk_invoice_list';
            $this->smarty->assign('title', $this->view->title);

            //Breadcumbs array start
            $breadcumbs_array = array(
                array('title' => 'Sales', 'url' => ''),
                array('title' => $this->view->title, 'url' => '')
            );
            $this->smarty->assign("links", $breadcumbs_array);
            //Breadcumbs array end

            $this->view->canonical = 'merchant/bulkupload/viewlist';
            $this->view->datatablejs = 'table-small-no-export-statesave';  //table-small-no-export old value
            $this->view->header_file = ['list'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/bulkupload/list.tpl');
            $this->view->render('footer/list');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E237]Error while payment request list initiate Error: for merchant [' . $merchant_id . ']  ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    #Bulk invoice list with filter

    public function bulkinvoice($link)
    {
        try {
            $this->hasRole(1, 4);
            $user_id = $this->session->get('userid');
            $bulk_id = $this->encrypt->decode($link);
            if (!is_numeric($bulk_id)) {
                $this->setInvalidLinkError();
            }
            $merchant_id = $this->session->get('merchant_id');
            $bulk_detail = $this->model->getBulkuploaddetail($merchant_id, $bulk_id);
            if (empty($bulk_detail)) {
                $this->setInvalidLinkError();
            }

            $status = $this->common->getRowValue('status', 'bulk_upload', 'bulk_upload_id', $bulk_id);
            if ($status == 5) {
                $this->bulkrequest($link);
                die();
            }
            $_SESSION['_bulk_id'] = $bulk_id;
            $this->view->selectedMenu = 'uploadlist';
            $this->setAjaxDatatableSession();
            $this->view->ajaxpage = 'bulkrequest.php';
            $this->view->selectedMenu = array(5, 29);
            $this->smarty->assign("list", $list);
            //$this->smarty->assign("title", 'Bulk upload request <small>(' . $bulk_detail['merchant_filename'] . ')</small>');
            $this->view->title = 'Bulk upload request';
            $this->smarty->assign("title", 'Bulk upload request');

            //Breadcumbs array start
            $breadcumbs_array = array(
                array('title' => 'Sales', 'url' => ''),
                array('title' => 'Bulk uploaded invoices', 'url' => '/merchant/bulkupload/viewlist'),
                array('title' => $this->view->title, 'url' => '')
            );
            $this->smarty->assign("links", $breadcumbs_array);
            //Breadcumbs array end

            $this->view->header_file = ['list'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/bulkupload/bulklist.tpl');
            $this->view->render('footer/request_list');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E239]Error while payment request list initiate Error: for merchant [' . $user_id . '] and bulk id [' . $bulk_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function bulkrequest($link)
    {
        try {
            $this->hasRole(1, 4);
            $merchant_id = $this->session->get('merchant_id');
            $bulk_id = $this->encrypt->decode($link);

            if (!is_numeric($bulk_id)) {
                $this->setInvalidLinkError();
            }
            $bulk_detail = $this->model->getBulkuploaddetail($merchant_id, $bulk_id);
            if (empty($bulk_detail)) {
                $this->setInvalidLinkError();
            }
            $user_id = $this->session->get('userid');
            $fromdate = new DateTime();
            $todate = new DateTime();
            require_once MODEL . 'merchant/ReportModel.php';
            $reportmodel = new ReportModel();
            $column_list = $reportmodel->getColumnList($user_id);
            $this->smarty->assign("column_list", $column_list);
            $column_select = (isset($_POST['column_name'])) ? $_POST['column_name'] : array();

            $this->smarty->assign("column_select", $column_select);
            $_SESSION['display_column'] = $column_select;
            $_SESSION['_from_date'] = $fromdate->format('Y-m-d');
            $_SESSION['_to_date'] = $todate->format('Y-m-d');
            $_SESSION['_cycle_name'] = '';
            $_SESSION['_bulk_id'] = $bulk_id;
            $_SESSION['_type'] = 3;
            if ($this->session->get('customer_default_column')) {
                $columns = $this->session->get('customer_default_column');
                $_SESSION['_customer_code_text'] = $columns['customer_code'];
            }
            $this->view->hide_first_col = true;
            $this->view->hide_col_index = 4;
            //$this->smarty->assign("title", "View sent requests  <small>(" . $bulk_detail['merchant_filename'] . ')</small>');
            $this->smarty->assign("title", "View sent requests");
            $this->view->selectedMenu = 'uploadlist';
            $this->setAjaxDatatableSession();
            $this->view->ajaxpage = 'paymentrequest.php';
            $this->view->title = 'View sent requests';

            //Breadcumbs array start
            $breadcumbs_array = array(
                array('title' => 'Sales', 'url' => ''),
                array('title' => 'Bulk uploaded invoices', 'url' => '/merchant/bulkupload/viewlist'),
                array('title' => $this->view->title, 'url' => '')
            );
            $this->smarty->assign("links", $breadcumbs_array);
            //Breadcumbs array end

            $this->view->header_file = ['list'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/bulkupload/bulk_request.tpl');
            $this->view->render('footer/request_list');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EB241]Error while bulk request viewlist initiate Error: for merchant [' . $merchant_id . '] and  payment request id [' . $payment_request_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function view($link)
    {

        try {
            $this->hasRole(1, 4);
            $user_id = $this->session->get('userid');
            $payment_request_id = $this->encrypt->decode($link);
            $info = $this->model->getPaymentRequestDetails($payment_request_id, $this->merchant_id);
            if ($info['message'] != 'success' || $info['template_type'] == 'event') {

                SwipezLogger::error(__CLASS__, '[E008]Error while geting invoice details. for merchant [' . $this->merchant_id . '] and for payment request id [' . $payment_request_id . ']');
                $this->setInvalidLinkError();
            }

            if (!empty($info['design_name'])) {
                header('Location: /merchant/invoice/bulkview/' . $link);
                die();
            }

            require_once CONTROLLER . 'InvoiceWrapper.php';
            $invoice = new InvoiceWrapper($this->common);
            $smarty = $invoice->asignSmarty($info, array(), $payment_request_id, 'Invoice', 'merchant', 1);
            $smarty['user_name'] = $this->session->get('user_name');
            foreach ($smarty as $key => $value) {
                $this->smarty->assign($key, $value);
            }

            $this->view->title = 'View Invoice';
            //Breadcumbs array start
            $breadcumbs_array = array(
                array('title' => 'Sales', 'url' => ''),
                array('title' => 'Bulk uploaded invoices', 'url' => '/merchant/bulkupload/viewlist'),
                array('title' => $this->view->title, 'url' => '')
            );
            $this->smarty->assign("links", $breadcumbs_array);
            //Breadcumbs array end

            $this->view->minwidth = '';


            $template_types = array('isp', 'franchise', 'nonbrandfranchise', 'travel');
            if (in_array($info['template_type'], $template_types)) {
                $template_type = $info['template_type'];
            } else {
                $template_type = 'detail';
            }

            $this->view->selectedMenu = array(5, 29);
            $this->view->header_file = ['list'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/paymentrequest/invoice_' . $template_type . '.tpl');
            $this->smarty->display(VIEW . 'merchant/bulkupload/invoice_footer.tpl');
            $this->view->render('footer/invoice');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E241]Error while bulk payment request view initiate Error: for merchant [' . $user_id . '] and  payment request id [' . $payment_request_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    #update bulk invoice initiate

    function invoiceupdate()
    {
        try {
            $merchant_id = $this->session->get('merchant_id');
            if (empty($_POST)) {
                header("Location:/merchant/paymentrequest/viewlist");
            }

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

            $template_id = $this->encrypt->decode($_POST['template_id']);
            $invoicevalues = $this->common->getTemplateBreakup($template_id);
            require_once CONTROLLER . 'InvoiceWrapper.php';
            $invoice = new InvoiceWrapper($this->common);
            $invoicevalues = $invoice->getTemplateBreakup($invoicevalues);

            require_once PACKAGE . 'swipez/function/DataFunction.php';
            $int = 0;
            foreach ($invoicevalues['header'] as $column) {
                if ($column['function_id'] > 0) {
                    $function_details = $this->common->getSingleValue('column_function', 'function_id', $column['function_id']);
                    $function = new $function_details['php_class']();
                    $function->req_type = 'Staging invoice';
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

                        $function->set($_POST['existvalues'][$int]);
                        $_POST['existvalues'][$int] = $function->get('value');
                    }
                }
                if ($column['table_name'] == 'metadata') {
                    $int++;
                }
            }
            $_POST['late_fee'] = ($_POST['late_fee'] > 0) ? $_POST['late_fee'] : 0;

            $billdate = new DateTime($_POST['bill_date']);
            $duedate = new DateTime($_POST['due_date']);

            if (!isset($_POST['newvalues'])) {
                $_POST['newvalues'] = array();
            }
            if (!isset($_POST['ids'])) {
                $_POST['ids'] = array();
            }

            $supplier_id = (isset($_POST['supplier_id'])) ? $_POST['supplier_id'] : 0;
            $franchise_id = (isset($_POST['franchise_id'])) ? $_POST['franchise_id'] : 0;
            $vendor_id = (isset($_POST['vendor_id'])) ? $_POST['vendor_id'] : 0;
            $_POST['advance'] = (isset($_POST['advance']) && $_POST['advance'] != '') ? $_POST['advance'] : 0;
            $supplier = (isset($_POST['supplier'])) ? $_POST['supplier'] : '';

            if (!isset($_POST['existvalues'])) {
                $_POST['existvalues'] = array();
            }
            if (!isset($_POST['existids'])) {
                $_POST['existids'] = array();
            }


            if (!isset($_POST['totalcost'])) {
                $_POST['totalcost'] = 0;
            }

            if (!isset($_POST['totaltax'])) {
                $_POST['totaltax'] = 0;
            }
            $_POST['narrative'] = (isset($_POST['narrative'])) ? $_POST['narrative'] : '';

            $_POST['previous_dues'] = ($_POST['previous_dues'] > 0) ? $_POST['previous_dues'] : 0;
            $_POST['last_payment'] = ($_POST['last_payment'] > 0) ? $_POST['last_payment'] : 0;
            $_POST['adjustment'] = ($_POST['adjustment'] > 0) ? $_POST['adjustment'] : 0;
            $franchise_name_invoice = ($_POST['franchise_name_invoice'] == 1) ? 1 : 0;
            require CONTROLLER . 'Paymentvalidator.php';
            $validator = new Paymentvalidator($this->model);
            $validator->validateInvoice($this->user_id);
            $hasErrors = $validator->fetchErrors();
            $payment_request_id = $this->encrypt->decode($_POST['payment_request_id']);
            if ($hasErrors == false) {
                $result = $this->model->updateInvoice($payment_request_id, $this->session->get('userid'), $_POST['customer_id'], $_POST['existids'], $_POST['existvalues'], $billdate->format('Y-m-d'), $duedate->format('Y-m-d'), $_POST['bill_cycle_name'], $_POST['newvalues'], $_POST['ids'], $_POST['narrative'], $_POST['totalcost'], $_POST['totaltax'], $_POST['particular_total'], $_POST['tax_total'], $_POST['previous_dues'], $_POST['last_payment'], $_POST['adjustment'], $supplier_id, $supplier, $_POST['late_fee'], $_POST['advance'], $_POST['expiry_date'], $_POST['notify_patron'], $_POST['coupon_id'], $franchise_id, $franchise_name_invoice, $vendor_id, $_POST['is_partial'], $_POST['partial_min_amount']);
                if ($result['Message'] == 'success') {
                    $this->view->title = 'Upload invoice updated';
                    $this->view->render('header/app');
                    $this->smarty->display(VIEW . 'merchant/bulkupload/success.tpl');
                    $this->view->render('footer/mDashboard');
                } else {

                    SwipezLogger::error(__CLASS__, '[E244]Error update payment request Error: for merchant [' . $merchant_id . '] and ' . $result['Message']);
                    $this->setGenericError();
                }
            } else {
                $template_id = $this->encrypt->decode($_POST['template_id']);
                $this->smarty->assign("hasErrors", $hasErrors);
                $this->update($template_id);
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E245]Error while sending payment request Error: for merchant [' . $merchant_id . '] and payment request id [' . $payment_request_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function prepareupload($invoicevalues)
    {
        try {

            $cycle_datekey = array_search('4', $_POST['col_position']);
            $bill_datekey = array_search('5', $_POST['col_position']);
            $due_datekey = array_search('6', $_POST['col_position']);
            $_POST['bill_cycle_name'] = $_POST['values'][$cycle_datekey];
            $_POST['bill_date'] = $_POST['values'][$bill_datekey];
            $_POST['due_date'] = $_POST['values'][$due_datekey];
            $_POST['late_fee'] = 0;
            $bill_date_col = 5;
            $due_date_col = 6;
            require_once PACKAGE . 'swipez/function/DataFunction.php';
            $int = 0;
            foreach ($invoicevalues['header'] as $column) {
                if ($column['function_id'] > 0) {
                    $function_details = $this->common->getSingleValue('column_function', 'function_id', $column['function_id']);
                    $function = new $function_details['php_class']();
                    $function->req_type = 'Staging invoice';
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
                        $function->set($_POST['values'][$int]);
                        $_POST['values'][$int] = $function->get('value');
                    }
                }
                $int++;
            }
            if ($_POST['bill_date'] != '' && $_POST['bill_date'] != 'NA') {
                $billdate = new DateTime($_POST['bill_date']);
                $_POST['bill_date'] = $billdate->format('Y-m-d');
            }
            if ($_POST['due_date'] != '' && $_POST['due_date'] != 'NA') {
                $duedate = new DateTime($_POST['due_date']);
                $_POST['due_date'] = $duedate->format('Y-m-d');
            }

            $template_id = $this->encrypt->encode($_POST['template_id']);
            $_POST['template_id'] = $template_id;

            if (!isset($_POST['newvalues'])) {
                $_POST['newvalues'] = array();
            }
            if (!isset($_POST['ids'])) {
                $_POST['ids'] = array();
            }

            if (!isset($_POST['totalcost'])) {
                $_POST['totalcost'] = 0;
            }

            if (!isset($_POST['totaltax'])) {
                $_POST['totaltax'] = 0;
            }

            $_POST['previous_dues'] = ($_POST['previous_dues'] > 0) ? $_POST['previous_dues'] : 0;
            $_POST['last_payment'] = ($_POST['last_payment'] > 0) ? $_POST['last_payment'] : 0;
            $_POST['adjustment'] = ($_POST['adjustment'] > 0) ? $_POST['adjustment'] : 0;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E267]Error while prepair upload Error: for template id [' . $template_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function validateuploadinvoice($templateinfo, $type = 1, $profiles = [])
    {
        try {
            $this->prepareupload($templateinfo);
            require_once CONTROLLER . 'merchant/Uploadvalidator.php';
            require_once MODEL . 'merchant/CustomerModel.php';
            $customer_model = new CustomerModel();
            $validator = new Uploadvalidator($customer_model);
            $merchant_id = $this->session->get('merchant_id');
            $currency = $this->session->get('currency');
            $einvoice_types = [];
            if (isset($_POST['einvoice_type'])) {
                $einvoice_type = $this->model->getStatusList('einvoice_type');
                foreach ($einvoice_type as $row) {
                    $einvoice_types[] = $row['config_value'];
                }
            }

            if ($type == 3 || $type == 4) {
                $validator->validateBulkSubscription($merchant_id, $this->user_id, $currency, $einvoice_types, $profiles);
                $hasErrors = $validator->fetchErrors();
            } else {
                $validator->validateBulkInvoice($merchant_id, $this->user_id, $currency, $einvoice_types, $profiles);
                $hasErrors = $validator->fetchErrors();
            }

            if ($hasErrors['invoice_number'] == false) {
                if (in_array($_POST['invoice_number'], $this->invoice_numbers)) {
                    $hasErrors['invoice_number'] = array('Invoice number', 'Invoice number already exists in excel sheet');
                }
            }
            if ($_POST['invoice_number'] != '' && substr($_POST['invoice_number'], 0, 16) != 'System generated') {
                $this->invoice_numbers[] = $_POST['invoice_number'];
            }

            if ($hasErrors == false) {
            } else {
                return $hasErrors;
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E246]Error while sending payment request Error:' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function send($bulk_id, $type = 1)
    {
        try {
            $this->hasRole(2, 4);
            $merchant_id = $this->session->get('merchant_id');
            $bulk_id = $this->encrypt->decode($bulk_id);
            $this->model->updateBulkUploadStatus($bulk_id, 4);
            if ($type == 2) {
                $this->session->set("successMessage", ' Customer saving request is initiated.');
                header('Location: /merchant/customer/bulkupload');
            } elseif ($type == 3) {
                $this->session->set("successMessage", ' Vendor saving request is initiated.');
                header('Location: /merchant/vendor/bulkupload/vendor');
            } elseif ($type == 4) {
                $this->session->set("successMessage", ' Transfer saving request is initiated.');
                header('Location: /merchant/vendor/bulkupload/transfer');
            } elseif ($type == 6) {
                $this->session->set("successMessage", ' Franchise saving request is initiated.');
                header('Location: /merchant/franchise/bulkupload');
            } elseif ($type == 8) {
                $this->session->set("successMessage", ' Beneficiary saving request is initiated.');
                header('Location: /merchant/vendor/bulkupload/beneficiary');
            } elseif ($type == 9) {
                $this->session->set("successMessage", ' Payout sending request is initiated.');
                header('Location: /merchant/vendor/bulkupload/payout');
            } elseif ($type == 10) {
                $this->session->set("successMessage", ' Expense saving is initiated.');
                header('Location: /merchant/expense/bulkupload');
            } elseif ($type == 'transaction') {
                $this->session->set("successMessage", ' Transaction saving request is initiated.');
                header('Location: /merchant/transaction/bulkupload');
            } else {
                $this->session->set("successMessage", ' Invoice sending request is initiated.');
                header('Location: /merchant/bulkupload/viewlist');
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E268]Error while bulkupload send Error: for merchant [' . $merchant_id . '] and bulk id [' . $bulk_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function deleteinvoice($link)
    {
        $id = $this->encrypt->decode($link);
        $detail = $this->common->getSingleValue('staging_payment_request', 'payment_request_id', $id);
        if ($detail['merchant_id'] == $this->merchant_id) {
            $this->common->genericupdate('staging_payment_request', 'is_active', 0, 'payment_request_id', $id, $this->user_id);
            $link = $this->encrypt->encode($detail['bulk_id']);
            $this->session->set("successMessage", ' Invoice deleted successfully');
            header('Location: /merchant/bulkupload/bulkinvoice/' . $link);
        } else {
        }
    }
    public function reupload($bulk_id)
    {
        try {
            $this->validatePackage();
            $this->hasRole(2, 4);
            $merchant_id = $this->session->get('merchant_id');
            if ($bulk_id != '') {
                $this->smarty->assign("bulk_id", $bulk_id);
                $this->view->header_file = ['bulkupload'];
                $this->view->render('header/app');
                $this->smarty->display(VIEW . 'merchant/bulkupload/reupload.tpl');
                $this->view->render('footer/bulkupload');
            } else {
                header('Location: /merchant/bulkupload/newupload');
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E269]Error while re-upload Error: for merchant [' . $merchant_id . '] and bulk id [' . $bulk_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function upload()
    {
        try {
            $merchant_id = $this->session->get('merchant_id');
            if (isset($_FILES["fileupload"])) {
                require_once CONTROLLER . 'merchant/Uploadvalidator.php';
                $validator = new Uploadvalidator($this->model);
                $validator->validateExcelUpload();
                $hasErrors = $validator->fetchErrors();
                if ($hasErrors == false) {
                    if (isset($_POST['bulk_id'])) {
                        $merchant_id = $this->session->get('merchant_id');
                        $bulk_id = $this->encrypt->decode($_POST['bulk_id']);
                        $detail = $this->model->getBulkuploaddetail($merchant_id, $bulk_id);
                        $this->model->updateBulkUploadStatus($bulk_id, 7);
                        $folder = ($detail['status'] == 2 || $detail['status'] == 3) ? 'staging' : 'error';
                        $this->model->moveExcelFile($merchant_id, $folder, $detail['system_filename']);
                    }
                    $this->bulk_upload($_FILES["fileupload"]);
                } else {
                    $this->smarty->assign("hasErrors", $hasErrors);
                    $this->newupload();
                }
            } else {
                header('Location: /merchant/bulkupload/newupload');
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E270]Error while bulk upload submit Error: for merchant [' . $this->merchant_id . '] ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function bulk_upload($inputFile, $bulk_upload_id = NULL)
    {
        try {

            if ($bulk_upload_id == NULL) {
                $File = $inputFile['tmp_name'];
            } else {
                $File = $inputFile;
            }
            $merchant_id = $this->session->get('merchant_id');
            $inputFileType = PHPExcel_IOFactory::identify($File);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($File);
            $subject = $objPHPExcel->getProperties()->getSubject();
            $link = $this->encrypt->decode($subject);
            $template_id = substr($link, 0, 10);
            if (strlen($link) > 30) {
                if (strlen($link) == 33) {
                    $update_date = substr($link, 10, -4);
                    $type = substr($link, -4, 1);
                    $sheet_type = substr($link, -3, 1);
                    $version = 'v2'; //for country column in excel sheet
                } else {
                    $update_date = substr($link, 10, -2);
                    $type = substr($link, -2, 1);
                    $sheet_type = substr($link, -1);
                    $version = 2;
                }
            } else {
                $update_date = substr($link, 10, -1);
                $type = substr($link, -1);
                $sheet_type = 1;
                $version = 1;
            }

            $worksheet = $objPHPExcel->getSheet(0);
            //$worksheetTitle = $worksheet->getTitle();
            $errors = array();
            $is_upload = TRUE;
            $templateinfo = array();

            require_once(MODEL . 'merchant/InvoiceModel.php');

            if ($template_id != '') {
                $merchant_details = $this->common->getSingleValue('merchant', 'merchant_id', $this->merchant_id);
                $info = $this->common->getSingleValue('invoice_template', 'template_id', $template_id, 1);
                $billing_profile = $this->common->getListValue('merchant_billing_profile', 'merchant_id', $this->merchant_id, 1);

                if (empty($info)) {
                    $errors[0][0] = 'Template does not exist.';
                    $errors[0][1] = 'Download again excel template from template list and re-upload with valid data.';
                    $is_upload = FALSE;
                }
                if ($info['user_id'] != $merchant_details['user_id']) {
                    $errors[0][0] = 'Wrong excel sheet';
                    $errors[0][1] = 'Download again excel template from template list and re-upload with valid data.';
                    $is_upload = FALSE;
                } else {
                    $info['template_id'] = $link;
                    $template_type = $info['template_type'];
                    require_once CONTROLLER . 'InvoiceWrapper.php';
                    $invoice = new InvoiceWrapper($this->common);
                    $templateinfo = $invoice->getTemplateBreakup($template_id);
                    $total_rows = $this->model->gettotalrows($this->merchant_id);
                    $result = $this->common->getSingleValue('merchant_setting', 'merchant_id', $this->merchant_id);
                    $upload_limit = $result['invoice_bulk_upload_limit'];
                }
            }
            $info['version'] = $version;

            if ($update_date != $info['last_update_date']) {
                $errors[0][0] = 'Template already updated';
                $errors[0][1] = 'Download again excel template from template list and re-upload with consumer data.';
                $is_upload = FALSE;
            }

            if (empty($templateinfo['header'])) {
                $errors[0][0] = 'Invalid template';
                $errors[0][1] = 'Download excel from template list and re-upload with consumer data.';
                $is_upload = FALSE;
            }

            $getcolumnvalue = array();
            $start_int = 0;
            if ($sheet_type == 2) {
                require_once CONTROLLER . 'merchant/Customer.php';
                $customer = new Customer();
                $rownumber = ($template_type == 'travel') ? 3 : 2;
                $array = $customer->bulk_upload(null,  NULL, $rownumber, $worksheet, $this->merchant_id, $version);
                $errors = $array['errors'];
                $start_int = $array['last_col'];
            }

            if (empty($errors)) {
                require_once CONTROLLER . 'merchant/BulkuploadWrapper.php';
                $bulkWrapper = new BulkuploadWrapper($this->common);
                $_POSTarray = $bulkWrapper->getInvoiceExcelRows($worksheet, $templateinfo, $info, $template_id, $type, $start_int);
            } else {
                $is_upload = FALSE;
            }

            $rows_count = count($_POSTarray);
            if ($rows_count + $total_rows > $upload_limit) {
                $is_upload = FALSE;
                $errors[0][0] = 'Bulk upload limit reached';
                if ($this->session->get('merchant_plan') == 2) {
                    $errors[0][1] = 'You are currently on the Free plan which allows only ' . $upload_limit . ' rows to be uploaded. <a href="/billing-software-pricing">Upgrade your plan</a>';
                } else {
                    $errors[0][1] = 'Maximum ' . $upload_limit . ' Rows can be uploaded in one day.';
                }
            }
            $errorrow = 2;
            try {
                if (empty($_POSTarray) && empty($errors)) {
                    $is_upload = FALSE;
                    $errors[0][0] = 'Empty excel';
                    $errors[0][1] = 'Uploaded excel does not contain any invoices.';
                }
                foreach ($billing_profile as $row) {
                    $profiles[] = $row['profile_name'];
                }
                if (empty($errors)) {
                    foreach ($_POSTarray as $_POST) {
                        $result = $this->validateuploadinvoice($templateinfo, $type, $profiles);

                        if (!empty($result)) {
                            $result['row'] = $errorrow;
                            $errors[] = $result;
                        } else {
                        }
                        $errorrow++;
                    }
                }
            } catch (Exception $e) {
                Sentry\captureException($e);
            }
            if (empty($errors) && $bulk_upload_id == NULL) {
                $this->model->uploadExcel($inputFile, $this->session->get('merchant_id'), FALSE, $rows_count);
                $this->session->set('successMessage', '  Excel sheet uploaded.');
                header('Location: /merchant/bulkupload/viewlist');
            } else {

                if ($is_upload == TRUE && $bulk_upload_id == NULL) {
                    $bulk_id = $this->model->uploadExcel($inputFile, $this->session->get('merchant_id'), TRUE, 0);
                    $this->common->genericupdate('bulk_upload', 'error_json', json_encode($errors), 'bulk_upload_id', $bulk_id, $this->user_id);
                    $bulk_id = $this->encrypt->encode($bulk_id);
                }

                if ($bulk_upload_id != NULL) {
                    $this->smarty->assign("bulk_id", $bulk_upload_id);
                    $this->smarty->assign("errors", $errors);
                    $this->view->render('header/guest');
                    $this->smarty->display(VIEW . 'merchant/bulkupload/allerrors.tpl');
                    $this->view->render('footer/nonfooter');
                } else {

                    $this->smarty->assign("haserrors", $errors);
                    if ($bulk_id != '') {
                        $this->reupload($bulk_id);
                    } else {
                        $this->newupload();
                    }
                }
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E271]Error while uploading excel Error: for merchant [' . $merchant_id . '] and template id [' . $template_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function error($bulk_upload_id)
    {
        try {
            $bulk_id = $this->encrypt->decode($bulk_upload_id);
            $merchant_id = $this->session->get('merchant_id');
            $detail = $this->model->getBulkuploaddetail($merchant_id, $bulk_id);
            if ($detail['error_json'] != '') {
                $errors = json_decode($detail['error_json'], true);
                $this->smarty->assign("bulk_id", $bulk_upload_id);
                $this->smarty->assign("errors", $errors);
                $this->view->render('header/guest');
                $this->smarty->display(VIEW . 'merchant/bulkupload/allerrors.tpl');
                $this->view->render('footer/nonfooter');
            } else {
                if ($detail['status'] == 1) {
                    $folder = 'error';
                } elseif ($detail['status'] == 6) {
                    $folder = 'deleted';
                } else {
                    $folder = 'staging';
                }
                if (!empty($detail)) {
                    $file = 'uploads/Excel/' . $merchant_id . '/' . $folder . '/' . $detail['system_filename'];
                }
                $this->bulk_upload($file, $bulk_upload_id);
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E272]Error while bulk upload error Error:for merchant [' . $merchant_id . '] and  bulk id [' . $bulk_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }
}
