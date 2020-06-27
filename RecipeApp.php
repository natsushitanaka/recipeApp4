<?php

class RecipeApp extends Application
{
  protected $login_action = array('account', 'notLoggedIn');

  public function getRootDir()
  {
    return dirname(__FILE__);
  }

  protected function registerRoutes()
  {
    return array(
      '/' => array('controller' => 'mypage', 'action' => 'index'),
      '/account' => array('controller' => 'account', 'action' => 'signin'),
      '/mypage' => array('controller' => 'mypage', 'action' => 'index'),
      '/recipe' => array('controller' => 'recipe', 'action' => 'recipes'),
      '/favorites' => array('controller' => 'mypage', 'action' => 'favorite'),
      '/recipe/user/:id' => array('controller' => 'recipe', 'action' => 'others'),
      '/recipe/new' => array('controller' => 'recipe', 'action' => 'new'),
      '/recipe/:id' => array('controller' => 'recipe', 'action' => 'detail'),
      '/:controller/:action' => array(),
      '/:controller/:action/:id' => array(),
    );
  }

  protected function configure()
  {
    $this->db_manager->connect('master');
  }
}
