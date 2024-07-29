<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Lib\Encryption;
use App\Models\ParentModel;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingEmail;
use Log;

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
        Log::info('Tracking: ' . json_encode($request->all()));
        //  $seq = rand(0, 5);
        // sleep($seq);
        $array['live_location'] = json_encode($request->all());
        $array['ride_id'] = $ride_id;
        //  if ($response == false) {
        //  $this->model->saveTable('ride_live_location', $array);
        //  }
        $this->model->saveTable('ride_location_track', $array);
    }

    public function rideLocation($ride_id)
    {
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
        $booking_id = $this->formatNumberToString($ride_id);

        $vehicle = $this->model->getTableRow('vehicle', 'vehicle_id', $ride->vehicle_id);
        $car_type = $vehicle->car_type;

        $params = [];
        $params[] = array('type' => 'text', 'text' => $driver->name);
        $params[] = array('type' => 'text', 'text' => $booking_id);
        $params[] = array('type' => 'text', 'text' => $passenger->address);
        $params[] = array('type' => 'text', 'text' => $ride->end_location);
        $params[] = array('type' => 'text', 'text' => $this->htmlDateTime($ride->start_time));
        $params[] = array('type' => 'text', 'text' => $passenger->employee_name);
        $params[] = array('type' => 'text', 'text' => ($passenger->mobile != '' ? $passenger->mobile : 'NA'));

        $apiController->sendWhatsappMessage($array['driver_id'], 4, 'driver_booking_details', $params, $driver_short_url, 'hi', 1);
        foreach ($passengers as $row) {
            $link = Encryption::encode($row->id);
            $url = 'https://app.svktrv.in/passenger/ride/' . $link;
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
                $params[] = array('type' => 'text', 'text' => $car_type);
                $params[] = array('type' => 'text', 'text' => $driver->name);
                $params[] = array('type' => 'text', 'text' => $driver->mobile);
                $apiController->sendWhatsappMessage($row->passenger_id, 5, 'booking_details', $params, $short_url, 'en', 1);
            }
        }

        if (isset($request->mobiles)) {
            $link = Encryption::encode($ride_id);
            $url = 'https://app.svktrv.in/admin/ride/' . $link;

            $short_url = $this->random();
            $this->model->saveTable('short_url', ['short_url' => $short_url, 'long_url' => $url]);
            $url = 'https://app.svktrv.in/l/' . $short_url;

            foreach ($request->mobiles as $mobile) {
                $mobile = trim($mobile);
                if (strlen($mobile) == 10) {
                    $params = [];
                    $params[] = array('type' => 'text', 'text' => 'Passenger');
                    $params[] = array('type' => 'text', 'text' => $booking_id);
                    $params[] = array('type' => 'text', 'text' => $this->htmlDateTime($ride->start_time));
                    $params[] = array('type' => 'text', 'text' => $ride->start_location);
                    $params[] = array('type' => 'text', 'text' => $ride->end_location);
                    $params[] = array('type' => 'text', 'text' => $car_type);
                    $params[] = array('type' => 'text', 'text' => $driver->name);
                    $params[] = array('type' => 'text', 'text' => $driver->mobile);
                    $apiController->sendWhatsappMessage($mobile, 'mobile', 'booking_details', $params, $short_url, 'en', 1);
                }
            }


            $data['booking_id'] = $booking_id;
            $data['pickup_time'] = $this->htmlDateTime($ride->start_time);
            $data['pickup_address'] = $request->pickup_location;
            $data['driver_name'] = $driver->name;
            $data['driver_mobile'] = $driver->mobile;
            $data['vehicle_number'] = $vehicle->number;
            $data['vehicle_type'] =  $car_type;
            $data['passengers'] = $request->passengers;

            $ccEmails = explode(',', env('CC_EMAILS'));

            Mail::to($request->emails)->cc($ccEmails)->send(new BookingEmail('#' . $booking_id . ' Siddhivinayak Travels House Cab Booking Confirmed', $data));
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
