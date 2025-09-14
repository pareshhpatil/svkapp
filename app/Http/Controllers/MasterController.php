<?php

namespace App\Http\Controllers;

use App\Http\Lib\Encryption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\MasterModel;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class MasterController extends Controller
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



    public function masterAdd($type)
    {
        if ($type == '__manifest.json') {
            return false;
        }
        $data['menu'] = 0;
        $data['title'] = 'Add ' . $type;
        return view('master.' . $type . '-add', $data);
    }

    public function masterList($type)
    {
        $data['menu'] = 0;
        $data['title'] = 'List ' . $type;
        $drivers = $this->model->getTableListOrderby('driver', 'is_active', 1, 'desc', 'id', 'id,name,mobile,photo');
        $array = json_decode(json_encode($drivers), 1);
        $data['drivers'] = $this->EncryptList($array, 0, '/master/update/driver/', 'id');
        return view('master.' . $type . '-list', $data);
    }

    public function masterUpdate($type, $link)
    {
        $id = Encryption::decode($link);
        $data['menu'] = 0;
        $data['title'] = 'Update ' . $type;
        $driver = $this->model->getTableRow('driver', 'id', $id);
        $data['data'] = $driver;
        $data['id'] = $id;
        return view('master.' . $type . '-update', $data);
    }

    public function callIVR($to)
    {
        $from = Session::get('mobile');
        $ApiController = new ApiController();
        $result = $ApiController->ivrCall($from, $to);
        $array['from'] = $from;
        $array['to'] = $to;
        $array['user_id'] = Session::get('user_id');
        $array['response'] = $result;
        $this->model->saveTable('call_ivr', $array, $array['user_id']);
        $from_user = $this->model->getTableRow('users', 'mobile', $from, 0, [], 'name,user_type');
        $to_user = $this->model->getTableRow('users', 'mobile', $to, 0, [], 'name,user_type');
        if ($from_user != false) {
            if ($from_user->user_type == 5) {
                $from = 'Employee ' . $from_user->name . ' - ' . $from;
            } else if ($from_user->user_type == 4) {
                $from = 'Driver ' . $from_user->name . ' - ' . $from;
            } else if ($from_user->user_type == 3) {
                $from = 'Supervisor ' . $from_user->name . ' - ' . $from;
            }
        }
        if ($to_user != false) {
            if ($to_user->user_type == 5) {
                $to = 'Employee ' . $to_user->name . ' - ' . $to;
            } else if ($to_user->user_type == 4) {
                $to = 'Driver ' . $to_user->name . ' - ' . $to;
            } else if ($to_user->user_type == 3) {
                $to = 'Supervisor ' . $to_user->name . ' - ' . $to;
            }
        } else {
            $passenger = $this->model->getColumnValue('passenger', 'mobile', $to, 'employee_name');
            if ($passenger != false) {
                $to = 'Employee ' . $passenger . ' - ' . $to;
            }
        }

        $tokens[] = env('MY_TOKEN');
        $ApiController->sendNotification(1, 1, 'Call Initiated ', $from . ' to ' . $to,  '', '');
    }

    public function masterSave(Request $request, $type, $source = 'web')
    {
        $array = $request->all();
        unset($array['_token']);

        if ($type == 'driver') {
            $id = $this->model->getColumnValue('driver', 'mobile', $array['mobile'], 'id');
            if ($id == false) {
                $id = $this->model->saveTable('driver', $array, Session::get('user_id'));
            }
        } else if ($type == 'vehicle') {

            $array['name'] = $array['brand'] . ' - ' . $array['number'];
            $array['admin_id'] = 1;
            $id = $this->model->getColumnValue('vehicle', 'number', $array['number'], 'vehicle_id', ['admin_id' => 1]);
            if ($id == false) {
                $id = $this->model->saveTable('vehicle', $array, Session::get('user_id'));
            }
        } else if ($type == 'escort') {
            $array['passenger_type'] = 2;
            $array['gender'] = 'Male';
            $array['location'] = '';
            $id = $this->model->getColumnValue('passenger', 'mobile', $array['mobile'], 'id', ['passenger_type' => 2, 'project_id' => $array['project_id']]);
            if ($id == false) {
                $id = $this->model->saveTable('passenger', $array, Session::get('user_id'));
            }
        }
        if ($source == 'api') {
            return $id;
        }
        if ($type == 'driver') {
            return redirect('/master/update/driver/' . Encryption::encode($id));
        }
        return redirect()->back()->with('message', 'Added successfully');
    }

    public function chat($link)
    {
        if ($link == '__manifest.json') {
            return false;
        }
        $id = Encryption::decode($link);
        $group = $this->model->getTableRow('chat_group', 'id', $id);
        $messages = $this->model->getChatMessages($id);
        $data['menu'] = 0;
        $data['created_date'] = $this->htmlDateTime($group->created_date);
        $data['user_id'] = Session::get('user_id');
        $data['group_id'] = $id;
        $data['link'] = $link;
        $data['messages'] = $messages;
        $data['title'] = $group->name;
        $data['hide_menu'] = true;
        return view('master.chat', $data);
    }

    public function chatMessage($link)
    {
        $id = Encryption::decode($link);
        $messages = $this->model->getChatMessages($id);

        return json_encode($messages);
    }


    public function whatsapp($link)
    {

        if ($link == '__manifest.json') {
            return false;
        }
        $id = Encryption::decode($link);
        //$group = $this->model->getTableRow('chat_group', 'id', $id);
        $messages = $this->model->getWhatsappMessages($id);
        $name = $this->model->getChatName($id);
        $data['menu'] = 0;
        $data['created_date'] = date('Y-m-d');
        $data['user_id'] = Session::get('user_id');
        $data['group_id'] = $id;
        $data['link'] = $link;
        $messages = json_encode($messages);
        $messages = str_replace('\n', '<br>', $messages);
        $messages = str_replace("'", "\'", $messages);
        $data['messages'] = $messages;
        $data['title'] = substr($name, 0, 15) . ' - ' . $id;
        $data['hide_menu'] = true;
        return view('master.whatsapp', $data);
    }

    public function whatsappMessage($link)
    {
        $id = Encryption::decode($link);
        $messages = $this->model->getWhatsappMessages($id);
        $messages = json_encode($messages);
        $messages = str_replace('\n', '<br>', $messages);
        return $messages;
    }

    public function whatsappSubmit(Request $request)
    {
        $array['type'] = $request->message_type;
        $array['message'] = $request->message;
        $body = $request->message;
        $image = '';
        if ($request->file()) {
            $file_name = 'whatsapp/' . time() . rand(1, 999) . '_chat.' . $request->file->extension();
            Storage::disk('s3')->put($file_name, file_get_contents($request->file('file')));
            $path = Storage::disk('s3')->url($file_name);
            $array['message'] = $path;
            $image = $path;
            $body = $image;
        }




        if ($array['type'] == 3) {
            $image = env('APP_URL') . '/assets/img/navigation.png';
            $body = 'Location received';
        }
        $url = env('APP_URL') . '/whatsapp/' . Encryption::encode($request->group_id);
        $array['group_id'] = $request->group_id;
        $array['user_id'] = Session::get('user_id');
        $array['name'] = Session::get('name');

        $ApiController = new ApiController();
        $usertokens = $this->model->getChatMembers($request->group_id, $array['user_id']);
        $tokens = [];
        foreach ($usertokens as $ut) {
            if ($ut['token'] != '') {
                $tokens[] = $ut['token'];
            }
        }
        if (!empty($tokens)) {
            // $ApiController->sendNotification($array['user_id'], 5, 'Message from ' . $array['name'], $body, $url, $image, $tokens);
        }

        $data_array['mobile'] = $request->group_id;
        $data_array['type'] = 'Sent';
        $data_array['status'] = 'Sent';
        if ($array['type'] == 1) {
            $data_array['message_type'] = 'text';
        } else if ($array['type'] == 2) {
            $data_array['message_type'] = 'image';
        } else if ($array['type'] == 3) {
            $data_array['message_type'] = 'location';
        }
        $data_array['message'] = $body;

        $whatsapparray['messaging_product'] = 'whatsapp';
        $whatsapparray['to'] = '91' . $request->group_id;
        $whatsapparray['type'] = $data_array['message_type'];
        if ($data_array['message_type'] == 'text') {
            $whatsapparray['text']['body'] = $body;
        } else if ($data_array['message_type'] == 'image') {
            $whatsapparray['image']['link'] = $body;
            $whatsapparray['image']['caption'] = '';
        } else if ($data_array['message_type'] == 'location') {
            $whatsapparray['location']['latitude'] = $request->mylatitude;
            $whatsapparray['location']['longitude'] = $request->mylongitude;
            $whatsapparray['location']['name'] = 'Location';
        }

        $data_array['name'] = $this->model->getChatName($request->group_id);

        $data_array['message_id'] = $this->sendWhatsappMessage($whatsapparray);

        $this->model->saveTable('whatsapp_messages', $data_array);
        $messages = $this->model->getWhatsappMessages($request->group_id);
        $messages = json_encode($messages);
        $messages = str_replace('\n', '<br>', $messages);
        return $messages;
    }

    function sendWhatsappMessage($array)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('WHATSAPP_TOKEN'),
            'Content-Type' => 'application/json', // Specify JSON content type if sending JSON data
        ])->post('https://graph.facebook.com/v19.0/350618571465341/messages', $array);

        if ($response->successful()) {
            $responseData = $response->json(); // Convert response to JSON
            // Handle successful response
            return $responseData['messages'][0]['id'];
        } else {
            // Handle failed request
            dd($response->status(), $response->body()); // Output status code and response body for debugging
        }
    }

    public function chatSubmit(Request $request)
    {
        $array['type'] = $request->message_type;
        $array['message'] = $request->message;
        $body = $request->message;
        $image = '';
        if ($request->file()) {

            $file_name = 'chats/' . time() . rand(1, 999) . '_chat.' . $request->file->extension();
            Storage::disk('s3')->put($file_name, file_get_contents($request->file('file')));
            $path = Storage::disk('s3')->url($file_name);
            $array['message'] = $path;
            $image = $path;
            $body = 'Image received';
        }
        if ($array['type'] == 3) {
            $image = env('APP_URL') . '/assets/img/navigation.png';
            $body = 'Location received';
        }
        $url = env('APP_URL') . '/chat/' . Encryption::encode($request->group_id);
        $array['group_id'] = $request->group_id;
        $array['user_id'] = Session::get('user_id');
        $array['name'] = Session::get('name');

        $ApiController = new ApiController();
        $usertokens = $this->model->getChatMembers($request->group_id, $array['user_id']);
        $tokens[] = env('MY_TOKEN');
        foreach ($usertokens as $ut) {
            if ($ut['token'] != '') {
                $tokens[] = $ut['token'];
            }
        }
        if (!empty($tokens)) {
            $ApiController->sendNotification($array['user_id'], 5, 'Message from ' . $array['name'], $body, $url, $image, $tokens);
        }

        $this->model->saveTable('chat_message', $array, Session::get('user_id'));
        $messages = $this->model->getChatMessages($request->group_id);
        return json_encode($messages);
    }

    public function chatCreate($user_type, $ride_id, $type, $passenger_id, $requested_passenger_id)
    {
        $array['ride_id'] = $ride_id;
        $array['user_type'] = $user_type;
        $array['type'] = $type;
        $array['passenger_id'] = $passenger_id;
        $array['requested_passenger_id'] = $requested_passenger_id;
        $group_id = $this->model->getColumnValue('chat_group', 'ride_id', $ride_id, 'id', $array);
        if ($group_id != false) {
            return redirect('/chat/' . Encryption::encode($group_id));
        }
        if ($ride_id > 0) {
            $ride = $this->model->getTableRow('ride', 'id', $ride_id);
        } else {
            $ride = $this->model->getTableRow('passenger', 'id', $passenger_id);
        }
        if ($user_type == 5) {
            $passenger = $this->model->getTableRow('users', 'parent_id', $passenger_id, 1, ['user_type' => 5]);
            $user_id = $passenger->id;
            $users[] = array('id' => $user_id, 'name' => $passenger->name);
            $array['project_id'] = $ride->project_id;
            switch ($type) {
                case 1:
                    $array['name'] = $passenger->name . ' - Supervisor';
                    $rows = $this->model->getList('users', ['user_type' => 3, 'is_active' => 1]);
                    foreach ($rows as $row) {
                        $users[] = array('id' => $row->id, 'name' => $row->name);
                    }
                    break;
                case 2:
                    $array['name'] = $passenger->name . ' - Driver';
                    $driver = $this->model->getTableRow('users', 'parent_id', $ride->driver_id, 1, ['user_type' => 4]);
                    $users[] = array('id' => $driver->id, 'name' => $driver->name);
                    break;
                case 3:
                    $array['name'] = 'Co Passengers Group';
                    $rows = $this->model->getRidePassengers($ride_id, $passenger_id);
                    foreach ($rows as $row) {
                        $users[] = array('id' => $row->id, 'name' => $row->name);
                    }
                    break;
                case 4:
                    $copassenger = $this->model->getTableRow('users', 'parent_id', $requested_passenger_id, 1, ['user_type' => 5]);
                    $array['name'] = $passenger->name . ' - Co Passenger';
                    if (!empty($copassenger)) {
                        $users[] = array('id' => $copassenger->id, 'name' => $copassenger->name);
                        $array['name'] = $passenger->name . ' - ' . $copassenger->name;
                    }
                    break;
            }
            $group_id = $this->model->saveTable('chat_group', $array, Session::get('user_id'));
            foreach ($users as $user) {
                $array = [];
                $array['group_id'] = $group_id;
                $array['ride_id'] = $ride_id;
                $array['user_id'] = $user['id'];
                $array['name'] = $user['name'];
                $this->model->saveTable('chat_group_member', $array, Session::get('user_id'));
            }
        }
        return redirect('/chat/' . Encryption::encode($group_id));
    }

    public function getZone($project_id, $car_type)
    {
        $zones = $this->model->getList('zone', ['project_id' => $project_id, 'car_type' => $car_type, 'is_active' => 1], 'zone_id,zone');
        $zones = json_decode(json_encode($zones), 1);
        return response()->json($zones);
    }

    public function getShifts($project_id, $pickup_drop)
    {
        $shifts = $this->model->getList('shift', ['project_id' => $project_id, 'type' => $pickup_drop, 'is_active' => 1], 'shift_time,name');
        $shifts = json_decode(json_encode($shifts), 1);
        return response()->json($shifts);
    }

    public function saveRide(Request $request)
    {
        $array = $request->all();
        unset($array['_token']);
        $this->model->saveTable('ride', $array, Session::get('user_id'));
        return redirect('/my-rides');
    }


}
