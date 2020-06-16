<?php

class MypageController extends Controller

{
  // protected $auth_actions = array('review', 'registerMenu', 'myMenuList', 'others');

  private $categories =array('No Category', '前菜', 'サラダ', 'メイン', 'ご飯・麺', 'おつまみ', 'ドリンク');
  private $sorts =array('作成日（新しい順）', '作成日（古い順）', '更新日（新しい順）', '更新日（古い順）', 'お気に入り（多い順）', 'お気に入り（少ない順）');

  public function pageNation($records = array(), $max_record_by_page)
  {
      $total_rec = count($records); 
      $range = 2;

      if($max_record_by_page > $total_rec){
          $max_page = 1;
      }else{
          $max_page = ceil($total_rec/$max_record_by_page);
      }

      if(isset($_GET['page']) && $_GET['page'] > 0 && $_GET['page'] <= $max_page){
        $current_page = (int)$_GET['page'];
      }else{
          $current_page = 1;
      }

      $prevDiff = 0;
      if ($current_page - $range < 1) {
        $prevDiff = $range - $current_page + 1;
      }
      
      $nextDiff = 0;
      if ($current_page + $range > $max_page) {
        $nextDiff = $current_page + $range - $max_page;
      }

      $start_no = ($current_page - 1) * $max_record_by_page;

      $records = array_slice($records, $start_no, $max_record_by_page, true);

      return array(
          'max_page' => $max_page,
          'records' => $records,
          'current_page' => $current_page,
          'range' => $range,
          'prevDiff' => $prevDiff,
          'nextDiff' => $nextDiff,
      );
  }

  public function indexAction()
  {
    $user = array();
    $menus = array();
    $favorites = array();
    $categories = array();
    $sort_array = array();

    $freeword = $this->request->getPost('freeword');
    $category_selected = $this->request->getPost('category');
    $sort_selected = $this->request->getPost('sort');
    $openRange = $this->request->getPost('openRange');

    if($this->session->isAuthenticated()){
      $user = $this->db_manager->get('Users')->fetchByUserName($_SESSION['user']['user_name']);
      $getMine = $this->db_manager->get('Menus')->getMine($_SESSION['user']['id']);
      $favorites = $this->db_manager->get('Menus')->getFavorites($_SESSION['user']['id']);
    }else{
      $user = null;
    }

    if(isset($_POST['find'])){
      $getMine = $this->db_manager->get('Menus')->findMine($freeword, $category_selected, $openRange);
    }

    foreach($this->categories as $category){
      $categories += $this->db_manager->get('Menus')->countCategory($category);
    }

    foreach($getMine as $menu){
        $countFavorite = $this->db_manager->get('Favorites')->countFavorite($menu['id']);
        $menu += $countFavorite;
        $menus[] = $menu;
    }

    $created_at = array_column($menus, 'created_at');
    $updated_at = array_column($menus, 'updated_at');
    $favorite = array_column($menus, 'favorite');

    if(isset($_POST['sort'])){

      switch ($sort_selected) {
          case '作成日（新しい順）':
              $sort_array = $created_at;
              $option = SORT_DESC;
              break;
          case '作成日（古い順）':
              $sort_array = $created_at;
              $option = SORT_ASC;
              break;
          case '更新日（新しい順）':
              $sort_array = $updated_at;
              $option = SORT_DESC;
              break;
          case '更新日（古い順）':
              $sort_array = $updated_at;
              $option = SORT_ASC;
              break;
          case 'お気に入り（多い順）':
              $sort_array = $favorite;
              $option = SORT_DESC;
              break;
          case 'お気に入り（少ない順）':
              $sort_array = $favorite;
              $option = SORT_ASC;
              break;
      }    

      array_multisort($sort_array, $option, $menus);
    }

    $total_menu = count($menus);

    $pageNation = $this->pageNation($menus, 10);

    return $this->render(array(
      'user' => $user,
      'menus' => $pageNation['records'],
      'pageNation' => $pageNation,
      'categories' => $categories,
      'sorts' => $this->sorts,
      'freeword' => $freeword,
      'category_selected' => $category_selected,
      'sort_selected' => $sort_selected,
      'favorites' => $favorites,
      'total_menu' => $total_menu,
      'openRange' => $openRange,
      '_token' => $this->generateCsrfToken('mypage/index'),
    ));
  }

  public function favoriteAction()
  {
    $favorites = array();

    $favorites = $this->db_manager->get('Menus')->getFavorites($_SESSION['user']['id']);

    return $this->render(array(
      'favorites' => $favorites,
      '_token' => $this->generateCsrfToken('mypage/favorite'),
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
