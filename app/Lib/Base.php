<?php
/**
 * Created by PhpStorm.
 * User: tieungao
 * Date: 2019-09-03
 * Time: 17:05
 */

namespace App\Lib;


class Base
{
   public static function getStatus()
   {
       return [
           0 => 'Mới tạo',
           1 => 'Đang xử lý',
           2 => 'Đã xử lý',
       ];
   }
}