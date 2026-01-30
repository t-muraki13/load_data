<?php

namespace App\Constants;

class Common
{
  const RECEIVING = '0';
  const NAME = '1';
  const CHARGE = '2';
  const ISSUE = '3';
  const PLACE = '4';

  const SORT_ORDER = [
    'receiving' => self::RECEIVING,
    'name' => self::NAME,
    'charge' => self::CHARGE,
    'issue' => self::ISSUE,
    'place' => self::PLACE
  ];

}