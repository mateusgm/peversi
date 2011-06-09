<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Game extends Controller {

   public function action_index()
   {
#      $uid = Cookie::get('user');
      $uid = Controller_Game::getCookie('user');
      $view = View::factory('game/index');
      $view->user = new Model_User($uid);
      $view->logged = Model_User::getLogged();
      $this->response->body($view);
   }
   
   public function action_create()
   {
#      $uid = Cookie::get('user');
      $uid = Controller_Game::getCookie('user');
      $uop = Controller_Game::getInput('opponent');
#      $uop = $this->request->post('opponent');
      $game = Model_Game::createX($uid, $uop);
#      Cookie::set('game', $game->id);
      Controller_Game::setCookie('game', $game->id);
   }

   public function action_play()
   {
#      $uid = Cookie::get('user');
#      $gid = Cookie::get('game');
      $uid = Controller_Game::getCookie('user');
      $gid = Controller_Game::getCookie('game');
      $view = View::factory('game/play');
      $view->user = new Model_User($uid);
      $view->game = new Model_Game($gid);
      $view->game->prepare();
      $this->response->body($view);
   }

   public function action_get()
   {
#      $gid = Cookie::get('game');
      $gid = Controller_Game::getCookie('game');
      $view = View::factory('game/update');
      $view->game = new Model_Game($gid);
      $view->game->prepare();
      $this->response->body($view);
   }

   public function action_update()
   {
#      $gid = Cookie::get('game');
      $gid = Controller_Game::getCookie('game');
      $x = $this->request->post('x');
      $y = $this->request->post('y');
      $player = $this->request->post('player');
      $move = new Model_Move($y, $x, $player);
      $uid = $this->request->post('user');

      $view = View::factory('game/update');
      $view->game = new Model_Game($gid);
      $results = $view->game->play($uid, $move);
      if ($results) $this->response->body($view);
      else $this->response->status(404);
   }

   public function action_check()
   {
#      $uid = Cookie::get('user');
      $uid = Controller_Game::getCookie('user'); 
      $game = Model_Game::isPlaying($uid);
#      if ($game->loaded()) Cookie::set('game', $game->id);
      if ($game->loaded()) setCookie('game', $game->id);
      else $this->response->status(404);
   }
   
   public static function getCookie($name) {
      return trim($_COOKIE[$name], '"');
   }
   public static function setCookie($name, $value) {
      setCookie($name, '"' . $value . '"');
   }
   public static function getInput($name) {
      $value = Request::current()->post($name);
      if (!$value) $value = $_GET[$name];
      echo "# " . $value . " #";
      return $value;
   }

}
