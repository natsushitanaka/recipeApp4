<?php

class MypageController extends Controller

{
  // protected $auth_actions = array('review', 'registerMenu', 'myMenuList', 'others');
  // private $categories =array('No Category', '前菜', 'サラダ', 'メイン', 'ご飯・麺', 'おつまみ', 'ドリンク');

  public function indexAction()
  {
    $user = $this->db_manager->get('Users')->fetchByUserName($_SESSION['user']['user_name']);

    return $this->render(array(
      'user' => $user,
      '_token' => $this->generateCsrfToken('mypage/index'),
    ));
  }
}
