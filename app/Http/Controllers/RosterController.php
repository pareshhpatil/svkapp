<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\MasterModel;
use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Cell_DataType;
use Illuminate\Support\Facades\Auth;
use App\Models\Message;
use App\Events\SendMessage;

class RosterController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public $model = null;

    public function __construct()
    {
        $this->model = new MasterModel();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create()
    {
        $data['selectedMenu'] = [2, 3];
        $data['menus'] = Session::get('menus');
        $data['project_list'] = $this->model->getTableList('project', 'is_active', 1);

        return view('web.roster.create', $data);
    }

    public function list(Request $request, $bulk_id = 0, $type = 0)
    {
        $data['selectedMenu'] = [2, 4];
        $data['menus'] = Session::get('menus');
        $data['bulk_id'] = $bulk_id;
        $data['type'] = $type;
        $data['project_id'] = (isset($request->project_id) ? $request->project_id : 0);
        $data['project_list'] = $this->model->getTableList('project', 'is_active', 1);
        return view('web.passenger.list', $data);
    }



    public function chat(){
        return view('chat');
    }

    public function messages(){
        return Message::with('user')->get();
    }

    public function messageStore(Request $request){
        $user = Auth::user();

        $messages = $user->messages()->create([
            'message' => $request->message
        ]);

        broadcast(new SendMessage($user, $messages))->toOthers();

        return 'message sent';
    }
    
}
