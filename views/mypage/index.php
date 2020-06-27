<?php $this->setLayoutvar('title', 'ホーム')?>

<?php if(!$session->isAuthenticated()): ?>
  <a href="<?= $base_url; ?>/account/signin">ログイン</a>
  <a href="<?= $base_url; ?>/account/signup">アカウント登録</a>
<?php else: ?>
  <a href="<?= $base_url; ?>/account/signout">ログアウト</a>
  <a href="<?= $base_url; ?>/account/unsubscribe">退会</a>
<?php endif; ?>

<h3>ホーム</h3>

<a href="<?= $base_url; ?>/recipe">レシピ検索</a>
<?php if($session->isAuthenticated()): ?>
  <a href="<?= $base_url; ?>/recipe/new">新規レシピ作成</a>
  <a href="<?= $base_url; ?>/favorites">お気に入り一覧</a>
<?php endif; ?>

<h3>ユーザー情報
  <?php if($session->isAuthenticated()): ?>
    【<a href="<?= $base_url; ?>/mypage/edit">編集</a>】
  <?php endif; ?>
</h3>

<?php if($session->isAuthenticated()): ?>
  <p>ユーザー名：<?= $user['user_name']; ?></p>
  <div>
    <?php if(isset($user['icon_data'])): ?>
      <img src="data:image/<?= $this->escape($user['icon_ext']); ?>;base64,<?= base64_encode($user['icon_data']); ?>" width="100" height="100">
    <?php else: ?>
      <p>No Icon</p>
    <?php endif; ?>
  </div>
<?php else: ?>
  <p>未ログイン</p>
<?php endif; ?>

<?php if($session->isAuthenticated()): ?>
  <?php if($total_menu === 0): ?>
    <h3>マイメニューリスト【<?= $this->escape($total_menu); ?>件】</h3>
    <p>＊メニューの登録がありません</p>
  <?php else: ?>

    <?= $this->render('findMenuForm', [
      'menus' => $menus, 
      'categories' => $categories,
      'sorts' => $sorts,
      'path' => $path,
      '_token' => $_token,
      'is_mypage' => 'yes',
      ]); ?>

    <?= $this->render('menuTable', ['menus' => $menus, 'is_mypage' => 'yes']); ?>
    <?= $this->render('pageNation', ['page_nation' => $page_nation]); ?>
  <?php endif; ?>
<?php endif; ?>

