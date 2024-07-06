<?php

if (!function_exists('format_currency')) {
    /**
     * Format currency value.
     *
     * @param float $value
     * @return string
     */
    function format_currency($value)
    {
        return number_format($value, 0, '.', ',');
    }
}

if (!function_exists('format_date')) {
    /**
     * Format a date value.
     *
     * @param string $date
     * @param string $format
     * @return string
     */
    function format_date($date, $format = 'Y-m-d H:i:s')
    {
        return \Carbon\Carbon::parse($date)->format($format);
    }
}