<?php

namespace App\services;

class SlugService
{

   public function addSlug($string)
   {

      // replace non letter or digits by -
      $string = preg_replace('~[^\pL\d]+~u', '-', $string);

      // transliterate
      $string = iconv('utf-8', 'us-ascii//TRANSLIT', $string);

      // remove unwanted characters
      $string = preg_replace('~[^-\w]+~', '', $string);

      // trim
      $string = trim($string, '-');

      // remove duplicate -
      $string = preg_replace('~-+~', '-', $string);

      // lowercase
      $string = strtolower($string);

      return $string;
   }
}
