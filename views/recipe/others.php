<?php $this->setLayoutvar('title', $this->escape($user['user_name']) .'のメニューリスト')?>

<a href="<?= $base_url; ?>/">ホーム</a>
<a href="<?= $this->escape($_SERVER['HTTP_REFERER']); ?>">戻る</a>

<h3><?= $this->escape($user['user_name']); ?>さんのメニューリスト【<?= $this->escape($total_menu); ?>件】</h3>

<?= $this->render('findMenuForm', [
  'menus' => $menus, 
  'categories' => $categories,
  'sorts' => $sorts,
  'path' => $path,
  '_token' => $_token,
  'is_mypage' => '',
  ]); ?>

<?= $this->render('menuTable', ['menus' => $menus, 'is_mypage' => '']); ?>
<?= $this->render('pageNation', ['page_nation' => $page_nation]); ?>

