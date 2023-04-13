<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session;
use App\Model\User;

class Localize extends Component
{
    public $date;
    public $type;
    public $userid = null;
    private $user_model = null;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($date, $type, $userid='')
    {
        $this->date = $date;
        $this->type = $type;
        $this->userid= ($userid!='') ? $userid : null;
        $this->user_model = new User();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        
        $default_timezone="America/Cancun";
        $default_date_format="M d yyyy";
        $default_time_format="24";

        if($this->userid!=null) {
            $preference = $this->user_model->getPreferences($this->userid);
            if($preference!='') {
                $default_timezone=$preference->timezone;
                $default_date_format=$preference->date_format;
                $default_time_format=$preference->time_format;
            }
        }
       
        if (Session::has('default_date_format')) {
            $default_date_format =  Session::get('default_date_format');
        }
        $default_date_format =  str_replace('yyyy', 'Y', $default_date_format);

        if (Session::has('default_timezone')) {
            $default_timezone =  Session::get('default_timezone');
        }
        
        if ($this->type == 'date') {
            $this->date = Carbon::parse($this->date)->format($default_date_format);
            $this->date = Carbon::createFromFormat($default_date_format, $this->date, 'UTC');
            $this->date = $this->date->setTimezone($default_timezone)->format($default_date_format);
        } else if ($this->type == 'datetime') {
            if (Session::has('default_time_format')) {
                $default_time_format =  Session::get('default_time_format');
            }

            if ($default_time_format == '24') {
                $time_format = 'G:i';
            } else {
                $time_format = 'g:i A';
            }

            $time = strtotime($this->date);
            $this->date = Carbon::parse($this->date)->format($default_date_format . ' H:i:s');
            $this->date = Carbon::createFromFormat($default_date_format . ' H:i:s',  $this->date, 'UTC');
            $time_formatted = $this->date->setTimezone($default_timezone)->format($time_format);
            $this->date = $this->date->setTimezone($default_timezone);

            $weekDays = ['Mon', 'Tue', 'Wed', 'Thur', 'Fri', 'Sat', 'Sun'];
            $months = ['Jan', 'Feb', 'Mar', 'Apr', ' May', 'Jun', 'Jul', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec'];

            if ($time > strtotime('-2 minutes')) {
                $this->date =  'Just now';
            } elseif ($time > strtotime('-59 minutes')) {
                $min_diff = floor((strtotime('now') - $time) / 60);
                $this->date =  $min_diff . ' min' . (($min_diff != 1) ? "s" : "") . ' ago';
            } elseif ($time > strtotime('-23 hours')) {
                $hour_diff = floor((strtotime('now') - $time) / (60 * 60));
                $this->date =  $hour_diff . ' hour' . (($hour_diff != 1) ? "s" : "") . ' ago';
            } elseif ($time > strtotime('today')) {
                $this->date =  $time_formatted;
            } elseif ($time > strtotime('yesterday')) {
                $this->date =  'Yesterday at ' . $time_formatted;
            } elseif ($time > strtotime('this week')) {
                $this->date =  $weekDays[$this->date->format('N') - 1] . ' at ' . $time_formatted;
            } else {
                if ($default_date_format == 'M d Y') {
                    $this->date = $months[$this->date->format('n') - 1] . ' ' . $this->date->format('j')  .
                        (($this->date->format('Y') != date("Y")) ? $this->date->format(' Y') : "") .
                        ' at ' . $time_formatted;
                } else {
                    $this->date = $this->date->format('j') . ' ' . $months[$this->date->format('n') - 1] .
                        (($this->date->format('Y') != date("Y")) ? $this->date->format(' Y') : "") .
                        ' at ' . $time_formatted;
                }
            }
        } else if($this->type == 'onlydate') {
            $this->date = Carbon::parse($this->date)->format($default_date_format);
        }
        return view('components.localize');
    }
}
