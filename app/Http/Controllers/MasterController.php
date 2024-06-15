<?php

namespace App\Http\Controllers;

use App\Http\Lib\Encryption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\MasterModel;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Http;

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
        $tokens[] = env('MY_TOKEN');
        $ApiController->sendNotification(1, 3, 'Call Initiated ', $from . ' to ' . $to,  '', '', $tokens);
    }

    public function masterSave(Request $request, $type)
    {
        $array = $request->all();
        if ($type == 'driver') {
            unset($array['_token']);
            $this->model->saveTable('driver', $array, Session::get('user_id'));
        } else if ($type == 'vehicle') {
            unset($array['_token']);
            $array['name'] = $array['brand'] . ' - ' . $array['number'];
            $array['admin_id'] = 1;
            $this->model->saveTable('vehicle', $array, Session::get('user_id'));
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
        $group = $this->model->getTableRow('chat_group', 'id', $id);
        $messages = $this->model->getWhatsappMessages($id);
        $name = $this->model->getChatName($id);
        $data['menu'] = 0;
        $data['created_date'] = date('Y-m-d');
        $data['user_id'] = Session::get('user_id');
        $data['group_id'] = $id;
        $data['link'] = $link;
        $messages = json_encode($messages);
        $messages = str_replace('\n', '<br>', $messages);
        $data['messages'] = $messages;
        $data['title'] = substr($name, 0, 20) . ' - ' . $id;
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
            $file_name = time() . rand(1, 999) . '_chat.' . $request->file->extension();
            $file_path = $request->file('file')->storeAs('uploads', $file_name, 'public');
            $path = '/storage/' . $file_path;
            $array['message'] = $path;
            $image = env('APP_URL') . $path;
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
            $file_name = time() . rand(1, 999) . '_chat.' . $request->file->extension();
            $file_path = $request->file('file')->storeAs('uploads', $file_name, 'public');
            $path = '/storage/' . $file_path;
            $array['message'] = $path;
            $image = env('APP_URL') . $path;
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
        $tokens = [];
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
}
