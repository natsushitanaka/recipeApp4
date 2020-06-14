<?php

class RecipeApp extends Application
{
  protected $login_action = array('', '');

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
      '/:controller/:action' => array(),
      '/:controller/:action/:id' => array(),
      // '/mypage/otherList/:user_name' => array('controller' => 'mypage', 'action' => 'otherList'),
      // '/mypage/:action/:id' => array('controller' => 'mypage'),
      // '/mypage/:action' => array('controller' => 'mypage'),
    );
  }

  protected function configure()
  {
    $this->db_manager->connect('master');
  }
}
