<?php

class MypageController extends Controller

{
  protected $auth_actions = ['edit', 'password', 'favorite'];

  private $categories =['No Category', '前菜', 'サラダ', 'メイン', 'ご飯・麺', 'おつまみ', 'ドリンク'];
  public $sorts =['作成日（古い順）', '更新日（新しい順）', '更新日（古い順）', 'お気に入り（多い順）', 'お気に入り（少ない順）', '作成日（新しい順）'];
  private $messages = [];


  public function indexAction()
  {
    if(!$this->session->isAuthenticated()){
      return $this->redirect('/recipe');
    }

    $path = '';

    $this->initSearchForm();

    if(isset($_GET['_token'])){
      $this->isSafeCsrf($path, $_GET['_token']);
  }

    $categories = $this->createCategoryCounted();   
    
    if($this->session->isAuthenticated()){
      $user = $_SESSION['user'];
      $menus = $this->db_manager->get('Menus')->findMine($_SESSION['user']['id'], $_GET['freeword'], $_GET['category'], $_GET['is_displayed']);
      $menus = $this->createMenusIncludeCountFavorite($menus);
    }

    $create_sort_array = $this->createSortArray($menus, $_GET['sort'], 'created_at', 'updated_at', 'favorite');

    array_multisort($create_sort_array['sort_array'], $create_sort_array['option'], $menus);

    $total_menu = count($menus);
    $page_nation = $this->pageNation($menus, 10);


    return $this->render([
      'menus' => $page_nation['records'],
      'user' => $user,
      'page_nation' => $page_nation,
      'categories' => $categories,
      'sorts' => $this->sorts,
      'total_menu' => $total_menu,
      'path' => $path,
      '_token' => $this->generateCsrfToken($path),
    ]);
  }




  public function editAction()
  {
    $path = 'mypage/edit';

    $user = $_SESSION['user'];
    $user_name = $this->request->getPost('user_name');

    if(isset($user_name)){
      $this->isSafeCsrf($path, $_POST['_token']);

      if(!strlen($user_name)){
        $this->messages[] = "ユーザー名を入力してください。";
      }elseif($user_name === $user['user_name']){
        $this->messages[] = "現在のユーザー名と同じですよ？";
      }elseif(!$this->db_manager->get('Users')->isUniqueUserName($user_name)){
        $this->messages[] = 'このユーザー名は使用できません。';
      }

      if(count($this->messages) === 0){
        $this->db_manager->get('Users')->editName($user['id'], $user_name);
        $user = $this->db_manager->get('Users')->fetchByUserName($user_name);
        $this->messages[] = 'ユーザー名を変更しました。';
      }
    }

    if(isset($_POST['icon'])){
      $this->isSafeCsrf($path, $_POST['_token']);

      if(empty($_FILES['icon']['name'])){
        $this->messages[] = '画像ファイル(png,gif,jpg,jpeg)を選択してください。';
      }else{
        $icon_data = file_get_contents($_FILES['icon']['tmp_name']);
        $icon_ext = pathinfo($_FILES["icon"]["name"], PATHINFO_EXTENSION);
  
        if($icon_ext !== 'png' && $icon_ext !== 'gif' && $icon_ext !== 'jpg' && $icon_ext !== 'jpeg' ){
          $this->messages[] = '画像ファイル(png,gif,jpg,jpeg)を選択してください。';
        }
  
        if(count($this->messages) === 0){
          $this->db_manager->get('Users')->editIcon($user['id'], $icon_data, $icon_ext);
          $user = $this->db_manager->get('Users')->fetchByUserName($user['user_name']);
        }
      }
    }

    $this->session->set('user', $user);

    return $this->render([
      'user' => $user,
      'messages' => $this->messages,
      '_token' => $this->generateCsrfToken($path),
    ]);
  }

