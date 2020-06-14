<?php $this->setLayoutvar('title', 'ホーム')?>

<a href="<?= $base_url; ?>/account/signin">ログイン</a>
<a href="<?= $base_url; ?>/account/signup">アカウント登録</a>
<a href="<?= $base_url; ?>/account/signout">ログアウト</a>
<a href="<?= $base_url; ?>/account/unsubscribe">退会</a>

<h3>ホーム</h3>

<a href="<?= $base_url; ?>/mypage/edit">ユーザー情報編集</a>
<a href="<?= $base_url; ?>/recipe/new">新規レシピ作成</a>

<?php if($session->isAuthenticated()): ?>
  <p>name:<?= $user['user_name']; ?></p>
  <div>
    <?php if(isset($user['icon'])): ?>
      <img src="data:image/png;base64,<?php base64_encode($user['icon']); ?>">
    <?php else: ?>
      <p>No Image</p>
    <?php endif; ?>
  </div>
<?php else: ?>
  <p>未ログイン</p>
<?php endif; ?>

<?php if(isset($menus) && count($menus) > 0): ?>
  <?php foreach($menus as $menu): ?>
    <table>
      <tr>
        <td>
          <a href="<?= $base_url; ?>/recipe/detail/<?= $this->escape($menu['id']); ?>">
            <?= $this->escape($menu['title']); ?>
          </a>
        </td>
        <td><?= $this->escape($menu['category']); ?></td>
        <td><?php if($menu['openRange'] === '1'){echo '公開';}else{echo '非公開';} ?></td>
        <td><?= $this->escape($menu['created_at']); ?></td>
      </tr>
    </table>
  <?php endforeach; ?>
<?php endif; ?>
