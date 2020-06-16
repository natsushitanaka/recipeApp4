<?php

class MenusRepository extends DbRepository
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

  public function getMine($user_id)
  {
    $sql = "select menus.id, menus.title, menus.body, menus.cost, menus.user_id, menus.created_at, menus.updated_at, menus.openRange, menus.category, users.user_name
    from menus 
    left join users on menus.user_id = users.id 
    where menus.user_id = :id 
    order by menus.created_at desc";

    return $this->fetchAll($sql, array(
      ':id' => $user_id,
    ));
  }

  public function getAll()
  {
    $sql = "select menus.id, menus.title, menus.body, menus.cost, menus.user_id, menus.created_at, menus.updated_at, menus.category, menus.openRange, users.user_name
    from menus left join users on menus.user_id = users.id 
    where menus.openRange = 1 
    order by menus.created_at desc";

    return $this->fetchAll($sql);
  }

  public function getFavorites($user_id)
  {
    $sql = "select menus.id, menus.title, menus.body, menus.cost, menus.user_id, menus.created_at, menus.category, menus.openRange, users.user_name
    from favorites 
    left join menus on favorites.menu_id = menus.id 
    left join users on favorites.user_id = users.id 
    where favorites.user_id = :user_id 
    order by menus.created_at desc";

    return $this->fetchAll($sql, array(
      ':user_id' => $user_id,
    ));
  }

  public function getDetail($id)
  {
    $sql = "select menus.*, users.user_name
     from menus 
     left join users on menus.user_id = users.id 
     where menus.id = :id";
    return $this->fetch($sql, array(
      ':id' => $id
    ));
  }

  public function edit($id, $title, $cost, $category, $body, $openRange)
    {
      $sql = "update menus set title = :title, cost = :cost, category = :category,
              body = :body, openRange = :openRange, updated_at = now() where id = :id";

      $stmt = $this->execute($sql, array(
        ':title' => $title,
        ':cost' => $cost,
        ':category' => $category,
        ':body' => $body,
        ':openRange' => $openRange,
        ':id' => $id
      ));
    }

  public function editImg($id, $img)
    {
      $sql = "update menus set img = :img, created_at = now() where id = :id";

      $stmt = $this->execute($sql, array(
        ':img' => $img,
        ':id' => $id
      ));
    }

  public function delete($id)
    {
      $sql = "delete from menus where id = :id";

      $stmt = $this->execute($sql, array(
        ':id' => $id,
      ));
    }

  public function find($freeword, $category)
    {
      if(strlen($category)){
        
        $sql = "
        select menus.id, menus.title, menus.body, menus.cost, menus.user_id, menus.created_at, menus.updated_at, menus.category, menus.openRange, users.user_name
        from menus 
        left join users on menus.user_id = users.id 
        where category = :category 
        and openRange = 1  
        and title like :title 
        order by menus.created_at desc
        ";

        return $this->fetchAll($sql, array(
          ':category' => $category,
          ':title' => "%".$freeword."%"
        ));
      }else{
        $sql = "
        select menus.id, menus.title, menus.body, menus.cost, menus.user_id, menus.created_at, menus.updated_at, menus.category, menus.openRange, users.user_name
        from menus 
        left join users on menus.user_id = users.id 
        where title like :title 
        and openRange = 1  
        order by menus.created_at desc
        ";
        
        return $this->fetchAll($sql, array(
          ':title' => "%".$freeword."%"
        ));
      }
    }

  public function findMine($freeword, $category, $openRange)
    {
      if(strlen($category)){
        
        $sql = "
        select menus.id, menus.title, menus.body, menus.cost, menus.user_id, menus.created_at, menus.updated_at, menus.category, menus.openRange, users.user_name
        from menus 
        left join users on menus.user_id = users.id 
        where category = :category 
        and openRange in (:openRange) 
        and title like :title 
        order by menus.created_at desc
        ";

        return $this->fetchAll($sql, array(
          ':category' => $category,
          ':openRange' => $openRange,
          ':title' => "%".$freeword."%",
        ));
      }else{
        $sql = "
        select menus.id, menus.title, menus.body, menus.cost, menus.user_id, menus.created_at, menus.updated_at, menus.category, menus.openRange, users.user_name
        from menus 
        left join users on menus.user_id = users.id 
        where title like :title 
        and openRange in (:openRange) 
        order by menus.created_at desc
        ";
        
        return $this->fetchAll($sql, array(
          ':openRange' => $openRange,
          ':title' => "%".$freeword."%",
        ));
      }
    }

  public function countCategory($category)
    {
      $sql = "select count(id) as :category from menus where category = :category and openRange = 1";

      return $this->fetch($sql, array(
        ':category' => $category
      ));
    }

}