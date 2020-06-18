<?php

class RecipeController extends Controller
{
    private $categories =array('No Category', '前菜', 'サラダ', 'メイン', 'ご飯・麺', 'おつまみ', 'ドリンク');
    private $sorts =array('作成日（新しい順）', '作成日（古い順）', '更新日（新しい順）', '更新日（古い順）', 'お気に入り（多い順）', 'お気に入り（少ない順）');
    private $messages = array();
    private $img;

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



    public function newAction()
    {
        $img = null;

        $menu = array(
            'title' => $this->request->getPost('title'),
            'cost' => $this->request->getPost('cost'),
            'category' => $this->request->getPost('category'),
            'body' => $this->request->getPost('body'),
            'openRange' => $this->request->getPost('openRange'),
        );

        if(isset($_POST['submit'])){
            if(!strlen($menu['title'])){
                $this->messages[] = "タイトルは必須項目です。";
            }
            if(!empty($_FILES['img']['name'])){
                $this->checkImg();
            }

            if($_POST['cost'] === ''){
                $menu['cost'] = '0';
            }

            if(count($this->messages) === 0){
                $this->db_manager->get('Menus')->insert
                ($_SESSION['user']['id'], $menu['title'], $menu['cost'], $menu['category'], $menu['body'], $menu['openRange'], $this->img);
                $this->messages[] = "新メニューが「{$menu['title']}」登録されました。";
            }    
        }

        return $this->render(array(
            'menu' => $menu,
            'categories' => $this->categories,
            'messages' => $this->messages,
            '_token' => $this->generateCsrfToken('recipe/new'),
          ));      
    }



    public function detailAction($params)
    {
        $menu = $this->db_manager->get('Menus')->getDetail($params['id']);
        $countFavorite = $this->db_manager->get('Favorites')->countFavorite($menu['id']);
        $comments = $this->db_manager->get('Comments')->get($menu['id']);
        $comment = $this->request->getPost('comment');

        $favorite = $this->db_manager->get('Favorites')->isFavorite($_SESSION['user']['id'], $params['id']);

        return $this->render(array(
            'menu' => $menu,
            'countFavorite' => $countFavorite['favorite'],
            'comments' => $comments,
            'favorite' => $favorite,
            '_token' => $this->generateCsrfToken('recipe/detail'),
          ));      
    }



    public function deleteAction($params)
    {
        $this->db_manager->get('Menus')->delete($params['id']);

        return $this->redirect($_SERVER['HTTP_REFERER']);
    }




    public function editAction($params)
    {
        $menu = $this->db_manager->get('Menus')->getDetail($params['id']);

        if(isset($_POST['submit'])){
            $menu = array(
                'title' => $this->request->getPost('title'),
                'cost' => $this->request->getPost('cost'),
                'category' => $this->request->getPost('category'),
                'body' => $this->request->getPost('body'),
                'openRange' => $this->request->getPost('openRange'),
            );    

            if($_POST['cost'] === ''){
                $menu['cost'] = '0';
            }

            if(!strlen($menu['title'])){
                $this->messages[] = "タイトルは必須項目です。";
            }

            if(count($this->messages) === 0){
                $this->db_manager->get('Menus')->edit
                ($params['id'], $menu['title'], $menu['cost'], $menu['category'], $menu['body'], $menu['openRange']);
                $this->messages[] = "レシピを変更しました。";
            }    
        }

        if(isset($_POST['img'])){
            if(empty($_FILES['img']['name'])){
                $this->messages[] = "ファイルを選択してください";
            }else{
                $this->checkImg();
            }

            if(count($this->messages) === 0){
                $this->db_manager->get('Menus')->editImg($params['id'], $this->img);
            }
        }


        return $this->render(array(
            'menu' => $menu,
            'categories' => $this->categories,
            'messages' => $this->messages,
            '_token' => $this->generateCsrfToken('recipe/edit'),
          ));      
    }



    public function othersAction()
    {
        if(!isset($_GET['user'])){
            return $this->redirect($_SERVER['HTTP_REFERER']);
        }else{
            $user = $this->db_manager->get('Users')->fetchById($_GET['user']);
            $getMine = $this->db_manager->get('Menus')->getMine($_GET['user']);
        }

        foreach($getMine as $menu){
            $countFavorite = $this->db_manager->get('Favorites')->countFavorite($menu['id']);
            $menu += $countFavorite;
            $menus[] = $menu;
        }

        $total_menu = count($menus);

        $pageNation = $this->pageNation($menus, 10);
    
        return $this->render(array(
            'user' => $user,
            'menus' => $menus,
            'menus' => $pageNation['records'],
            'max_page' => $pageNation['max_page'],
            'total_menu' => $total_menu,
            '_token' => $this->generateCsrfToken('recipe/others'),
          ));      
    }



    public function recipesAction()
    {
        $categories = array();
        $menus = array();
        $sort_array = array();
        $sort_selected = '作成日（新しい順）';
        $category_selected = '指定なし';

        $getAll = $this->db_manager->get('Menus')->getAll();

        $freeword = $this->request->getPost('freeword');

        if(isset($_POST['find'])){
            $category_selected = $this->request->getPost('category');
            $sort_selected = $this->request->getPost('sort');
        
            $getAll = $this->db_manager->get('Menus')->find($freeword, $category_selected);
        }

        foreach($this->categories as $category){
            $categories += $this->db_manager->get('Menus')->countCategory($category);
        }

        foreach($getAll as $menu){
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
            'categories' => $categories,
            'sorts' => $this->sorts,
            'freeword' => $freeword,
            'category_selected' => $category_selected,
            'sort_selected' => $sort_selected,
            'total_menu' => $total_menu,
            'menus' => $pageNation['records'],
            'pageNation' => $pageNation,
                  '_token' => $this->generateCsrfToken('recipe/recipes'),
          ));      
    }


}