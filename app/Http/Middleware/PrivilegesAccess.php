<?php

namespace App\Http\Middleware;

use App\Libraries\Encrypt;
use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Session;

class PrivilegesAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $path = $request->path();
        //$result = Str::startsWith($path, 'merchant/contract/update');
        $userID = Encrypt::decode(Session::get('userid'));

        $pathArray = explode("/", $path);

        $modelID = Encrypt::decode(Arr::last($pathArray));
        $userRole = Session::get('user_role');

        if($userRole == 'Admin') {
            return $next($request);
        }

        $result = false;
        $projectPrivilegesAccessIDs = json_decode(Redis::get('project_privileges_' . $userID), true);
        $customerPrivilegesAccessIDs = json_decode(Redis::get('customer_privileges_' . $userID), true);
        $contractPrivilegesAccessIDs = json_decode(Redis::get('contract_privileges_' . $userID), true);

        $projectPrivilegesAccessIDs = json_decode(Redis::get('project_privileges_' . $userID), true);
        $customerPrivilegesAccessIDs = json_decode(Redis::get('customer_privileges_' . $userID), true);
        $contractPrivilegesAccessIDs = json_decode(Redis::get('contract_privileges_' . $userID), true);
        switch ($pathArray[1]) {
            case 'contract':
                if (!empty($contractPrivilegesAccessIDs)) {
                    if(in_array('all', array_keys($contractPrivilegesAccessIDs)) && !in_array($modelID, array_keys($contractPrivilegesAccessIDs))) {
                        if ($contractPrivilegesAccessIDs['all'] == 'full' || $contractPrivilegesAccessIDs['all'] == 'edit' || $contractPrivilegesAccessIDs['all'] == 'approve') {
                            $result = true;
                        }
                    } else {
                        if(($pathArray[2] == 'create' || $pathArray[2] == 'store') && (in_array('full', array_values($contractPrivilegesAccessIDs)) || in_array('edit', array_values($contractPrivilegesAccessIDs)) || in_array('approve', array_values($contractPrivilegesAccessIDs)))) {
                            $result = true;
                        } else {
                            if(!empty($modelID) && ($contractPrivilegesAccessIDs[$modelID] == 'full' || $contractPrivilegesAccessIDs[$modelID] == 'edit' || $contractPrivilegesAccessIDs[$modelID] == 'approve')) {
                                $result = true;
                            }
                        }
                    }
                } elseif (!empty($projectPrivilegesAccessIDs)) {
                    if(($pathArray[2] == 'create' || $pathArray[2] == 'store') && (in_array('full', array_values($projectPrivilegesAccessIDs)) || in_array('edit', array_values($projectPrivilegesAccessIDs)) || in_array('approve', array_values($projectPrivilegesAccessIDs)))) {
                        $result = true;
                    }
                } elseif (!empty($customerPrivilegesAccessIDs)) {
                    if(($pathArray[2] == 'create' || $pathArray[2] == 'store') && (in_array('full', array_values($customerPrivilegesAccessIDs)) || in_array('edit', array_values($customerPrivilegesAccessIDs)) || in_array('approve', array_values($customerPrivilegesAccessIDs)))) {
                        $result = true;
                    }
                }

                break;
            case 'order':
                $orderPrivilegesAccessIDs = json_decode(Redis::get('change_order_privileges_' . $userID), true);

                if(!empty($orderPrivilegesAccessIDs)) {
                    if($pathArray[2] == 'approve' || $pathArray[2] == 'unapprove') {
                        $result = true;

                    } else {
                        if(in_array('all', array_keys($orderPrivilegesAccessIDs)) && !in_array($modelID, array_keys($orderPrivilegesAccessIDs))) {
                            if ($orderPrivilegesAccessIDs['all'] == 'full' || $orderPrivilegesAccessIDs['all'] == 'edit' || $orderPrivilegesAccessIDs['all'] == 'approve') {
                                $result = true;
                            }
                        } else {
                            if(($pathArray[2] == 'create' || $pathArray[2] == 'save' || $pathArray[2] == 'store' || $pathArray[2] == 'updatesave') && (in_array('full', array_values($orderPrivilegesAccessIDs)) || in_array('edit', array_values($orderPrivilegesAccessIDs)) || in_array('approve', array_values($orderPrivilegesAccessIDs)))) {
                                $result = true;
                            } else {
                                if(!empty($modelID) && isset($orderPrivilegesAccessIDs[$modelID])) {
                                    if($orderPrivilegesAccessIDs[$modelID] == 'full' || $orderPrivilegesAccessIDs[$modelID] == 'edit' || $orderPrivilegesAccessIDs[$modelID] == 'approve') {
                                        $result = true;
                                    }
                                }
                            }
                        }
                    }
                } elseif (!empty($contractPrivilegesAccessIDs)) {
                    if($pathArray[2] == 'approve' || $pathArray[2] == 'unapprove') {
                        $result = true;

                    } else {
                        if(($pathArray[2] == 'create' || $pathArray[2] == 'save' || $pathArray[2] == 'store' || $pathArray[2] == 'updatesave') && (in_array('full', array_values($contractPrivilegesAccessIDs)) || in_array('edit', array_values($contractPrivilegesAccessIDs)) || in_array('approve', array_values($contractPrivilegesAccessIDs)))) {
                            $result = true;
                        }
                    }
                } elseif (!empty($projectPrivilegesAccessIDs)) {
                    if($pathArray[2] == 'approve' || $pathArray[2] == 'unapprove') {
                        $result = true;
                    } else {
                        if(($pathArray[2] == 'create' || $pathArray[2] == 'save' || $pathArray[2] == 'store' || $pathArray[2] == 'updatesave') && (in_array('full', array_values($projectPrivilegesAccessIDs)) || in_array('edit', array_values($projectPrivilegesAccessIDs)) || in_array('approve', array_values($projectPrivilegesAccessIDs)))) {
                            $result = true;
                        }
                    }
                } elseif (!empty($customerPrivilegesAccessIDs)) {
                    if($pathArray[2] == 'approve' || $pathArray[2] == 'unapprove') {
                        $result = true;
                    } else {
                        if(($pathArray[2] == 'create' || $pathArray[2] == 'save' || $pathArray[2] == 'store' || $pathArray[2] == 'updatesave') && (in_array('full', array_values($customerPrivilegesAccessIDs)) || in_array('edit', array_values($customerPrivilegesAccessIDs)) || in_array('approve', array_values($customerPrivilegesAccessIDs)))) {
                            $result = true;
                        }
                    }
                }

                break;
            case 'invoice':
                $invoicePrivilegesAccessIDs = json_decode(Redis::get('invoice_privileges_' . $userID), true);

                if(in_array('all', array_keys($invoicePrivilegesAccessIDs)) && !in_array($modelID, array_keys($invoicePrivilegesAccessIDs))) {
                    if ($invoicePrivilegesAccessIDs['all'] == 'full' || $invoicePrivilegesAccessIDs['all'] == 'edit' ||  $invoicePrivilegesAccessIDs['all'] == 'approve') {
                        $result = true;
                    }
                } else {
                    if($pathArray[2] == 'create' || $pathArray[2] == 'viewg703' || $pathArray[2] == 'viewg702' || $pathArray[2] == 'document') {
                        $result = true;
                    }

                    if(!empty($modelID) && (isset($invoicePrivilegesAccessIDs[$modelID])) && ($invoicePrivilegesAccessIDs[$modelID] == 'full' || $invoicePrivilegesAccessIDs[$modelID] == 'edit' || $invoicePrivilegesAccessIDs[$modelID] == 'approve')) {
                        $result = true;
                    }
                }

                break;
            case 'project':
                if(!empty($projectPrivilegesAccessIDs)) {
                    if(in_array('all', array_keys($projectPrivilegesAccessIDs)) && !in_array($modelID, array_keys($projectPrivilegesAccessIDs))) {
                        if ($projectPrivilegesAccessIDs['all'] == 'full' || $projectPrivilegesAccessIDs['all'] == 'edit' || $projectPrivilegesAccessIDs['all'] == 'approve') {
                            $result = true;
                        }
                    } else {
                        if(($pathArray[2] == 'create' || $pathArray[2] == 'store' || $pathArray[2] == 'edit' || $pathArray[2] == 'updatestore') && (in_array('full', array_values($projectPrivilegesAccessIDs)) || in_array('edit', array_values($projectPrivilegesAccessIDs)) || in_array('approve', array_values($projectPrivilegesAccessIDs)))) {
                            $result = true;
                        }

                    }
                } elseif (!empty($customerPrivilegesAccessIDs)) {
                    if(($pathArray[2] == 'create' || $pathArray[2] == 'store' || $pathArray[2] == 'edit' || $pathArray[2] == 'updatestore') && (in_array('full', array_values($customerPrivilegesAccessIDs)) || in_array('edit', array_values($customerPrivilegesAccessIDs)) || in_array('approve', array_values($customerPrivilegesAccessIDs)))) {
                        $result = true;
                    }
                }
                break;
        }

        if (!$result) {
            throw new AuthorizationException();
        }

        return $next($request);
    }
}
