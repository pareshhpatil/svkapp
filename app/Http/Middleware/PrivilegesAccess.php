<?php

namespace App\Http\Middleware;

use App\Libraries\Encrypt;
use App\Model\Master;
use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

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

        //$masterModel = new Master();

        $result = true;

        switch ($pathArray[1]) {
            case 'contract':
                $contractPrivilegesAccessIDs = json_decode(Redis::get('contract_privileges_' . $userID), true);
                
                if($userRole !== 'Admin') {
                    if(in_array('all', array_keys($contractPrivilegesAccessIDs)) && !in_array($modelID, array_keys($contractPrivilegesAccessIDs))) {
                        if ($contractPrivilegesAccessIDs['all'] !== 'full' && $contractPrivilegesAccessIDs['all'] !== 'edit') {
                            $result = false;
                        }
                    } else {
                        if($pathArray[2] == 'create' && (in_array('full', array_values($contractPrivilegesAccessIDs)) || in_array('edit', array_values($contractPrivilegesAccessIDs)))) {
                            $result = true;
                        } else {
                            if(empty($modelID)) {
                                $result = false;
                            } else {
                                if ($contractPrivilegesAccessIDs[$modelID] !== 'full' && $contractPrivilegesAccessIDs[$modelID] !== 'edit') {
                                    $result = false;
                                }
                            }
                        }
                    }
                }

                break;
            case 'order':
                $orderPrivilegesAccessIDs = json_decode(Redis::get('change_order_privileges_' . $userID), true);
                //$privilegesAccessIDs = $masterModel->getUserPrivilegesAccessIDsWithType($userID, 'change-order');

                if($userRole !== 'Admin') {
                    if($pathArray[2] == 'approve' || $pathArray[2] == 'unapprove') {
                        if(in_array('all', array_keys($orderPrivilegesAccessIDs)) && !in_array($modelID, array_keys($orderPrivilegesAccessIDs))) {
                            if ($orderPrivilegesAccessIDs['all'] !== 'full' && $orderPrivilegesAccessIDs['all'] !== 'approve') {
                                $result = false;
                            }
                        } else {
                            if ($orderPrivilegesAccessIDs[$modelID] !== 'full' && $orderPrivilegesAccessIDs[$modelID] !== 'approve') {
                                $result = false;
                            }
                        }
                    } else {
                        if(in_array('all', array_keys($orderPrivilegesAccessIDs)) && !in_array($modelID, array_keys($orderPrivilegesAccessIDs))) {
                            if ($orderPrivilegesAccessIDs['all'] !== 'full' && $orderPrivilegesAccessIDs['all'] !== 'edit') {
                                $result = false;
                            }
                        } else {
                            if($pathArray[2] == 'create' && (in_array('full', array_values($orderPrivilegesAccessIDs)) || in_array('edit', array_values($orderPrivilegesAccessIDs)))) {
                                $result = true;
                            } else {
                                if(empty($modelID)) {
                                    $result = false;
                                } else {
                                    if ($orderPrivilegesAccessIDs[$modelID] !== 'full' && $orderPrivilegesAccessIDs[$modelID] !== 'edit') {
                                        $result = false;
                                    }
                                }
                            }
                        }
                    }
                }

                break;
            case 'invoice':
                $invoicePrivilegesAccessIDs = json_decode(Redis::get('invoice_privileges_' . $userID), true);
                //$privilegesAccessIDs = $masterModel->getUserPrivilegesAccessIDsWithType($userID, 'invoice');

                if($userRole !== 'Admin') {
                    if(in_array('all', array_keys($invoicePrivilegesAccessIDs)) && !in_array($modelID, array_keys($invoicePrivilegesAccessIDs))) {
                        if ($invoicePrivilegesAccessIDs['all'] !== 'full' && $invoicePrivilegesAccessIDs['all'] !== 'edit') {
                            $result = false;
                        }
                    } else {
                        if($pathArray[2] == 'create' && (in_array('full', array_values($invoicePrivilegesAccessIDs)) || in_array('edit', array_values($invoicePrivilegesAccessIDs)) || in_array('approve', array_values($invoicePrivilegesAccessIDs)))) {
                            $result = true;
                        } else {
                            if(empty($modelID)) {
                                $result = false;
                            } else {
                                if ($invoicePrivilegesAccessIDs[$modelID] !== 'full' && $invoicePrivilegesAccessIDs[$modelID] !== 'edit' && $invoicePrivilegesAccessIDs[$modelID] !== 'approve') {
                                    $result = false;
                                }
                            }
                        }
                    }
                }

                break;
            case 'project':
                $projectPrivilegesAccessIDs = json_decode(Redis::get('project_privileges_' . $userID), true);

                if($userRole !== 'Admin') {
                    if(in_array('all', array_keys($projectPrivilegesAccessIDs)) && !in_array($modelID, array_keys($projectPrivilegesAccessIDs))) {
                        if ($projectPrivilegesAccessIDs['all'] !== 'full' && $projectPrivilegesAccessIDs['all'] !== 'edit') {
                            $result = false;
                        }
                    } else {
                        if($pathArray[2] == 'create' && (in_array('full', array_values($projectPrivilegesAccessIDs)) || in_array('edit', array_values($projectPrivilegesAccessIDs)))) {
                            $result = true;
                        } else {
                            if(empty($modelID)) {
                                $result = false;
                            } else {
                                if ($projectPrivilegesAccessIDs[$modelID] !== 'full' && $projectPrivilegesAccessIDs[$modelID] !== 'edit') {
                                    $result = false;
                                }
                            }
                        }
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
