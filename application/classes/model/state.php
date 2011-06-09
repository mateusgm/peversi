<?php defined('SYSPATH') or die('No direct script access.');

class Model_State extends ORM {

   public $white = 2,
         $black = 2,
         $available = array(),
         $boardX = null;
 
   public function inflate() {
      if ($this->boardX) $this->board = $this->boardX;
      else $this->board = new Model_Board($this->board);
   }
   
   public function deflate() {
      $this->boardX = $this->board;
      $this->board = $this->board->deflate();
   }
   
   public function prepare() {
      $this->available = $this->board->prospects($this->turn);
      $this->white = $this->board->count('x');
      $this->black = $this->board->count('o');
      $this->board = $this->board->toArray();
   }
   
   public function play($move) {
      if ($move->player != $this->turn) return false;
      $flips = $this->board->flips($move);
      if (!$flips) return false;
      $this->board->flip($flips);
      $this->setMeta();
      $this->deflate();
      $this->save();
      $this->inflate();
      return true;
   }
   
   public function get_ai_move($stone) {
      $ps = current($this->board->prospects($stone));
      return new Model_Move($ps[0], $ps[1], $stone);
   }
   
   private function isBlocked($stone) {
      return $this->board->prospects($stone) ? false : true;
   }
   
   private function setMeta() {
      if ($this->isBlocked($this->idle)) {
         if ($this->isBlocked($this->turn))
            $this->finish();
      } else {
         $aux = $this->turn;
         $this->turn = $this->idle;
         $this->idle = $aux;
      }
   }
   
   private function finish() {
      $tcount = $this->board->count($this->turn);
      $icount = $this->board->count($this->idle); 
      if ($tcount > $icount) {
         $status = 'over';
         $winner = $this->turn;
         $loser = $this->idle;
      } elseif ($tcount < $icount) {
         $status = 'over';
         $winner = $this->idle;
         $loser = $this->turn;
      } else {
         $status = 'draw';
         $winner = $this->turn;
         $loser = $this->idle;
      }
      $this->status = $status;
      $this->turn = $winner;
      $this->idle = $loser;
   }
   
   public static function createX() {
      $state = new Model_State();
      $state->board = Model_Board::create();
      $state->turn = 'o';
      $state->idle = 'x';
      $state->status = 'play';
      $state->save(); 
      return $state->id;
   }

}
