<?php

namespace App\Constants;

class SalesCommon
{
  const RECEIVING = '0';
  const NAME = '1';
  const CHARGE = '2';

  const SORT_ORDER = [
    'receiving' => self::RECEIVING,
    'name' => self::NAME,
    'charge' => self::CHARGE,
  ];

}