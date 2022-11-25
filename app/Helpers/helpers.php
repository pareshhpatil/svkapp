<?php

use Carbon\Carbon;

if (!function_exists("check_maintenance_mode")) {
    /**
     *
     * @return bool
     */
    function check_maintenance_mode()
    {
        return config('app.maintenance_mode');
    }
}

if (!function_exists('get_date_in_human_diff')) {
    /**
     * @param $date
     *
     * @return string
     */
    function get_date_in_human_diff($date)
    {
        if (empty($date)) {
            return '';
        }

        $Date = Carbon::parse($date);

        return $Date->diffForHumans();
    }
}

if (!function_exists('is_true')) {
    function is_true($value)
    {
        return ($value === "1" || $value === 1 || $value === "true" || $value === true);
    }
}

if (!function_exists('is_false')) {
    function is_false($value)
    {
        return ($value === "0" || $value === 0 || $value === "false" || $value === false);
    }
}

if (!function_exists('to_boolean')) {
    function to_boolean($value)
    {
        return boolval($value);
    }
}

if (!function_exists('to_integer')) {
    function to_integer($value)
    {
        return intval($value);
    }
}

if (!function_exists('studly_case_space')) {
    function studly_case_space($value)
    {
        return ucwords(str_replace(['-', '_'], ' ', $value));
    }
}

if (!function_exists('to_uppercase')) {
    function to_uppercase($value)
    {
        return mb_strtoupper(trim($value));
    }
}

if (!function_exists('to_lowercase')) {
    function to_lowercase($value)
    {
        return mb_strtolower($value);
    }
}