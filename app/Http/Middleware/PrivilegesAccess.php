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

        //$masterModel = new Master();

        $result = true;

        switch ($pathArray[1]) {
            case 'contract':
                $privilegesAccessIDs = json_decode(Redis::get('contract_privileges_' . $userID), true);
//                $privilegesAccessIDs = $masterModel->getUserPrivilegesAccessIDsWithType($userID, 'contract');

                if(in_array('all', array_keys($privilegesAccessIDs)) && !in_array($modelID, array_keys($privilegesAccessIDs))) {
                    if ($privilegesAccessIDs['all'] !== 'full' && $privilegesAccessIDs['all'] !== 'edit') {
                        $result = false;
                    }   
                } else {
                    if ($privilegesAccessIDs[$modelID] !== 'full' && $privilegesAccessIDs[$modelID] !== 'edit') {
                        $result = false;
                    }
                }

                break;
            case 'order':
                $privilegesAccessIDs = json_decode(Redis::get('change_order_privileges_' . $userID), true);
                //$privilegesAccessIDs = $masterModel->getUserPrivilegesAccessIDsWithType($userID, 'change-order');

                if($pathArray[2] == 'approve' || $pathArray[2] == 'unapprove') {
                    if(in_array('all', array_keys($privilegesAccessIDs)) && !in_array($modelID, array_keys($privilegesAccessIDs))) {
                        if ($privilegesAccessIDs['all'] !== 'full' && $privilegesAccessIDs['all'] !== 'approve') {
                            $result = false;
                        }
                    } else {
                        if ($privilegesAccessIDs[$modelID] !== 'full' && $privilegesAccessIDs[$modelID] !== 'approve') {
                            $result = false;
                        }
                    }
                } else {
                    if(in_array('all', array_keys($privilegesAccessIDs)) && !in_array($modelID, array_keys($privilegesAccessIDs))) {
                        if ($privilegesAccessIDs['all'] !== 'full' && $privilegesAccessIDs['all'] !== 'edit') {
                            $result = false;
                        }
                    } else {
                        if ($privilegesAccessIDs[$modelID] !== 'full' && $privilegesAccessIDs[$modelID] !== 'edit') {
                            $result = false;
                        }
                    }
                }

                break;
            case 'invoice':
                $privilegesAccessIDs = json_decode(Redis::get('invoice_privileges_' . $userID), true);
                //$privilegesAccessIDs = $masterModel->getUserPrivilegesAccessIDsWithType($userID, 'invoice');

                if(in_array('all', array_keys($privilegesAccessIDs)) && !in_array($modelID, array_keys($privilegesAccessIDs))) {
                    if ($privilegesAccessIDs['all'] !== 'full' && $privilegesAccessIDs['all'] !== 'edit') {
                        $result = false;
                    }
                } else {
                    if ($privilegesAccessIDs[$modelID] !== 'full' && $privilegesAccessIDs[$modelID] !== 'edit') {
                        $result = false;
                    }
                }

                break;
            case 'project':
                $privilegesAccessIDs = json_decode(Redis::get('project_privileges_' . $userID), true);

                if(in_array('all', array_keys($privilegesAccessIDs)) && !in_array($modelID, array_keys($privilegesAccessIDs))) {
                    if ($privilegesAccessIDs['all'] !== 'full' && $privilegesAccessIDs['all'] !== 'edit') {
                        $result = false;
                    }
                } else {
                    if ($privilegesAccessIDs[$modelID] !== 'full' && $privilegesAccessIDs[$modelID] !== 'edit') {
                        $result = false;
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
