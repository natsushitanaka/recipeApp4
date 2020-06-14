<?php

class RecipeController extends Controller
{
    private $categories =array('No Category', '前菜', 'サラダ', 'メイン', 'ご飯・麺', 'おつまみ', 'ドリンク');

    public function newAction()
    {
        $messages = array();

        $menu = array(
            'title' => $this->request->getPost('title'),
            'cost' => $this->request->getPost('cost'),
            'category' => $this->request->getPost('category'),
            'body' => $this->request->getPost('body'),
            'openRange' => $this->request->getPost('openRange'),
        );

        if(isset($menu['title'])){
            if(!strlen($menu['title'])){
                $messages[] = "タイトルは必須項目です。";
            }

            if(isset($_POST['img'])){
                $tempfile = $_FILES['img']['tmp_name'];
                $img = uniqid();
    
                if (is_uploaded_file($tempfile)) {
                    move_uploaded_file($tempfile , '../web/imgs/'.$img);
                }    
            }else{
                $img = null;
            }

            if(isset($_POST['cost']) && $_POST['cost'] === ''){
                $menu['cost'] = '0';
            }

            if(count($messages) === 0){
                $this->db_manager->get('Recipe')->insert
                ($_SESSION['user']['id'], $menu['title'], $menu['cost'], $menu['category'], $menu['body'], $menu['openRange'], $img);
                $messages[] = "新メニューが「{$menu['title']}」登録されました。";
            }    
        }

        return $this->render(array(
            'menu' => $menu,
            'categories' => $this->categories,
            'messages' => $messages,
            '_token' => $this->generateCsrfToken('recipe/new'),
          ));      
    }

    public function detailAction($params)
    {
        $menu = $this->db_manager->get('Recipe')->getDetail($params['id']);

        return $this->render(array(
            'menu' => $menu,
            '_token' => $this->generateCsrfToken('recipe/detail'),
          ));      
    }


}