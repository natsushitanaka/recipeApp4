<?php

class CommentController extends Controller
{
    public function newAction($params)
    {
        $this->session->remove('comment_error');

        $comment = $this->request->getPost('comment');

        if(!strlen($comment)){
            $this->session->set('comment_error', 'コメントを入力してください。');
        }else{
            $this->db_manager->get('Comments')->insert($_SESSION['user']['id'], $params['id'], $comment);
        }

        return $this->redirect('/recipe/detail/'.$params['id']);
    }

    public function deleteAction($params)
    {
        $this->db_manager->get('Comments')->delete($params['id']);

        return $this->redirect($_SERVER['HTTP_REFERER']);

    }

}