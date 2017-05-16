<?php

/*
 * Logic Functions
 * All Site function must be put in here for easy control
 */
namespace App\Crawler;

use App\Account;

class Functions
{
     public static function traceCrawler($productName)
     {


     }

     public static function removeSpecialChar($string)
     {
         $string = str_replace("\n", '', $string);
         $string =str_replace("\r", '', $string);
         $string =str_replace("\r\n", '', $string);
         $string =str_replace("\t", '', $string);
         $string = trim($string);
         return $string;

     }

}