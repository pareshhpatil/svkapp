<?php

/**
 * Aprove controller customer changes approval or reject
 */
class Approve extends Controller {

    function __construct() {
        parent::__construct();
        $this->validateSession('merchant');
        $this->view->js = array('customer');
    }

    public function pending() {
        try {
            $this->hasRole(2, 16);
            $merchant_id = $this->session->get('merchant_id');
            $last_date = $this->getLast_date();
            $current_date = date('d M Y');

            if (isset($_POST['from_date'])) {
                $from_date = $_POST['from_date'];
                $to_date = $_POST['to_date'];
            } else {
                $from_date = $last_date;
                $to_date = $current_date;
            }
            $this->smarty->assign("from_date", $from_date);
            $this->smarty->assign("to_date", $to_date);
            $fromdate = new DateTime($from_date);
            $todate = new DateTime($to_date);

            $list = $this->model->getApproval($fromdate->format('Y-m-d'), $todate->format('Y-m-d'), $merchant_id, 'pending');
            foreach ($list as $request) {

                $request['changed_value'] = str_replace(array("\r\n", "\r", "\n", "\\r", "\\n", "\\r\\n"), ' ', $request['changed_value']);
                $request['changed_value'] = str_replace(array("\r\n", "\r", "\n", "\\r", "\\n", "\\r\\n"), ' ', $request['changed_value']);
                $request['current_value'] = str_replace(array("\r\n", "\r", "\n", "\\r", "\\n", "\\r\\n"), ' ', $request['current_value']);
                $request['current_value'] = str_replace(array("\r\n", "\r", "\n", "\\r", "\\n", "\\r\\n"), ' ', $request['current_value']);
                $requestlist[$request['change_id']][] = $request;
            }

            //Breadcumbs array start
            $breadcumbs_array = array(
                array('title' => 'Contacts','url' => ''),
                array('title'=> 'Customer', 'url'=>''),
                array('title' => 'Approve customer updates ','url' => '')
            );
            $this->smarty->assign("links", $breadcumbs_array);
            //Breadcumbs array end

            $this->view->title = 'Approve customer updates';
            $this->view->selectedMenu = array(2, 15, 73);
            $this->smarty->assign("requestlist", $requestlist);
            $this->view->datatablejs = 'table-accordian';
            $this->smarty->assign("json_req", json_encode($requestlist));
            $this->smarty->assign("title", 'Approve customer updates');
            $this->view->header_file = ['list'];
        $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/approve/pending.tpl');
            $this->view->render('footer/list');
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E1031]Error while pending approval list initiate Error: for merchant [' . $merchant_id . '] ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function approved() {
        try {
            $this->hasRole(2, 16);
            $merchant_id = $this->session->get('merchant_id');
            $last_date = $this->getLast_date();
            $current_date = date('d M Y');

            if (isset($_POST['from_date'])) {
                $from_date = $_POST['from_date'];
                $to_date = $_POST['to_date'];
            } else {
                $from_date = $last_date;
                $to_date = $current_date;
            }
            $this->smarty->assign("from_date", $from_date);
            $this->smarty->assign("to_date", $to_date);
            $fromdate = new DateTime($from_date);
            $todate = new DateTime($to_date);

            $requestlist = $this->model->getApproval($fromdate->format('Y-m-d'), $todate->format('Y-m-d'), $merchant_id, 'approved');
            $this->smarty->assign("requestlist", $requestlist);
            $this->view->title = 'Approved Approval';
            $this->view->selectedMenu = 'approved_approve';
            $this->smarty->assign("title", 'Approved Approval');
            $this->view->header_file = ['list'];
        $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/approve/approved.tpl');
            $this->view->render('footer/list');
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E1032]Error while pending approval list initiate Error: for merchant [' . $merchant_id . '] ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function approve_individual($detail_id) {
        try {
            $merchant_id = $this->session->get('merchant_id');
            $detail = $this->model->getDetails($detail_id, $merchant_id);
            if (empty($detail)) {
                SwipezLogger::warn(__CLASS__, '[E1033]Found empty changes for merchant [' . $merchant_id . '] change id [' . $detail_id . '] ');
            } else {
                $this->model->update_change_status($detail['change_detail_id'], 1, $this->user_id);
                switch ($detail['column_type']) {
                    case 1:
                        $this->model->update_customer_data('first_name', $detail['customer_id'], $detail['changed_value'], $this->user_id);
                        break;
                    case 2:
                        $this->model->update_customer_data('last_name', $detail['customer_id'], $detail['changed_value'], $this->user_id);
                        break;
                    case 3:
                        $this->model->update_customer_data('email', $detail['customer_id'], $detail['changed_value'], $this->user_id);
                        $this->model->updateUserid_customer($detail['changed_value'], $detail['customer_id']);
                        break;
                    case 4:
                        $this->model->update_customer_data('mobile', $detail['customer_id'], $detail['changed_value'], $this->user_id);
                        break;
                    case 5:
                        $this->model->update_customer_data('address', $detail['customer_id'], $detail['changed_value'], $this->user_id);
                        break;
                    case 6:
                        $this->model->update_customer_data('city', $detail['customer_id'], $detail['changed_value'], $this->user_id);
                        break;
                    case 7:
                        $this->model->update_customer_data('state', $detail['customer_id'], $detail['changed_value'], $this->user_id);
                        break;
                    case 8:
                        $this->model->update_customer_data('zipcode', $detail['customer_id'], $detail['changed_value'], $this->user_id);
                        break;
                }
            }
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E1034]Error while approve all changes initiate Error: for merchant [' . $merchant_id . '] detail id [' . $detail_id . '] ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function reject_individual($detail_id) {
        try {
            $merchant_id = $this->session->get('merchant_id');
            $detail = $this->model->getDetails($detail_id, $merchant_id);
            if (empty($detail)) {
                SwipezLogger::warn(__CLASS__, '[E1035]Found empty changes for merchant [' . $merchant_id . '] detail id [' . $detail_id . '] ');
            } else {
                $this->model->update_change_status($detail['change_detail_id'], 2, $this->user_id);
                if ($detail['status'] == 0) {
                    
                } else {
                    /* switch ($detail['column_type']) {
                      case 1:
                      $this->model->update_customer_data('first_name', $detail['customer_id'], $detail['current_value'], $this->user_id);
                      break;
                      case 2:
                      $this->model->update_customer_data('last_name', $detail['customer_id'], $detail['current_value'], $this->user_id);
                      break;
                      case 3:
                      $this->model->update_customer_comm_detail($detail['column_value_id'], $detail['current_value'], $this->user_id);
                      break;
                      case 4:
                      $this->model->update_customer_comm_detail($detail['column_value_id'], $detail['current_value'], $this->user_id);
                      break;
                      case 5:
                      $this->model->update_customer_data('address', $detail['customer_id'], $detail['current_value'], $this->user_id);
                      break;
                      case 6:
                      $this->model->update_customer_data('city', $detail['customer_id'], $detail['current_value'], $this->user_id);
                      break;
                      case 7:
                      $this->model->update_customer_data('state', $detail['customer_id'], $detail['current_value'], $this->user_id);
                      break;
                      case 8:
                      $this->model->update_customer_data('zipcode', $detail['customer_id'], $detail['current_value'], $this->user_id);
                      break;
                      } */
                }
            }
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E1036]Error while approve all changes initiate Error: for merchant [' . $merchant_id . '] detail id [' . $detail_id . '] ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function undo_individual($detail_id) {
        try {
            $merchant_id = $this->session->get('merchant_id');
            $detail = $this->model->getDetails($detail_id, $merchant_id);
            if (empty($detail)) {
                SwipezLogger::warn(__CLASS__, '[E1037]Found empty changes for merchant [' . $merchant_id . '] detail id [' . $detail_id . '] ');
            } else {
                $this->model->update_change_status($detail['change_detail_id'], 0, $this->user_id);
                if ($detail['status'] == 0) {
                    
                } else {
                    switch ($detail['column_type']) {
                        case 1:
                            $this->model->update_customer_data('first_name', $detail['customer_id'], $detail['current_value'], $this->user_id);
                            break;
                        case 2:
                            $this->model->update_customer_data('last_name', $detail['customer_id'], $detail['current_value'], $this->user_id);
                            break;
                        case 3:
                            $this->model->update_customer_data('email', $detail['customer_id'], $detail['current_value'], $this->user_id);
                            break;
                        case 4:
                            $this->model->update_customer_data('mobile', $detail['customer_id'], $detail['current_value'], $this->user_id);
                            break;
                        case 5:
                            $this->model->update_customer_data('address', $detail['customer_id'], $detail['current_value'], $this->user_id);
                            break;
                        case 6:
                            $this->model->update_customer_data('city', $detail['customer_id'], $detail['current_value'], $this->user_id);
                            break;
                        case 7:
                            $this->model->update_customer_data('state', $detail['customer_id'], $detail['current_value'], $this->user_id);
                            break;
                        case 8:
                            $this->model->update_customer_data('zipcode', $detail['customer_id'], $detail['current_value'], $this->user_id);
                            break;
                    }
                }
            }
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E1038]Error while approve all changes initiate Error: for merchant [' . $merchant_id . '] detail id [' . $detail_id . '] ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function approve_all($change_id) {
        try {
            $merchant_id = $this->session->get('merchant_id');
            $get_details = $this->model->getChangeDetails($change_id, $merchant_id);
            if (empty($get_details)) {
                SwipezLogger::warn(__CLASS__, '[E1039]Found empty changes for merchant [' . $merchant_id . '] change id [' . $change_id . '] ');
            } else {
                foreach ($get_details as $detail) {
                    $this->model->update_change_status($detail['change_detail_id'], 1, $this->user_id);

                    switch ($detail['column_type']) {
                        case 1:
                            $this->model->update_customer_data('first_name', $detail['customer_id'], $detail['changed_value'], $this->user_id);
                            break;
                        case 2:
                            $this->model->update_customer_data('last_name', $detail['customer_id'], $detail['changed_value'], $this->user_id);
                            break;
                        case 3:
                            $this->model->update_customer_data('email', $detail['customer_id'], $detail['changed_value'], $this->user_id);
                            $this->model->updateUserid_customer($detail['changed_value'], $detail['customer_id']);
                            break;
                        case 4:
                            $this->model->update_customer_data('mobile', $detail['customer_id'], $detail['changed_value'], $this->user_id);
                            break;
                        case 5:
                            $this->model->update_customer_data('address', $detail['customer_id'], $detail['changed_value'], $this->user_id);
                            break;
                        case 6:
                            $this->model->update_customer_data('city', $detail['customer_id'], $detail['changed_value'], $this->user_id);
                            break;
                        case 7:
                            $this->model->update_customer_data('state', $detail['customer_id'], $detail['changed_value'], $this->user_id);
                            break;
                        case 8:
                            $this->model->update_customer_data('zipcode', $detail['customer_id'], $detail['changed_value'], $this->user_id);
                            break;
                    }

                    $ids['ids'][] = $detail['change_detail_id'];
                }


                echo json_encode($ids);
            }
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E1040]Error while approve all changes initiate Error: for merchant [' . $merchant_id . '] Change id [' . $change_id . '] ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function reject_all($change_id) {
        try {
            $merchant_id = $this->session->get('merchant_id');
            $get_details = $this->model->getChangeDetails($change_id, $merchant_id);
            if (empty($get_details)) {
                SwipezLogger::warn(__CLASS__, '[E1041]Found empty changes for merchant [' . $merchant_id . '] change id [' . $change_id . '] ');
            } else {
                foreach ($get_details as $detail) {
                    $this->model->update_change_status($detail['change_detail_id'], 2, $this->user_id);
                    if ($detail['status'] == 0) {
                        
                    } else {
                        /* switch ($detail['column_type']) {
                          case 1:
                          $this->model->update_customer_data('first_name', $detail['customer_id'], $detail['current_value'], $this->user_id);
                          break;
                          case 2:
                          $this->model->update_customer_data('last_name', $detail['customer_id'], $detail['current_value'], $this->user_id);
                          break;
                          case 3:
                          $this->model->update_customer_comm_detail($detail['column_value_id'], $detail['current_value'], $this->user_id);
                          break;
                          case 4:
                          $this->model->update_customer_comm_detail($detail['column_value_id'], $detail['current_value'], $this->user_id);
                          break;
                          case 5:
                          $this->model->update_customer_data('address', $detail['customer_id'], $detail['current_value'], $this->user_id);
                          break;
                          case 6:
                          $this->model->update_customer_data('city', $detail['customer_id'], $detail['current_value'], $this->user_id);
                          break;
                          case 7:
                          $this->model->update_customer_data('state', $detail['customer_id'], $detail['current_value'], $this->user_id);
                          break;
                          case 8:
                          $this->model->update_customer_data('zipcode', $detail['customer_id'], $detail['current_value'], $this->user_id);
                          break;
                          } */
                    }

                    $ids['ids'][] = $detail['change_detail_id'];
                }
                echo json_encode($ids);
            }
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E1042]Error while approve all changes initiate Error: for merchant [' . $merchant_id . '] Change id [' . $change_id . '] ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function undo_all($change_id) {
        try {
            $merchant_id = $this->session->get('merchant_id');
            $get_details = $this->model->getChangeDetails($change_id, $merchant_id);
            if (empty($get_details)) {
                SwipezLogger::warn(__CLASS__, '[E1032]Found empty changes for merchant [' . $merchant_id . '] change id [' . $change_id . '] ');
            } else {
                foreach ($get_details as $detail) {
                    $this->model->update_change_status($detail['change_detail_id'], 0, $this->user_id);
                    if ($detail['status'] == 0) {
                        
                    } else {
                        switch ($detail['column_type']) {
                            case 1:
                                $this->model->update_customer_data('first_name', $detail['customer_id'], $detail['current_value'], $this->user_id);
                                break;
                            case 2:
                                $this->model->update_customer_data('last_name', $detail['customer_id'], $detail['current_value'], $this->user_id);
                                break;
                            case 3:
                                $this->model->update_customer_data('email', $detail['customer_id'], $detail['current_value'], $this->user_id);
                                break;
                            case 4:
                                $this->model->update_customer_data('mobile', $detail['customer_id'], $detail['current_value'], $this->user_id);
                                break;
                            case 5:
                                $this->model->update_customer_data('address', $detail['customer_id'], $detail['current_value'], $this->user_id);
                                break;
                            case 6:
                                $this->model->update_customer_data('city', $detail['customer_id'], $detail['current_value'], $this->user_id);
                                break;
                            case 7:
                                $this->model->update_customer_data('state', $detail['customer_id'], $detail['current_value'], $this->user_id);
                                break;
                            case 8:
                                $this->model->update_customer_data('zipcode', $detail['customer_id'], $detail['current_value'], $this->user_id);
                                break;
                        }
                    }

                    $ids['ids'][] = $detail['change_detail_id'];
                }
                echo json_encode($ids);
            }
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E1043]Error while approve all changes initiate Error: for merchant [' . $merchant_id . '] Change id [' . $change_id . '] ' . $e->getMessage());
            $this->setGenericError();
        }
    }

}
