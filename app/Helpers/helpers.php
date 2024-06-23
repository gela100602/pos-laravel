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
        return number_format($value, 2, '.', ',');
    }
}
