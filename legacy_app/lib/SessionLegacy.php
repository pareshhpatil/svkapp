<?php

use Illuminate\Support\Facades\Session;

class SessionLegacy
{

    public function set($key, $value)
    {
        if ($key == 'userid' || $key == 'merchant_id' || $key == 'system_user_id') {
            $converter = new Encryption;
            $value = $converter->encode($value);
        }
        Session::put($key, $value);
        Session::save();
    }

    public function setFlash($key, $value)
    {
        Session::flash($key, $value);
        Session::save();
    }

    public function remove($key)
    {
        Session::forget($key);
        Session::save();
    }

    public function removeCookie($key)
    {
        if (isset($_COOKIE[$key])) {
            unset($_COOKIE[$key]);
            setcookie($key, '', time() - 3600, '/');
        }
    }
    public function has($key)
    {
        if (Session::has($key)) {
            return true;
        } else {
            return false;
        }
    }

    public function get($key, $original = false)
    {
        //dd(Session::all());
        if (Session::has($key)) {
            $value = Session::get($key);
            if ($original == true) {
                return $value;
            }
            if ($key == 'userid' || $key == 'merchant_id' || $key == 'system_user_id') {
                $converter = new Encryption;
                return $converter->decode($value);
            } else {
                return $value;
            }
            return $value;
        }
    }

    public function destroy()
    {
        Session::flush();
    }

    public function getCookie($key)
    {
        if (isset($_COOKIE[$key]))
            return $_COOKIE[$key];
    }

    public function getArrayCookie($key)
    {
        if (isset($_COOKIE[$key]))
            return json_decode($_COOKIE[$key], 1);
    }

    public function setCookie($key, $value, $days = 30)
    {
        setcookie($key, $value, time() + (864000 * $days), "/"); // 86400 = 1 day
    }

    public function setArrayCookie($key, $value, $days = 30)
    {
        setcookie($key, json_encode($value), time() + (864000 * $days), "/"); // 86400 = 1 day
    }

    public function destroyuser()
    {
        $this->remove('userid');
        $this->remove('logged_in');
        $this->remove('email_id');
        $this->remove('display_name');
        $this->remove('user_status');
        $this->remove('user_name');
    }
}
