<?php

use App\Models\Setting;

function formatNumber($number)
{
    if ($number >= 1000 && $number < 1000000) {
        return number_format($number / 1000, 1) . 'k'; // Formats as 1k, 1.3k, etc.
    } elseif ($number >= 1000000) {
        return number_format($number / 1000000, 1) . 'M'; // For 1M, 1.3M, etc.
    } else {
        return $number; // Return number as is if less than 1000
    }
}

function numberToWords($number)
{
    $f = new \NumberFormatter('en', \NumberFormatter::SPELLOUT);
    return ucfirst($f->format($number));
}

function isshowqty()
{
   return Setting::first()->is_show_qty;
}
function isshowpurchase()
{
   return Setting::first()->is_show_purchase;
}
function isshowsale()
{
   return Setting::first()->is_show_sale;
}
function isshowstatus()
{
   return Setting::first()->is_show_status;
}
function isshowaction()
{
   return Setting::first()->is_show_action;
}
