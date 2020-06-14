<?php

class RecipeRepository extends DbRepository
{
  public function insert($user_id, $title, $cost, $category, $body, $openRange, $img)
    {
    $sql = "insert into menus(user_id, title, cost, category, body, openRange, img, created_at, updated_at) 
    values(:user_id, :title, :cost, :category, :body, :openRange, :img, now(), now())";

    $stmt = $this->execute($sql, array(
      ':user_id' => $user_id,
      ':title' => $title,
      ':category' => $category,
      ':cost' => $cost,
      ':body' => $body,
      ':openRange' => $openRange,
      ':img' => $img,
    ));
  }

  public function getMyMenuAmount($user_id)
  {
    $sql = "select count(id) from menus where user_id = :user_id";
    return $this->fetch($sql, array(
      ':user_id' => $user_id,
    ));
  }

  public function getMyAllMenus($user_id)
  {
    $sql = "select menus.id, menus.title, menus.body, menus.cost, menus.user_id, menus.created_at, menus.updated_at, menus.openRange, menus.category, users.user_name
    from menus left join users on menus.user_id = users.id where menus.user_id = :id order by menus.created_at desc";

    return $this->fetchAll($sql, array(
      ':id' => $user_id,
    ));
  }

  public function getAllMenus($user_id)
  {
    $sql = "select menus.id, menus.title, menus.body, menus.cost, menus.user_id, menus.created_at, menus.good, menus.category, menus.appeal, users.user_name
    from menus left join users on menus.user_id = users.id where menus.user_id != :id order by menus.created_at desc";
    return $this->fetchAll($sql, array(
      ':id' => $user_id
    ));
  }

  public function getDetail($id)
  {
    $sql = "select * from menus where id = :id";
    return $this->fetch($sql, array(
      ':id' => $id
    ));
  }

  public function editMenu($id, $title, $appeal, $category, $body, $cost, $img)
    {
      $sql = "update menus set title = :title, appeal = :appeal, category = :category,
              body = :body, cost = :cost, img = :img, created_at = now() where id = :id";

      $stmt = $this->execute($sql, array(
        ':title' => $title,
        ':appeal' => $appeal,
        ':category' => $category,
        ':body' => $body,
        ':cost' => $cost,
        ':img' => $img,
        ':id' => $id
      ));
    }
  public function addGood($id)
    {
      $sql = "update menus set
          good = good +1
          where id = :id
      ";
      $stmt = $this->execute($sql, array(
        ':id' => $id
      ));
    }

  public function deleteMenu($id)
    {
      $sql = "delete from menus where id = :id";

      $stmt = $this->execute($sql, array(
        ':id' => $id,
      ));
    }

  public function findMenu($find_freeword, $find_category)
    {
      if(strlen($find_category)){
        $sql = "select menus.id, menus.title, menus.body, menus.cost, menus.user_id, menus.created_at, menus.good, menus.category, menus.appeal, users.user_name
        from menus left join users on menus.user_id = users.id where category = :category and title like :title order by menus.created_at desc";
        return $this->fetchAll($sql, array(
          ':category' => $find_category,
          ':title' => "%".$find_freeword."%"
        ));
      }else{
        $sql = "select menus.id, menus.title, menus.body, menus.cost, menus.user_id, menus.created_at, menus.good, menus.category, menus.appeal, users.user_name
        from menus left join users on menus.user_id = users.id where title like :title order by menus.created_at desc";
        
        return $this->fetchAll($sql, array(
          ':title' => "%".$find_freeword."%"
        ));
      }
    }

  public function getAmountOfCategory($category)
    {
      $sql = "select count(id) as :category from menus where category = :category";

      return $this->fetch($sql, array(
        ':category' => $category
      ));
    }

}