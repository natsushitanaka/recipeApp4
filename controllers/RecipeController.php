<?php

class RecipeController extends Controller
{
    protected $auth_actions = ['new', 'delete', 'edit'];

    private $categories =['No Category', '前菜', 'サラダ', 'メイン', 'ご飯・麺', 'おつまみ', 'ドリンク'];
    public $sorts =['作成日（古い順）', '更新日（新しい順）', '更新日（古い順）', 'お気に入り（多い順）', 'お気に入り（少ない順）', '作成日（新しい順）'];
    private $messages = [];
    private $img;


    public function newAction()
    {
        $path = 'recipe/new';

        $menu = $this->getMenuForm();

        if(isset($_POST['submit'])){
            $this->isSafeCsrf($path);
            
            if(!strlen($menu['title'])){
                $this->messages[] = "タイトルは必須項目です。";
            }
            if(!empty($_FILES['img']['name'])){
                $this->checkImg();
            }

            if(count($this->messages) === 0){
                $this->db_manager->get('Menus')->insert
                ($_SESSION['user']['id'], $menu['title'], (int)$menu['cost'], $menu['category'], $menu['body'], $menu['is_displayed'], $this->img);
                $this->messages[] = "新メニューが「{$menu['title']}」登録されました。";
            }    
        }

        return $this->render([
            'menu' => $menu,
            'categories' => $this->categories,
            'messages' => $this->messages,
            '_token' => $this->generateCsrfToken($path),
        ]);      
    }



    public function detailAction($params)
    {
        $path = 'recipe/detail/'.$params['id'];

        $menu = $this->db_manager->get('Menus')->getDetail($params['id']);
        $count_favorite = $this->db_manager->get('Favorites')->countFavorite($menu['id']);
        $comments = $this->db_manager->get('Comments')->get($menu['id']);
        
        if($this->session->isAuthenticated()){
            $favorite = $this->db_manager->get('Favorites')->isFavorite($_SESSION['user']['id'], $params['id']);
        }else{
            $favorite = '';
        }

        return $this->render([
            'menu' => $menu,
            'count_favorite' => $count_favorite['favorite'],
            'comments' => $comments,
            'favorite' => $favorite,
            '_token' => $this->generateCsrfToken($path),
        ]);      
    }



    public function deleteAction($params)
    {
        if(!$this->request->isPost()){
            $this->forward404();
        }

        $this->isSafeCsrf('/');

        $this->db_manager->get('Menus')->delete($params['id']);

        return $this->redirect($_SERVER['HTTP_REFERER']);
    }




    public function editAction($params)
    {
        $path = 'recipe/edit'.$params['id'];

        $menu = $this->getMenuForm();

        if(isset($_POST['submit'])){
            $this->isSafeCsrf($path);

            if(!strlen($menu['title'])){
                $this->messages[] = "タイトルは必須項目です。";
            }

            if(count($this->messages) === 0){
                $this->db_manager->get('Menus')->edit
                ($params['id'], $menu['title'], $menu['cost'], $menu['category'], $menu['body'], $menu['is_displayed']);
                $this->messages[] = "レシピを変更しました。";
            }    
        }

        if(isset($_POST['img'])){
            $this->isSafeCsrf($path);

            if(empty($_FILES['img']['name'])){
                $this->messages[] = "ファイルを選択してください";
            }else{
                $this->checkImg();
            }

            if(count($this->messages) === 0){
                $this->db_manager->get('Menus')->editImg($params['id'], $this->img);
            }
        }

        $menu = $this->db_manager->get('Menus')->getDetail($params['id']);

        return $this->render([
            'menu' => $menu,
            'categories' => $this->categories,
            'messages' => $this->messages,
            '_token' => $this->generateCsrfToken($path),
        ]);      
    }



    public function othersAction($params)
    {
        $path = 'recipe/user/'.$params['id'];

        $this->InitFindSessions($path);

        if(isset($_POST['find'])){
            $this->isSafeCsrf($path);
            $this->getFindSessions($path);
        }

        $user = $this->db_manager->get('Users')->fetchByUserId($params['id']); 

        $categories = $this->createCategoryCounted();    
        $menus = $this->db_manager->get('Menus')->findMine($user['id'], $_SESSION[$path.'_freeword'], $_SESSION[$path.'_category_selected'], 1);
        $menus = $this->createMenusIncludeCountFavorite($menus);

        $create_sort_array = $this->createSortArray($menus, $_SESSION[$path.'_sort_selected'], 'created_at', 'updated_at', 'favorite');

        array_multisort($create_sort_array['sort_array'], $create_sort_array['option'], $menus);

        $total_menu = count($menus);
        $page_nation = $this->pageNation($menus, 10);
    
        return $this->render([
            'user' => $user,
            'menus' => $page_nation['records'],
            'page_nation' => $page_nation,
            'total_menu' => $total_menu,
            'categories' => $categories,
            'sorts' => $this->sorts,
            'path' => $path,
            '_token' => $this->generateCsrfToken($path),
        ]);      
    }




    public function recipesAction()
    {
        $path = 'recipe';

        $this->InitFindSessions($path);

        if(isset($_POST['find'])){
            $this->isSafeCsrf($path);
            $this->getFindSessions($path);
        }

        $categories = $this->createCategoryCounted();    
        $menus = $this->db_manager->get('Menus')->find($_SESSION[$path.'_freeword'], $_SESSION[$path.'_category_selected']);
        $menus = $this->createMenusIncludeCountFavorite($menus);

        $create_sort_array = $this->createSortArray($menus, $_SESSION[$path.'_sort_selected'], 'created_at', 'updated_at', 'favorite');
        array_multisort($create_sort_array['sort_array'], $create_sort_array['option'], $menus);

        $total_menu = count($menus);
        $page_nation = $this->pageNation($menus, 10);

        return $this->render([
            'total_menu' => $total_menu,
            'categories' => $categories,
            'sorts' => $this->sorts,
            'menus' => $page_nation['records'],
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


    public function isSafeCsrf($path)
    {
      $token = $this->request->getPost('_token');
  
      if(!$this->checkCsrfToken($path, $token)){
        return $this->redirect('/'.$path);
      }
    }



    public function getFindSessions($path)
    {
        $_SESSION[$path.'_freeword'] = $this->request->getPost('freeword');
        $_SESSION[$path.'_category_selected'] = $this->request->getPost('category');
        $_SESSION[$path.'_sort_selected'] = $this->request->getPost('sort');
        $_SESSION[$path.'_is_displayed'] = $this->request->getPost('is_displayed');
    }



    public function initFindSessions($path)
    {
        if(empty($_SESSION[$path.'_freeword'])){
            $_SESSION[$path.'_freeword'] = '';
        }    
        if(empty($_SESSION[$path.'_category_selected'])){
            $_SESSION[$path.'_category_selected'] = '';
        }    
        if(empty($_SESSION[$path.'_sort_selected'])){
            $_SESSION[$path.'_sort_selected'] = '作成日（新しい順）';
        }    
        if(!isset($_SESSION[$path.'_is_displayed'])){
            $_SESSION[$path.'_is_displayed'] = '1';
        }        
    }


    
    public function getMenuForm()
    {
        return [
            'title' => $this->request->getPost('title'),
            'cost' => $this->request->getPost('cost'),
            'category' => $this->request->getPost('category'),
            'body' => $this->request->getPost('body'),
            'is_displayed' => $this->request->getPost('is_displayed'),
        ];
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