  public function passwordAction()
  {
    $path = 'mypage/password';

    $now_password = $this->request->getPost('now_password');
    $new_password = $this->request->getPost('new_password');
    $validate_password = $this->request->getPost('validate_password');
    
    if(isset($now_password) || isset($new_password) || isset($validate_password)){
      $this->isSafeCsrf($path);

      if(!strlen($now_password)){
        $this->messages[] = '現在のパスワードを入力してください。';
      }elseif(!$this->db_manager->get('Users')->validatePassword($now_password, $_SESSION['user']['password'])){
        $this->messages[] = '現在のパスワードが正しくありません。';
      }
      if(!strlen($new_password)){
        $this->messages[] = '新しいパスワードを入力してください。';
      }
      if(!strlen($validate_password)){
        $this->messages[] = '新しいパスワード(確認)を入力してください。';
      }
      if(strlen($new_password) && strlen($validate_password)){
        if($new_password !== $validate_password){
          $this->messages[] = '新しいパスワードと新しいパスワード(確認)が一致しません。';
        }
        if(!preg_match('/\A(?=.*?[a-z])(?=.*?\d)(?=.*?[!-\/:-@[-`{-~])[!-~]{8,20}+\z/i', $new_password)){
          $this->messages[] = 'パスワードは半角英数字記号の組み合わせ８～２０文字以内で入力してください。';
        }
      }

      if(count($this->messages) === 0){
        $this->db_manager->get('Users')->editPassword($_SESSION['user']['id'], $new_password);
        $user = $this->db_manager->get('Users')->fetchByUserName($_SESSION['user']['user_name']);
        $this->session->set('user', $user);
        $this->messages[] = 'パスワードを変更しました。';
      }
    }
    
    return $this->render([
      'password' => [
        'now' => $now_password,
        'new' => $new_password,
        'validate' => $validate_password,
      ],
      'messages' => $this->messages,
      '_token' => $this->generateCsrfToken($path),
    ]);
  }



  public function favoriteAction()
  {
    $path = 'favorites';

    $menus = $this->db_manager->get('Menus')->getFavorites($_SESSION['user']['id']);
    $menus = $this->createMenusIncludeCountFavorite($menus);
    $page_nation = $this->pageNation($menus, 10);

    return $this->render([
      'menus' => $menus,
      'page_nation' => $page_nation,
      'path' => $path,
      '_token' => $this->generateCsrfToken($path),
    ]);
  }

// -----------------------functions-----------------------

  public function pageNation($records = [], $max_record_by_page)
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

      $prev_diff = 0;
      if ($current_page - $range < 1) {
        $prev_diff = $range - $current_page + 1;
      }
      
      $next_diff = 0;
      if ($current_page + $range > $max_page) {
        $next_diff = $current_page + $range - $max_page;
      }

      $start_no = ($current_page - 1) * $max_record_by_page;

      $records = array_slice($records, $start_no, $max_record_by_page, true);

      return [
          'max_page' => $max_page,
          'records' => $records,
          'current_page' => $current_page,
          'range' => $range,
          'prev_diff' => $prev_diff,
          'next_diff' => $next_diff,
      ];
  }



  public function checkImg()
  {
      $this->img = null;
      
      $tempfile = $_FILES['img']['tmp_name'];
      $img = uniqid();
      $img .= '.' . substr(strrchr($_FILES['img']['name'], '.'), 1);
      $file = "imgs/".$img;

      if (is_uploaded_file($tempfile)) {
          move_uploaded_file($tempfile , 'imgs/'.$img);

          if (!exif_imagetype($file)){
              return $this->messages[] = "選択したファイルは画像ではありません。";
          }else{
              $this->img = $img;
          }

      }else{
          return $this->messages[] = "画像をアップロード出来ませんでした。";
      }    
  }


  public function createSortArray($menus, $sort_selected, $created_at, $updated_at, $favorite)
  {
      $created_at = array_column($menus, $created_at);
      $updated_at = array_column($menus, $updated_at);
      $favorite = array_column($menus, $favorite);

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

      return [
          'sort_array' => $sort_array,
          'option' => $option,
      ];

  }


  public function isSafeCsrf($path, $token)
  {  
    if(!$this->checkCsrfToken($path, $token)){
      return $this->redirect('/'.$path);
    }
  }



  public function initSearchForm()
  {
      if(!isset($_GET['freeword'])){
          $_GET['freeword'] = '';
      }    
      if(!isset($_GET['category'])){
          $_GET['category'] = '';
      }    
      if(!isset($_GET['sort'])){
          $_GET['sort'] = '作成日（新しい順）';
      }    
      if(!isset($_GET['is_displayed'])){
          $_GET['is_displayed'] = '1';
      }        
  }




  public function createCategoryCounted()
  {
      $categories = [];

      foreach($this->categories as $category){
          $categories += $this->db_manager->get('Menus')->countCategory($category);
      }

      return $categories;
  }


  public function createMenusIncludeCountFavorite($menus)
  {
      $MenusIncludeCountFavorite = [];
      foreach($menus as $menu){
          $count_favorite = $this->db_manager->get('Favorites')->countFavorite($menu['id']);
          $menu += $count_favorite;
          $MenusIncludeCountFavorite[] = $menu;
      }

      return $MenusIncludeCountFavorite;
  }

}