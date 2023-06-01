<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Lib\Encryption;
use App\Models\ParentModel;
use App\Http\Controllers\ApiController;

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
        $array['live_location'] = json_encode($request->all());
        $array['ride_id'] = $ride_id;
      //  if ($response == false) {
          //  $this->model->saveTable('ride_live_location', $array);
      //  }
        $this->model->saveTable('ride_location_track', $array);
    }

    public function rideLocation($ride_id)
    {
        $array = $this->model->getColumnValue('ride_live_location', 'ride_id', $ride_id, 'live_location');
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
        $ride_id = $request->ride_id;
        $array['status'] = 1;
        $this->model->updateArray('ride', 'id', $ride_id, $array);
        $apiController = new ApiController();
        $link = Encryption::encode($ride_id);
        $url = 'https://app.svktrv.in/driver/ride/' . $link;
        $apiController->sendNotification($array['driver_id'], 4, 'A new trip has been assigned', 'Please make sure to arrive at the pick-up location on time and provide a safe and comfortable ride to the passenger', $url);
        $passengers = $this->model->getTableList('ride_passenger', 'ride_id', $ride_id);
        foreach ($passengers as $row) {
            $link = Encryption::encode($row->id);
            $url = 'https://app.svktrv.in/passenger/ride/' . $link;
            $apiController->sendNotification($row->passenger_id, 5, 'Cab has been assigned for your next ride', 'Please be ready at your pickup location. Have a safe and pleasant journey.', $url);
        }

        return redirect('/my-rides/pending');
    }


    public function shortUrl($short)
    {
        $url = $this->model->getColumnValue('short_url', 'short_url', $short, 'long_url');
        if ($url != false) {
            return redirect($url, 301);
        }
    }
}
