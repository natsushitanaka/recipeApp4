<?php

class MypageController extends Controller

{
  // protected $auth_actions = array('review', 'registerMenu', 'myMenuList', 'others');
  // private $categories =array('No Category', '前菜', 'サラダ', 'メイン', 'ご飯・麺', 'おつまみ', 'ドリンク');

  public function indexAction()
  {
    if($this->session->isAuthenticated()){
      $user = $this->db_manager->get('Users')->fetchByUserName($_SESSION['user']['user_name']);
    }else{
      $user = null;
    }

    return $this->render(array(
      'user' => $user,
      '_token' => $this->generateCsrfToken('mypage/index'),
    ));
  }

  public function editAction()
  {
    $messages = array();

    $user = $_SESSION['user'];
    $user_name = $this->request->getPost('user_name');

    if(isset($user_name)){
      if(!strlen($user_name)){
        $messages[] = "ユーザー名を入力してください。";
      }elseif($user_name === $_SESSION['user']['user_name']){
        $messages[] = "現在のユーザー名と同じですよ？";
      }elseif(!$this->db_manager->get('Users')->isUniqueUserName($user_name)){
        $messages[] = 'このユーザー名は使用できません。';
      }

      if(count($messages) === 0){
        $this->db_manager->get('Users')->editName($_SESSION['user']['id'], $user_name);
        $user = $this->db_manager->get('Users')->fetchByUserName($user_name);
        $this->session->set('user', $user);
        $messages[] = 'ユーザー名を変更しました。';
      }
    }

    return $this->render(array(
      'user' => $user,
      'messages' => $messages,
      '_token' => $this->generateCsrfToken('mypage/edit'),
    ));
  }

  public function passwordAction()
  {
    $messages = array();

    $now = $this->request->getPost('now_password');
    $new = $this->request->getPost('new_password');
    $validate = $this->request->getPost('validate_password');
    
    if(isset($now) || isset($new) || isset($validate)){
      if(!strlen($now)){
        $messages[] = '現在のパスワードを入力してください。';
      }elseif(!$this->db_manager->get('Users')->isSamePassword($_SESSION['user']['password'], $now)){
        $messages[] = '現在のパスワードが正しくありません。';
      }
      if(!strlen($new)){
        $messages[] = '新しいパスワードを入力してください。';
      }
      if(!strlen($validate)){
        $messages[] = '新しいパスワード(確認)を入力してください。';
      }
      if(strlen($new) && strlen($validate)){
        if($new !== $validate){
          $messages[] = '新しいパスワードと新しいパスワード(確認)が一致しません。';
        }
        if(!preg_match('/\A(?=.*?[a-z])(?=.*?\d)(?=.*?[!-\/:-@[-`{-~])[!-~]{8,20}+\z/i', $new)){
          $messages[] = 'パスワードは半角英数字記号の組み合わせ８～２０文字以内で入力してください。';
        }
      }

      if(count($messages) === 0){
        $this->db_manager->get('Users')->editPassword($_SESSION['user']['id'], $new);
        $user = $this->db_manager->get('Users')->fetchByUserName($_SESSION['user']['user_name']);
        $this->session->set('user', $user);
        $messages[] = 'パスワードを変更しました。';
      }
    }
    
    return $this->render(array(
      'now_password' => $now,
      'new_password' => $new,
      'validate_password' => $validate,
      'messages' => $messages,
      '_token' => $this->generateCsrfToken('mypage/password'),
    ));
  }
}
