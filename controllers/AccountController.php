<?php

class AccountController extends Controller
{
  // protected $auth_actions = array('signout');

  public function signupAction()
  {
    if($this->session->isAuthenticated()){
      return $this->redirect('/');
    }

    return $this->render(array(
      'user_name' => '',
      'password' => '',
      '_token' => $this->generateCsrfToken('account/signup'),
    ));
  }

  public function registerAction()
  {
    if($this->session->isAuthenticated()){
      return $this->redirect('/');
    }

    if(!$this->request->isPost()){
      $this->forward404();
    }

    // $token = $this->request->getPost('_token');
    // if(!$this->checkCsrfToken('account/signup', $token)){
    //   return $this->redirect('/account/signup');
    // }

    $user_name = $this->request->getPost('user_name');
    $password = $this->request->getPost('password');

    $messages = array();

    if(!strlen($user_name)){
      $messages[] = "ユーザー名を入力してください。";
    }elseif(!$this->db_manager->get('Users')->isUniqueUserName($user_name)){
      $messages[] = 'このユーザー名は使用できません。';
    }

    if(!strlen($password)){
      $messages[] = 'パスワードを入力してください。';
    }elseif(!preg_match('/\A(?=.*?[a-z])(?=.*?\d)(?=.*?[!-\/:-@[-`{-~])[!-~]{8,20}+\z/i', $password)){
      $messages[] = 'パスワードは半角英数字記号の組み合わせ８～２０文字以内で入力してください。';
    }

    if(count($messages) === 0){
      $this->db_manager->get('Users')->insert($user_name, $password);
      $this->session->setAuthenticated(true);
      $user = $this->db_manager->get('Users')->fetchByUserName($user_name);
      $this->session->set('user', $user);

      return $this->redirect('/');
    }

    return $this->render(array(
      'user_name' => $user_name,
      'password' => $password,
      'messages' => $messages,
      '_token' => $this->generateCsrfToken('account/signup'),
    ), 'signup');
  }

  public function authenticateAction()
  {
    if($this->session->isAuthenticated()){
      return $this->redirect('/');
    }

    if(!$this->request->isPost()){
      $this->forward404();
    }

    // $token = $this->request->getPost('_token');
    // if(!$this->checkCsrfToken('account/signin', $token)){
    //   return $this->redirect('/account/signin');
    // }

    $user_name = $this->request->getPost('user_name');
    $password = $this->request->getPost('password');

    $messages = array();

    if(!strlen($user_name)){
      $messages[] = "ユーザー名を入力してください。";
    }

    if(!strlen($password)){
      $messages[] = 'パスワードを入力してください。';
    }

    if(count($messages) === 0){

       $user_repository = $this->db_manager->get('Users');
       $user = $user_repository->fetchByUserName($user_name);

       if(!$user ||
          ($user['password'] !== $user_repository->hashPassword($password))
        ){
          $messages[] = 'ユーザー名またはパスワードが正しくありません。';
        }else{
          $this->session->setAuthenticated(true);
          $this->session->set('user', $user);

          return $this->redirect('/');
        }
    }

    return $this->render(array(
      'user_name' => $user_name,
      'password' => $password,
      'messages' => $messages,
      '_token' => $this->generateCsrfToken('account/signin'),
    ), 'signin');
  }

  public function signinAction()
  {
    if($this->session->isAuthenticated()){
      return $this->redirect('/');
    }

    return $this->render(array(
      'user_name' => '',
      'password' => '',
      '_token' => $this->generateCsrfToken('account/signin'),
    ));
  }

  public function signoutAction()
  {
    $this->session->clear();
    $this->session->setAuthenticated(false);

    return $this->redirect('/account');
  }

  public function unsubscribeAction()
  {
    $this->db_manager->get('Users')->unsubscribe($_SESSION['user']['id']);

    $this->session->clear();
    $this->session->setAuthenticated(false);

    return $this->redirect('/account');
  }

  // public function NotLogInAction()
  // {
  //   return $this->render();
  // }
  //
  // public function explanationAction()
  // {
  //   return $this->render();
  // }
}
