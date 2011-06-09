<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Home extends Controller {

	public function action_index()
	{
		$this->response->body(View::factory('home/index'));
	}

	public function action_start() 
   {
#      $name = $this->request->post('name');
      $name = Controller_Game::getInput('name');
      $user = Model_User::createX($name);
      Controller_Game::setCookie('user', $user->id);
#      Cookie::set('user', $user->id);
   }

}
