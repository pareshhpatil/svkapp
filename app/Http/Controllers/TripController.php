<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Lib\Encryption;
use App\Models\ParentModel;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingEmail;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;



class TripController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public $model;
    public function __construct()
    {
        $this->model = new ParentModel();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function rideLiveTrack(Request $request, $ride_id)
    {
        // $response = $this->model->updateTable('ride_live_location', 'ride_id', $ride_id, 'live_location', json_encode($request->all()));

        //   Log::info('Tracking POST: ' . json_encode($_POST));
        //  $seq = rand(0, 5);
        // sleep($seq);
        Log::error('Tracking: ' . json_encode($request->all()));

        $live_location = $request->all();
        $array['latitude'] = $live_location['latitude'];
        $array['longitude'] = $live_location['longitude'];
        $array['speed'] = $live_location['speed'];
        $array['speedAccuracy'] = (isset($live_location['speedAccuracy'])) ? $live_location['speedAccuracy'] : 0;
        $array['live_location'] = json_encode($array);
        $array['ride_id'] = $ride_id;

        $value = Cache::get('ride_' . $ride_id);
        if ($value != false) {
            $cache_array = json_decode($value, true);
            if ($cache_array['latitude'] == $array['latitude'] && $cache_array['longitude'] == $array['longitude']) {
                Log::error('Tracking: Same');
                return true;
            }
        }
        //Log::error('Tracking: ' . json_encode($request->all()));


        Cache::put('ride_' . $ride_id, $array['live_location'], 8600); // 3600 seconds = 1 hour

        //  if ($response == false) {
        //  $this->model->saveTable('ride_live_location', $array);
        //  }
        $this->model->saveTable('ride_location_track', $array);
        return true;
    }

    public function rideLocation($ride_id)
    {
        $value = Cache::get('ride_' . $ride_id);
        if ($value != false) {
            return $value;
        }
        $array = $this->model->getColumnValue('ride_location_track', 'ride_id', $ride_id, 'live_location', [], 'id');
        return $array;
    }
    public function rating($id, $rating)
    {
        $array = $this->model->updateTable('ride_passenger', 'id', $id, 'rating', $rating);
    }

    public  function dateFetch($date, $type)
    {
        if ($type == 1) {
            return $this->htmlDate(date('Y-m-d', strtotime($date . ' + 1 days')));
        } else {
            return $this->htmlDate(date('Y-m-d', strtotime($date . ' - 1 days')));
        }
    }
    public function driverAppRideDetail($id)
    {
        $link = Encryption::encode($id);
        return redirect('https://app.svktrv.in/driver/ride/' . $link);
    }

    public function sendemail($to_email, $ccEmails, $subject, $body)
    {
        $cc_array = [];
        foreach ($ccEmails as $k => $email) {
            $cc_array[$k]['email'] = $email;
        }
        $curl = curl_init();
        $json = '{
            "sender":{
               "name":"Siddhivinayak Travels House",
               "email":"contact@siddhivinayaktravelshouse.in"
            },
            "to":[
               {
                  "email":"' . $to_email . '"
               }
            ],
            "cc":' . json_encode($cc_array) . ',
            "subject":"' . $subject . '",
            "htmlContent":""
         }';

        $array = json_decode($json, 1);
        $array['htmlContent'] = $body;
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.brevo.com/v3/smtp/email',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($array),
            CURLOPT_HTTPHEADER => array(
                'accept: application/json',
                'api-key: ' . env('EMAIL_KEY'),
                'content-type: application/json'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
    }

    public function assignRideCab(Request $request)
    {
        $array['driver_id'] = $request->driver_id;
        $array['vehicle_id'] = $request->vehicle_id;
        $array['escort'] = ($request->escort_id > 0) ? 1 : 0;
        $ride_id = $request->ride_id;
        $vehicle_id = $request->vehicle_id;
        $driver_id = $request->driver_id;
        $escort_id = ($request->escort_id > 0) ? $request->escort_id : 0;
        $ride = $this->model->getTableRow('ride', 'id', $ride_id);
        $old_driver_id = $ride->driver_id;
        $array['status'] = 1;
        $this->model->updateArray('ride', 'id', $ride_id, $array);
        $type = $ride->type;
        $vehicle_number = $this->model->getColumnValue('vehicle', 'vehicle_id', $vehicle_id, 'number');
        $project_location = $this->model->getColumnValue('project', 'project_id', $ride->project_id, 'location');
        $array['title'] = $type . ' ' . substr($vehicle_number, -4);
        if ($type == 'Drop') {
            $location = $this->model->getColumnValue('ride_passenger', 'ride_id', $ride_id, 'drop_location', [], 'id');
            $array['start_location'] = $project_location;
            $array['end_location'] = $location;
        } else {
            $location = $this->model->getColumnValue('ride_passenger', 'ride_id', $ride_id, 'pickup_location');
            $array['start_location'] = $location;
            $array['end_location'] = $project_location;
        }
        $this->model->updateArray('ride', 'id', $ride_id, $array);
        if ($escort_id > 0) {
            if ($ride->escort > 0) {
                $this->model->updateWhereArray('ride_passenger', ['ride_id' => $ride_id, 'passenger_type' => 2], ['passenger_id' => $request->escort_id]);
            } else {
                $ride_passenger['roster_id'] = 0;
                $ride_passenger['ride_id'] = $ride_id;
                $ride_passenger['passenger_type'] = 2;
                $ride_passenger['pickup_location'] = $ride->start_location;
                $ride_passenger['drop_location'] = $ride->end_location;
                $ride_passenger['pickup_time'] = $ride->start_time;
                $ride_passenger['created_by'] = Session::get('user_id');
                $ride_passenger['last_update_by'] = Session::get('user_id');
                $ride_passenger['otp'] = rand(1111, 9999);
                $this->model->saveTable('ride_passenger', $ride_passenger, 0);
                $this->model->updateTable('ride', 'id', $ride_id, 'escort', 1);
            }
        }

        if ($old_driver_id != $driver_id) {
            $apiController = new ApiController();
            $link = Encryption::encode($ride_id);
            $url = 'https://app.svktrv.in/driver/ride/' . $link;
            $apiController->sendNotification($driver_id, 4, 'A new trip has been assigned', 'Please make sure to arrive at the pick-up location on time and provide a safe and comfortable ride to the passenger', $url);
            $driver_name = $this->model->getColumnValue('driver', 'id', $driver_id, 'name');

            $short_url = $this->random();
            $this->model->saveTable('short_url', ['short_url' => $short_url, 'long_url' => $url]);
            $params = [];
            $params[] = array('type' => 'text', 'text' => $driver_name);
            $params[] = array('type' => 'text', 'text' => $array['start_location']);
            $params[] = array('type' => 'text', 'text' => $array['end_location']);
            $params[] = array('type' => 'text', 'text' => $this->htmlShortDateTime($ride->start_time));

            $apiController->sendWhatsappMessage($driver_id, 4, 'driver_assign', $params, $short_url, 'hi', 1);

            $params = [];
            $passengers = $this->model->getTableList('ride_passenger', 'ride_id', $ride_id);

            foreach ($passengers as $row) {
                $link = Encryption::encode($row->id);
                $url = 'https://app.svktrv.in/passenger/ride/' . $link;
                $apiController->sendNotification($row->passenger_id, 5, 'Cab has been assigned for your next ride', 'Please be ready at your pickup location. Have a safe and pleasant journey.', $url);
                $short_url = $this->random();
                $this->model->saveTable('short_url', ['short_url' => $short_url, 'long_url' => $url]);
                $url = 'app.svktrv.in/l/' . $short_url;

                //$message_ = 'Cab assigned for ' . $ride->type . ' on ' . $this->htmlDate($row->pickup_time) . ' Please reach your pickup point at ' . $this->htmlTime($row->pickup_time) . ' Trip details ' . $url . ' - Siddhivinayak Travels House';
                $params['var1'] = $ride->type;
                $params['var2'] = $this->htmlDate($row->pickup_time);
                $params['var3'] = $this->htmlTime($row->pickup_time);
                $params['var4'] = $url;
                $apiController->sendUserSMS($row->passenger_id, 5, $params, '6804878cd6fc0553042e8f65');
                $employee_name = $this->model->getColumnValue('passenger', 'id', $row->passenger_id, 'employee_name');
                if ($type == 'Drop') {
                    $start_location = "Office";
                    $end_location = "Home";
                } else {
                    $start_location = "Home";
                    $end_location = "Office";
                }
                $params = [];
                $params[] = array('type' => 'text', 'text' => $employee_name);
                $params[] = array('type' => 'text', 'text' => $start_location);
                $params[] = array('type' => 'text', 'text' => $end_location);
                $params[] = array('type' => 'text', 'text' => $this->htmlShortDateTime($row->pickup_time));
                $params[] = array('type' => 'text', 'text' => $driver_name);
                $params[] = array('type' => 'text', 'text' => $vehicle_number);
                $params[] = array('type' => 'text', 'text' => $row->otp);
                $apiController->sendWhatsappMessage($row->passenger_id, 5, 'ride_confirmation', $params, $short_url, 'en', 1);
            }
        }


        return redirect('/my-rides/pending');
    }
    public function assignCab(Request $request)
    {
        $array['driver_id'] = $request->driver_id;
        $array['vehicle_id'] = $request->vehicle_id;
        $array['escort'] = ($request->escort_id > 0) ? 1 : 0;
        $ride_id = $request->ride_id;
        $ride = $this->model->getTableRow('ride', 'id', $ride_id);
        $array['status'] = 1;
        $this->model->updateArray('ride', 'id', $ride_id, $array);
        $apiController = new ApiController();
        $link = Encryption::encode($ride_id);
        $url = 'https://app.svktrv.in/driver/ride/' . $link;


        $driver_short_url = $this->random();
        $this->model->saveTable('short_url', ['short_url' => $driver_short_url, 'long_url' => $url]);

        if ($array['escort'] > 0) {
            if ($ride->escort > 0) {
                $this->model->updateWhereArray('ride_passenger', ['ride_id' => $ride_id, 'passenger_id' => 0], ['passenger_id' => $request->escort_id]);
            } else {
                $ride_passenger['roster_id'] = 0;
                $ride_passenger['ride_id'] = $ride_id;
                $ride_passenger['passenger_id'] = $request->escort_id;
                $ride_passenger['pickup_location'] = $ride->start_location;
                $ride_passenger['drop_location'] = $ride->end_location;
                $ride_passenger['pickup_time'] = $ride->start_time;
                $ride_passenger['otp'] = rand(1111, 9999);
                $this->model->saveTable('ride_passenger', $ride_passenger, 0);
            }
        }

        $apiController->sendNotification($array['driver_id'], 4, 'A new trip has been assigned', 'Please make sure to arrive at the pick-up location on time and provide a safe and comfortable ride to the passenger', $url);

        $passengers = $this->model->getTableList('ride_passenger', 'ride_id', $ride_id);

        $driver = $this->model->getTableRow('driver', 'id', $array['driver_id']);
        $passenger = $this->model->getTableRow('passenger', 'id', $passengers[0]->passenger_id);
        $employee_name = $passenger->employee_name;
        $employee_mobile = $passenger->mobile;
        $booking_id = $this->formatNumberToString($ride_id);

        $vehicle = $this->model->getTableRow('vehicle', 'vehicle_id', $ride->vehicle_id);
        $car_type = $vehicle->car_type;

        $params = [];
        $params[] = array('type' => 'text', 'text' => $driver->name);
        $params[] = array('type' => 'text', 'text' => $booking_id);
        $params[] = array('type' => 'text', 'text' => $passenger->address);
        $params[] = array('type' => 'text', 'text' => $ride->end_location);
        $params[] = array('type' => 'text', 'text' => $this->htmlDateTime($ride->start_time));
        $params[] = array('type' => 'text', 'text' => $employee_name);
        $params[] = array('type' => 'text', 'text' => ($employee_mobile != '' ? $employee_mobile : 'NA'));


        if (isset($request->emails)) {
            $data['booking_id'] = $booking_id;
            $data['pickup_time'] = $this->htmlDateTime($ride->start_time);
            $data['pickup_address'] = $request->pickup_location;
            $data['driver_name'] = $driver->name;
            $data['driver_mobile'] = $driver->mobile;
            $data['vehicle_number'] = $vehicle->number;
            $data['vehicle_type'] =  $car_type;
            $data['passengers'] = $request->passengers;

            $ccEmails = explode(',', env('CC_EMAILS'));
            $to_email = '';
            foreach ($request->emails as $email) {
                if ($to_email == '') {
                    $to_email = $email;
                } else {
                    $ccEmails[] = $email;
                }
            }
            if ($to_email != '') {
                $subject = '#' . $booking_id . ' Siddhivinayak Travels House Cab Booking Confirmed';
                $body = View::make('emails.booking', $data)->render();
                $this->sendemail($to_email, $ccEmails, $subject, $body);
                // Mail::to($to_email)->cc($ccEmails)->send(new BookingEmail('#' . $booking_id . ' Siddhivinayak Travels House Cab Booking Confirmed', $data));
            }
        }



        $apiController->sendWhatsappMessage($array['driver_id'], 4, 'driver_booking_details', $params, $driver_short_url, 'hi', 1);
        foreach ($passengers as $row) {
            $link = Encryption::encode($row->id);
            $url = 'https://app.svktrv.in/ride/detail/' . $link;
            $apiController->sendNotification($row->passenger_id, 5, 'Cab has been assigned for your next ride', 'Please be ready at your pickup location. Have a safe and pleasant journey.', $url);

            $short_url = $this->random();
            $this->model->saveTable('short_url', ['short_url' => $short_url, 'long_url' => $url]);
            $url = 'https://app.svktrv.in/l/' . $short_url;

            $message_ = 'Cab assigned for ' . $ride->type . ' on ' . $this->htmlDateTime($row->pickup_time) . ' Please reach your pickup point at ' . $this->htmlTime($row->pickup_time) . ' Trip details ' . $url . ' - Siddhivinayak Travels House';
            $apiController->userSMS($row->passenger_id, 5, $message_, '1107168138570499675');

            $passenger = $this->model->getTableRow('passenger', 'id', $row->passenger_id);
            if (!isset($request->mobiles)) {
                $mobiles = [];
            } else {
                $mobiles = $request->mobiles;
            }

            if (!in_array($passenger->mobile, $mobiles)) {
                $params = [];
                $params[] = array('type' => 'text', 'text' => $passenger->employee_name);
                $params[] = array('type' => 'text', 'text' => $booking_id);
                $params[] = array('type' => 'text', 'text' => $this->htmlDateTime($row->pickup_time));
                $params[] = array('type' => 'text', 'text' => $passenger->address);
                $params[] = array('type' => 'text', 'text' => $row->drop_location);
                $params[] = array('type' => 'text', 'text' => $vehicle->number . ' - ' . $car_type);
                $params[] = array('type' => 'text', 'text' => $driver->name);
                $params[] = array('type' => 'text', 'text' => $driver->mobile);
                $params[] = array('type' => 'text', 'text' => $passenger->employee_name);
                $apiController->sendWhatsappMessage($row->passenger_id, 5, 'booking_details', $params, $short_url, 'en', 1);
            }
        }

        if (isset($request->mobiles) || isset($request->emails)) {
            $link = Encryption::encode($ride_id);
            $url = 'https://app.svktrv.in/ride/detail/' . $link;

            $short_url = $this->random();
            $this->model->saveTable('short_url', ['short_url' => $short_url, 'long_url' => $url]);
            $url = 'https://app.svktrv.in/l/' . $short_url;
            if (!empty($request->mobiles)) {
                foreach ($request->mobiles as $mobile) {
                    $mobile = str_replace(' ', '', $mobile);
                    $mobile = trim($mobile);
                    if (strlen($mobile) == 10) {
                        $params = [];
                        $params[] = array('type' => 'text', 'text' => 'Passenger');
                        $params[] = array('type' => 'text', 'text' => $booking_id);
                        $params[] = array('type' => 'text', 'text' => $this->htmlDateTime($ride->start_time));
                        $params[] = array('type' => 'text', 'text' => $ride->start_location);
                        $params[] = array('type' => 'text', 'text' => $ride->end_location);
                        $params[] = array('type' => 'text', 'text' => $vehicle->number . ' - ' . $car_type);
                        $params[] = array('type' => 'text', 'text' => $driver->name);
                        $params[] = array('type' => 'text', 'text' => $driver->mobile);
                        $employee_mobile = ($employee_mobile != '') ? ' Mob: ' . $employee_mobile : '';
                        $params[] = array('type' => 'text', 'text' => $employee_name . $employee_mobile);
                        $apiController->sendWhatsappMessage($mobile, 'mobile', 'booking_details', $params, $short_url, 'en', 1);
                    }
                }
            }
        } else {
            return redirect('/my-rides/pending');
        }
    }


    public function shortUrl($short)
    {
        $url = $this->model->getColumnValue('short_url', 'short_url', $short, 'long_url');
        if ($url != false) {
            return redirect($url, 301);
        }
    }

    function formatNumberToString($number, $length = 10, $prefix = 'STH')
    {
        // Convert number to string
        $numberString = (string) $number;

        // Calculate the number of zeros needed
        $zerosToAdd = $length - strlen($prefix) - strlen($numberString);

        // Append zeros to the left
        $formattedString = $prefix . str_repeat('0', $zerosToAdd) . $numberString;

        return $formattedString;
    }

    function random($length_of_string = 4)
    {
        // String of all alphanumeric character
        $str_result = '0123456789bcdfghjklmnpqrstvwxyz';
        // Shuffle the $str_result and returns substring
        // of specified length
        $exist = true;
        while ($exist == true) {
            $short = substr(
                str_shuffle($str_result),
                0,
                $length_of_string
            );
            $exist = $this->model->getTableRow('short_url', 'short_url', $short);
        }
        return $short;
    }
}
