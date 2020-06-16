<?php

class FavoriteController extends Controller
{
    public function newAction($params)
    {
        $this->db_manager->get('Favorites')->add($_SESSION['user']['id'], $params['id']);

        return $this->redirect('/recipe/detail/'.$params['id']);
    }

    public function deleteAction($params)
    {
        $this->db_manager->get('Favorites')->delete($_SESSION['user']['id'], $params['id']);

        return $this->redirect('/recipe/detail/'.$params['id']);
    }
}
