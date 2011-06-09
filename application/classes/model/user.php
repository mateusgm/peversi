<?php defined('SYSPATH') or die('No direct script access.');

class Model_User extends ORM {

   public static function createX($name) {
      $user = new Model_User();
      $user->name = $name;
      $user->save(); 
      return $user;
   }
   
   public static function getLogged() {
      return ORM::factory('user')->find_all();
   }
}
