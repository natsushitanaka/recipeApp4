<?php

class FavoriteController extends Controller
{
    public function newAction($params)
    {
        if(!$this->request->isPost()){
            $this->forward404();
        }

        $this->isSafeCsrf('recipe/detail'.$params['id']);

        $this->db_manager->get('Favorites')->add($_SESSION['user']['id'], $params['id']);

        return $this->redirect('/recipe/'.$params['id']);
    }

    public function deleteAction($params)
    {
        if(!$this->request->isPost()){
            $this->forward404();
        }

        $this->isSafeCsrf('favorites');

        $this->db_manager->get('Favorites')->delete($_SESSION['user']['id'], $params['id']);

        return $this->redirect('/recipe/'.$params['id']);
    }

    public function isSafeCsrf($token_path)
    {
      $token = $this->request->getPost('_token');
  
      if(!$this->checkCsrfToken($token_path, $token)){
        return $this->redirect('/'.$token_path);
      }
    }

}
