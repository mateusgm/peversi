<?php defined('SYSPATH') or die('No direct script access.');

class Model_Game extends ORM {

   public function prepare() {
      $this->inflate();
      $this->state->prepare();
   }

   private function inflate(){
      $this->state = new Model_State($this->state);
      $this->state->inflate();
   }
   
   public function play($uid, $move) {
      if ($uid != $this->turn) return false;
      $this->inflate();
      if ($uid == 1)
         $move = $this->state->get_ai_move($move->player);
      $turn = $this->state->turn;
      $results = $this->state->play($move);
      if (!$results) return false;
      $this->setTurn($turn);
      $this->save();
      $this->state->prepare();
      return true;
   }
   
   private function setTurn ($turn) {
      if ($turn != $this->state->turn) {
         $aux = $this->turn;
         $this->turn = $this->idle;
         $this->idle = $aux;
      }
   }
   
   public static function createX($turn, $idle) {
      $game = new Model_Game();
      $game->turn = $turn;
      $game->idle = $idle;
      $game->state = Model_State::createX();
      $game->save(); 
      return $game;
   }

   public static function isPlaying($uid) {
      return ORM::factory('game')
         ->join('states')
         ->on('states.id', '=', 'games.state')
         ->where('status', '=', 'play')
         ->and_where_open()
         ->where('games.turn', '=', $uid)
         ->or_where('games.idle', '=', $uid)
         ->and_where_close()
         ->order_by('games.id', 'DESC')
         ->find();
   }

}
