<?php

class UsersRepository extends DbRepository
{
  public function isUniqueUserName($user_name)
  {
    $sql = "select count(id) as count from users where user_name = :user_name and deleted_at is null";

    $row = $this->fetch($sql, [
      ':user_name' => $user_name
    ]);
    
    if($row['count'] === '0'){
        return true;
    }
        return false;
  }

  public function insert($user_name, $password)
  {
    $password = $this->hashPassword($password);

    $sql = "insert into users(user_name, password, created_at, updated_at) values(:user_name, :password, now(), now())";
    $stmt = $this->execute($sql, [
      ':user_name' => $user_name,
      ':password' => $password
    ]);
  }

  public function unsubscribe($id)
  {
    $sql = "update users set deleted_at = now() where id = :id";
    $stmt = $this->execute($sql, [
      ':id' => $id,
    ]);
  }

  public function editName($id, $user_name)
  {
    $sql = "update users set user_name = :user_name where id = :id";
    $stmt = $this->execute($sql, [
      ':id' => $id,
      ':user_name' => $user_name,
    ]);
  }

  public function editIcon($id, $icon_data, $icon_ext)
  {
    $sql = "update users set icon_data = :icon_data, icon_ext = :icon_ext where id = :id";

    $stmt = $this->execute($sql, [
      ':id' => $id,
      ':icon_data' => $icon_data,
      ':icon_ext' => $icon_ext,
    ]);
  }

  public function editPassword($id, $password)
  {
    $password = $this->hashPassword($password);

    $sql = "update users set password = :password where id = :id";
    $stmt = $this->execute($sql, [
      ':id' => $id,
      ':password' => $password,
    ]);
  }

  public function hashPassword($password)
  {
    return password_hash($password, PASSWORD_DEFAULT);
  }

  public function validatePassword($password, $hash_password)
  {
    return password_verify($password, $hash_password);
  }

  public function fetchByUserName($user_name)
  {
    $sql = "select * from users where user_name = :user_name and deleted_at is null";

    return $this->fetch($sql, [
      ':user_name' => $user_name,
    ]);
  }

  public function fetchByUserId($id)
  {
    $sql = "select id, user_name from users where id = :id and deleted_at is null";

    return $this->fetch($sql, [
      ':id' => $id,
    ]);
  }

  public function getMyFavorites($id)
  {
    $sql = "select * from favorites where user_id = :id";

    return $this->fetch($sql, [
      ':id' => $id,
    ]);
  }
}
