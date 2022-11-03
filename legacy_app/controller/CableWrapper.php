<?php

/**
 * Payment request functionality for viewing payment requests, confirming and invoking payment gateway
 * 
 */
class CableWrapper {

    public function setSelectpackage($settopbox_id, $common_model, $model) {
        try {
            $sev_det = $common_model->getSingleValue('customer_service', 'id', $settopbox_id);
            $merchant_id = $sev_det['merchant_id'];
            $total_cost = $sev_det['cost'];


            $cable_setting = $common_model->getSingleValue('cable_setting', 'merchant_id', $merchant_id);
            $customer_packages = $model->getCustomerPackage($settopbox_id);
            $exist_package = array();
            $exist_channel = array();
            $checked_channel = array();
            $chlist = array();
            foreach ($customer_packages as $p) {
                if ($p['sub_package_id'] > 0) {
                    $exist_package[] = $p['sub_package_id'];
                }
                if ($p['package_id'] > 0) {
                    $exist_package[] = $p['package_id'];
                    $checked_package[] = $p['package_id'];
                }
                if ($p['channel_id'] > 0) {
                    $exist_channel[] = $p['channel_id'];
                    $checked_channel[] = $p['channel_id'];
                }
            }
            if (!empty($exist_package)) {
                $chlist = $model->getPackageChannelsID($exist_package);
                foreach ($chlist as $ch) {
                    $exist_channel[] = $ch['channel_id'];
                }
            }

            $cable_group = $model->getGroupPackage($merchant_id);
            $channels = array();
            $packages = array();
            $pkg_array = array();
            $pkg_selected = '';
            foreach ($cable_group as $key => $det) {
                $pkgchannels = $model->getPackageChannels($det['package_id']);
                $packages[$det['package_group']]['sub_package_id'] = $det['sub_package_id'];
                $packages[$det['package_group']]['name'] = $det['group_name'];
                $packages[$det['package_group']]['min'] = $det['min_package_selection'];
                $packages[$det['package_group']]['max'] = $det['max_package_selection'];
                $packages[$det['package_group']]['group_type'] = $det['group_type'];
                $pkg_array[$det['package_id']]['total_cost'] = $det['total_cost'];
                $pkg_array[$det['package_id']]['group_type'] = $det['group_type'];
                $pkg_array[$det['package_id']]['channel_count'] = count($pkgchannels);
                if (in_array($det['package_id'], $checked_package)) {
                    $det['exist'] = 1;
                    $pkg_array[$det['package_id']]['checked'] = 1;
                    $pkg_selected = $pkg_selected . $det['package_id'] . ',';
                } else {
                    $det['exist'] = 0;
                    $pkg_array[$det['package_id']]['checked'] = 0;
                }
                $packages[$det['package_group']]['packages'][$det['package_id']] = $det;
                if ($det['sub_package_id'] > 0) {
                    $packages[$det['package_group']]['packages'][$det['package_id']]['sub_package_name'] = $common_model->getRowValue('package_name', 'cable_package', 'package_id', $det['sub_package_id'], 1);
                } else {
                    $packages[$det['package_group']]['packages'][$det['package_id']]['sub_package_name'] = '';
                }
                $packages[$det['package_group']]['packages'][$det['package_id']]['channels'] = $pkgchannels;
            }



            $channels = $common_model->getListValue('cable_channel', 'merchant_id', $merchant_id, 1);
            $ch_array = array();
            $ch_selected = '';
            foreach ($channels as $key => $pkg) {
                $ch_array[$pkg['channel_id']]['total_cost'] = $pkg['total_cost'];
                $ch_array[$pkg['channel_id']]['checked'] = 0;
                if (in_array($pkg['channel_id'], $exist_channel)) {
                    if (in_array($pkg['channel_id'], $checked_channel)) {
                        $pkg['checked'] = 1;
                        $ch_selected = $ch_selected . $pkg['channel_id'] . ',';
                        $ch_array[$pkg['channel_id']]['checked'] = 1;
                        $channels[$key]['checked'] = 1;
                        $pkg['exist'] = 0;
                        $channels[$key]['exist'] = 0;
                    } else {
                        $pkg['exist'] = 1;
                        $channels[$key]['exist'] = 1;
                    }
                } else {
                    $pkg['exist'] = 0;
                    $channels[$key]['exist'] = 0;
                }
                $genre_pkg[$pkg['genre']][] = $pkg;
                $genre[$pkg['genre']] = $pkg['genre'];
            }

            $smarty["checked_package"] = $checked_package;
            $smarty["checked_channel"] = $checked_channel;
            $smarty["total_cost"] = $total_cost;
            $smarty["exist_package"] = $exist_package;
            $smarty["exist_channel"] = $exist_channel;
            $smarty["settopbox_id"] = $settopbox_id;
            $smarty["packages"] = $packages;
            $smarty["genre_pkg"] = $genre_pkg;
            $smarty["genre"] = $genre;
            $smarty["ch_selected"] = $ch_selected;
            $smarty["ch_array"] = json_encode($ch_array);
            $smarty["pkg_selected"] = $pkg_selected;
            $smarty["pkg_array"] = json_encode($pkg_array);
            $smarty["channels"] = $channels;
            $smarty["merchant_id"] = $merchant_id;
            $smarty["settopbox_name"] = $sev_det['name'];
            if (!empty($cable_setting)) {
                $smarty["cable_setting"] = json_encode($cable_setting);
            }
            return $smarty;
        } catch (Exception $e) {
Sentry\captureException($e);
            SwipezLogger ::error(__CLASS__, '[E1011]Error while payment request payment initiate Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function savePackage($common_model, $model, $user_id) {
        try {
            $service_id = $_POST['settopbox_id'];
            $model->deletePackage($service_id);
            $det = $common_model->getSingleValue('customer_service', 'id', $service_id);
            $cable_setting = $common_model->getSingleValue('cable_setting', 'merchant_id', $det['merchant_id']);
            if (empty($det)) {
                SwipezLogger ::error(__CLASS__, '[E1011]empty detail for : ' . $service_id);
            }
            $_POST['package_id'] = explode(',', $_POST['package_selected']);
            $packages = $_POST['package_id'];
            $ncf_channels = 0;
            foreach ($packages as $pkg) {
                if ($pkg > 0) {
                    $pkgdet = $common_model->getSingleValue('cable_package', 'package_id', $pkg);
                    $group = $common_model->getRowValue('group_type', 'cable_group', 'group_id', $pkgdet['package_group']);
                    $cost = $pkgdet['total_cost'];

                    if ($pkgdet['sub_package_id'] > 0) {
                        $common_model->genericupdate('customer_service_package', 'is_active', 0, 'service_id', $service_id, null, " and package_id=" . $pkgdet['sub_package_id']);
                    }
                    $model->saveCustomerService($service_id, $det['customer_id'], $det['merchant_id'], 1, $pkg, 0, $cost, $user_id);
                    if ($group == 1 && $cable_setting['ncf_base_package'] == 1) {
                        $package_channel = $common_model->getRowValue('count(id)', 'cable_package_channel', 'package_id', $pkg, 1);
                        $ncf_channels = $ncf_channels + $package_channel;
                    }

                    if ($group == 2 && $cable_setting['ncf_addon_package'] == 1) {
                        $package_channel = $common_model->getRowValue('count(id)', 'cable_package_channel', 'package_id', $pkg, 1);
                        $ncf_channels = $ncf_channels + $package_channel;
                    }
                }
            }
            $_POST['channel_id'] = explode(',', $_POST['channel_selected']);
            $channels = array_unique($_POST['channel_id']);
            foreach ($channels as $ch) {
                if ($ch > 0) {
                    $cost = $common_model->getRowValue('total_cost', 'cable_channel', 'channel_id', $ch);
                    $model->saveCustomerService($service_id, $det['customer_id'], $det['merchant_id'], 2, 0, $ch, $cost, $user_id);
                    if ($cable_setting['ncf_alacarte_package'] == 1) {
                        $ncf_channels = $ncf_channels + 1;
                    }
                }
            }

            $group_dett = $common_model->getSingleValue('cable_group', 'merchant_id', $det['merchant_id'], 1, " and min_package_selection=1");
            if (!empty($group_dett)) {
                $group_id = $group_dett['group_id'];
                $count = $model->getMinPackage($group_id, $service_id);
                if ($count == 0) {
                    $response['status'] = 0;
                    $response['error'] = 'Select Minimum 1 package from ' . $group_dett['group_name'];
                    return $response;
                }
            }
            $total_cost = $common_model->getRowValue('sum(cost)', 'customer_service_package', 'service_id', $service_id, 1);
            if ($ncf_channels > 0) {
                $nfc_amt = $cable_setting['ncf_fee'] + ($cable_setting['ncf_fee'] * $cable_setting['ncf_tax'] / 100);
                while ($ncf_channels > 0) {
                    $total_cost = $total_cost + $nfc_amt;
                    $ncf_channels = $ncf_channels - $cable_setting['ncf_qty'];
                }
            }
            $common_model->genericupdate('customer_service', 'cost', $total_cost, 'id', $service_id, null);
            $common_model->genericupdate('customer_service', 'status', 0, 'id', $service_id, null);
            $response['status'] = 1;
            return $response;
        } catch (Exception $e) {
Sentry\captureException($e);
            SwipezLogger ::error(__CLASS__, '[E1011]Error while save cable Package Error: ' . $e->getMessage());
        }
    }

}
