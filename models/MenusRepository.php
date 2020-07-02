<?php

class MenusRepository extends DbRepository
{
  public function insert($user_id, $title, $cost, $category, $body, $is_displayed, $img)
    {
    $sql = "insert into menus(user_id, title, cost, category, body, is_displayed, img, created_at, updated_at) 
    values(:user_id, :title, :cost, :category, :body, :is_displayed, :img, now(), now())";

    $stmt = $this->execute($sql, [
      ':user_id' => $user_id,
      ':title' => $title,
      ':category' => $category,
      ':cost' => $cost,
      ':body' => $body,
      ':is_displayed' => $is_displayed,
      ':img' => $img,
    ]);
  }

  public function getMyMenuAmount($user_id)
  {
    $sql = "select count(id) from menus where user_id = :user_id";
    return $this->fetch($sql, [
      ':user_id' => $user_id,
    ]);
  }

  public function getAll()
  {
    $sql = "select menus.id, menus.title, menus.body, menus.cost, menus.user_id, menus.created_at, menus.updated_at, menus.category, menus.is_displayed, users.user_name
    from menus left join users on menus.user_id = users.id 
    where menus.is_displayed = 1 
    order by menus.created_at desc";

    return $this->fetchAll($sql);
  }

  public function getFavorites($user_id)
  {
    $sql = "select menus.id, menus.title, menus.body, menus.cost, menus.user_id, menus.created_at, menus.updated_at, menus.category, menus.is_displayed, users.user_name
    from favorites 
    left join menus on favorites.menu_id = menus.id 
    left join users on favorites.user_id = users.id 
    where favorites.user_id = :user_id 
    and is_displayed = 1 
    order by menus.created_at desc";

    return $this->fetchAll($sql, [
      ':user_id' => $user_id,
    ]);
  }

  public function getDetail($id)
  {
    $sql = "select menus.*, users.user_name
     from menus 
     left join users on menus.user_id = users.id 
     where menus.id = :id";
    return $this->fetch($sql, [
      ':id' => $id
    ]);
  }

  public function edit($id, $title, $cost, $category, $body, $is_displayed)
    {
      $sql = "update menus set title = :title, cost = :cost, category = :category,
              body = :body, is_displayed = :is_displayed, updated_at = now() where id = :id";

      $stmt = $this->execute($sql, [
        ':title' => $title,
        ':cost' => $cost,
        ':category' => $category,
        ':body' => $body,
        ':is_displayed' => $is_displayed,
        ':id' => $id
      ]);
    }

  public function editImg($id, $img)
    {
      $sql = "update menus set img = :img, updated_at = now() where id = :id";

      $stmt = $this->execute($sql, [
        ':img' => $img,
        ':id' => $id
      ]);
    }

  public function delete($id)
    {
      $sql = "delete from menus where id = :id";

      $stmt = $this->execute($sql, [
        ':id' => $id,
      ]);
    }

  public function find($freeword, $category)
    {
      if(!empty($category)){
        
        $sql = "
        select menus.id, menus.title, menus.body, menus.cost, menus.user_id, menus.created_at, menus.updated_at, menus.category, menus.is_displayed, users.user_name
        from menus 
        left join users on menus.user_id = users.id 
        where category = :category 
        and is_displayed = 1  
        and title like :title 
        order by menus.created_at desc
        ";

        return $this->fetchAll($sql, [
          ':category' => $category,
          ':title' => "%".$freeword."%"
        ]);
      }else{
        $sql = "
        select menus.id, menus.title, menus.body, menus.cost, menus.user_id, menus.created_at, menus.updated_at, menus.category, menus.is_displayed, users.user_name
        from menus 
        left join users on menus.user_id = users.id 
        where title like :title 
        and is_displayed = 1  
        order by menus.created_at desc
        ";
        
        return $this->fetchAll($sql, [
          ':title' => "%".$freeword."%"
        ]);
      }
    }

  public function findMine($user_id, $freeword, $category, $is_displayed)
    {
      if(!empty($category)){
        
        $sql = "
        select menus.id, menus.title, menus.body, menus.cost, menus.user_id, menus.created_at, menus.updated_at, menus.category, menus.is_displayed, users.user_name
        from menus 
        left join users on menus.user_id = users.id 
        where category = :category 
        and is_displayed = :is_displayed 
        and title like :title 
        and users.id = :user_id 
        order by menus.created_at desc
        ";

        return $this->fetchAll($sql, [
          ':user_id' => $user_id,
          ':category' => $category,
          ':is_displayed' => $is_displayed,
          ':title' => "%".$freeword."%",
        ]);
      }else{
        $sql = "
        select menus.id, menus.title, menus.body, menus.cost, menus.user_id, menus.created_at, menus.updated_at, menus.category, menus.is_displayed, users.user_name
        from menus 
        left join users on menus.user_id = users.id 
        where title like :title 
        and is_displayed = :is_displayed 
        and users.id = :user_id 
        order by menus.created_at desc
        ";
        
        return $this->fetchAll($sql, [
          ':user_id' => $user_id,
          ':is_displayed' => $is_displayed,
          ':title' => "%".$freeword."%",
        ]);
      }
    }

  public function countCategory($category)
    {
      $sql = "select count(id) as :category from menus where category = :category and is_displayed = 1";

      return $this->fetch($sql, [
        ':category' => $category
      ]);
    }

}