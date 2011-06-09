<?php defined('SYSPATH') or die('No direct script access.');

class Model_Board extends Model {

   private $board;

   public static function create() {
      return serialize(Model_Board::init_board());
   }
   
   public function __construct ($serialized){
      $this->board = unserialize($serialized);
   }
   
   public function deflate() {
      return serialize($this->board);
   }
   
   public function toArray() {
      return $this->board;
   }
   
   public function count($stone) {
      $count = 0;
      foreach ($this->board as $row)
         foreach ($row as $st)
            if($st == $stone) $count++;
      return $count;
   }
   
   public function flips($move) {
      $dirs = Model_Board::dirs();
      $flips = array();
      foreach ($dirs as $dir) {
         $mvs = $this->direction($dir, $move);
         $aux = array();
         $sentinel = false;
         foreach ($mvs as $mv) {
            if ($mv->player == '-') {
               $sentinel = false;
               break;
            } elseif ($mv->player == $move->player) {
               if ($aux) $aux[$move->y . $move->x] = $move;
               $sentinel = true;
               break;
            } else {
               $mv->player = $move->player;
               $aux[$mv->y . $mv->x] = $mv;
            }
         }
         if ($sentinel)
            $flips = Model_Board::merge($flips, $aux);
      }
      return $flips;
   }
   
   public function flip($flips) {
      foreach ($flips as $flip)
         $this->board[$flip->y][$flip->x] = $flip->player;
   }
   
   public function prospects($stone) {
      $prospects = array();
      for($i = 1; $i <= 8; $i++) {
         for ($j = 1; $j <= 8; $j++) {
            if ($this->emptyX($j, $i)) {
               $move = new Model_Move($j, $i, $stone);
               $flips = $this->flips($move);
               if ($flips) $prospects[] = "${j}${i}";
            }
         }
      }
      return $prospects;
   }
   
   private function emptyX($y, $x) {
      return $this->board[$y][$x] == '-';
   }
   
   private function out($x, $y) {
      return $x < 1 || $x > 8 || $y < 1 || $y > 8;
   }
   
   private function valid($move) {
      $x = $move->x;
      $y = $move->y;
      return !$this->out($y, $x) && $this->emptyX($y, $x);
   }

   private function direction($dir, $move) {
      list ($di, $dj) = $dir;
      $i = $move->y + $di;
      $j = $move->x + $dj;
      $moves = array();
      while (!$this->out($i,$j)) {
         $moves[] = new Model_Move($i,$j, $this->board[$i][$j]);
         $i += $di;
         $j += $dj;
      }
      return $moves;
   }
   
   private static function merge($array1, $array2) {
      foreach ($array2 as $key => $item)
         $array1[$key] = $item;
      return $array1;
   }

   private static function dirs() {
      $dirs = array();
      $dirs[] = array(1,0);
      $dirs[] = array(1,1);
      $dirs[] = array(0,1);
      $dirs[] = array(-1,1);
      $dirs[] = array(-1,0);
      $dirs[] = array(-1,-1);
      $dirs[] = array(0,-1);
      $dirs[] = array(1,-1);
      return $dirs;
   }
   
   
   
   private static function init_board() {
      $board = array();
      for ($i = 1; $i <= 8; $i++) {
         $board[$i] = array();
         for ($j = 1; $j <= 8; $j++)
            $board[$i][$j] = '-';
      }
      $board[4][4] = $board[5][5] = 'o';
      $board[4][5] = $board[5][4] = 'x'; 
      return $board;
   }

}
