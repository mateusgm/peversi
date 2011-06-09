<?php defined('SYSPATH') or die('No direct script access.');

class Model_Move extends Model {

   public $x, $y, $player;
   
   public function __construct ($y, $x, $player) {
      $this->x = $x;
      $this->y = $y;
      $this->player = $player;
   }

}
