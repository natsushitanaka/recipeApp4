<?php

class FavoritesRepository extends DbRepository
{
    public function add($user_id, $menu_id)
    {
        $sql = "insert into favorites(user_id, menu_id, created_at) values(:user_id, :menu_id, now())";

        $this->execute($sql, array(
          ':user_id' => $user_id,
          ':menu_id' => $menu_id
        ));  
    }

    public function delete($user_id, $menu_id)
    {
        $sql = "delete from favorites where user_id = :user_id and menu_id = :menu_id";

        $this->execute($sql, array(
            ':user_id' => $user_id,
            ':menu_id' => $menu_id
        ));
    }

    public function isFavorite($user_id, $menu_id)
    {
        $sql = "select count(id) as count from favorites where user_id = :user_id and menu_id = :menu_id";

        $row = $this->fetch($sql, array(
            ':user_id' => $user_id,
            ':menu_id' => $menu_id
        ));

        if($row['count'] === '0'){
            return false;
        }
            return true;
    }

    public function countFavorite($menu_id)
    {
      $sql = "select count(id) as favorite from favorites where menu_id = :menu_id";

      return $this->fetch($sql, array(
        ':menu_id' => $menu_id,
      ));
    }


}
