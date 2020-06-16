<?php

class CommentsRepository extends DbRepository
{
  public function insert($user_id, $menu_id, $body)
    {
    $sql = "insert into comments(user_id, menu_id, body, created_at) values(:user_id, :menu_id, :body, now())";
    $stmt = $this->execute($sql, array(
      ':user_id' => $user_id,
      ':menu_id' => $menu_id,
      ':body' => $body
    ));
  }

  public function get($menu_id)
  {
    $sql = "select comments.body, comments.created_at, comments.id, comments.user_id, users.user_name from comments
    left join users on comments.user_id = users.id where menu_id = :menu_id order by created_at desc";

    return $stmt = $this->fetchAll($sql, array(
      ':menu_id' => $menu_id,
    ));
  }

  public function delete($comment_id)
    {
      $sql = "delete from comments where id = :id";

      $stmt = $this->execute($sql, array(
        ':id' => $comment_id,
      ));
    }

  // public function countComments($menu_id)
  // {
  //   $sql = "select count(id) as count from comments where menu_id = :menu_id";
  //
  //   $stmt = $this->db_manager->fetch($sql, array(
  //     ':menu_id' => $menu_id
  //   ));
  // }

}