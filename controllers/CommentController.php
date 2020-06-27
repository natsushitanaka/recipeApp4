<?php

class CommentController extends Controller
{
    protected $auth_actions = ['new', 'delete'];

    public function newAction($params)
    {
        if(!$this->request->isPost()){
            $this->forward404();
        }
      
        $this->isSafeCsrf('recipe/detail'.$params['id']);

        $this->session->remove('comment_error');

        $comment = $this->request->getPost('comment');
        
        if(!strlen($comment)){
            $this->session->set('comment_error', 'コメントを入力してください。');
        }else{
            $this->db_manager->get('Comments')->insert($_SESSION['user']['id'], $params['id'], $comment);
        }

        return $this->redirect('/recipe/'.$params['id']);
    }

    public function deleteAction($params)
    {
        if(!$this->request->isPost()){
            $this->forward404();
        }

        $this->isSafeCsrf('recipe/detail'.$params['id']);

        $this->db_manager->get('Comments')->delete($params['id']);

        return $this->redirect($_SERVER['HTTP_REFERER']);

    }


    public function isSafeCsrf($token_path)
    {
      $token = $this->request->getPost('_token');
  
      if(!$this->checkCsrfToken($token_path, $token)){
        return $this->redirect('/'.$token_path);
      }
    }

}