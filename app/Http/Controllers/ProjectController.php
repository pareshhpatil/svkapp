<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Project;
use Validator;

class ProjectController extends Controller
{
    private $projectModel = null;

    public function __construct()
    {
       
        $this->projectModel = new Project();
    }

    //api to get project list
    function getProjectList(Request $request) {
        $validator = Validator::make($request->all(), [
            'start' => 'numeric',
            'limit' => 'numeric',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
        $start = ($request->start > 0) ? $request->start : 0;
        $limit = ($request->limit > 0) ? $request->limit : 15;

        $projectlists = $this->projectModel->getProjectList($request->merchant_id,$start,$limit);
        $success['lastno'] = count($projectlists) + $start;
        $success['list'] = $projectlists;
        return response()->json(['success' => $success], 200);
    }

}
