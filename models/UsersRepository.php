<?php

class UsersRepository extends DbRepository
{
  public function isUniqueUserName($user_name)
  {
    $sql = "select count(id) as count from users where user_name = :user_name and deleted_at is null";

    $row = $this->fetch($sql, array(
      ':user_name' => $user_name
    ));
    if($row['count'] === '0'){
        return true;
    }
        return false;
  }

  public function insert($user_name, $password)
  {
    $password = $this->hashPassword($password);

    $sql = "insert into users(user_name, password, created_at, updated_at) values(:user_name, :password, now(), now())";
    $stmt = $this->execute($sql, array(
      ':user_name' => $user_name,
      ':password' => $password
    ));
  }

  public function unsubscribe($id)
  {
    $sql = "update users set deleted_at = now() where id = :id";
    $stmt = $this->execute($sql, array(
      ':id' => $id,
    ));
  }

  public function editName($id, $user_name)
  {
    $sql = "update users set user_name = :user_name where id = :id";
    $stmt = $this->execute($sql, array(
      ':id' => $id,
      ':user_name' => $user_name,
    ));
  }

  public function editIcon($id, $icon)
  {
    $iconHandle = fbsql_create_blob($icon);

    $sql = "update users set icon = :icon where id = :id";
    $stmt = $this->execute($sql, array(
      ':id' => $id,
      ':icon' => $iconHandle,
    ));
  }

  public function editPassword($id, $password)
  {
    $password = $this->hashPassword($password);

    $sql = "update users set password = :password where id = :id";
    $stmt = $this->execute($sql, array(
      ':id' => $id,
      ':password' => $password,
    ));
  }

  public function isSamePassword($now_password, $validate_password)
  {
    $validate_password = $this->hashPassword($validate_password);

    if($validate_password === $now_password){
      return true;
    }else{
      return false;
    }
  }

  public function hashPassword($password)
  {
    return sha1($password. 'SecretKey');
  }

  public function fetchByUserName($user_name)
  {
    $sql = "select * from users where user_name = :user_name and deleted_at is null";

    return $this->fetch($sql, array(
      ':user_name' => $user_name,
    ));
  }

  public function getMyFavorites($id)
  {
    $sql = "select * from favorites where user_id = :id";

    return $this->fetch($sql, array(
      ':id' => $id,
    ));
  }
}
