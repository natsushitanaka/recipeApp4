<?php $this->setLayoutvar('title', 'ホーム')?>

<a href="<?= $base_url; ?>/account/signin">ログイン</a>
<a href="<?= $base_url; ?>/account/signup">アカウント登録</a>
<a href="<?= $base_url; ?>/account/signout">ログアウト</a>
<a href="<?= $base_url; ?>/account/unsubscribe">退会</a>

<h3>ホーム</h3>

<a href="<?= $base_url; ?>/mypage/edit">ユーザー情報編集</a>
<p>name:<?= $user['user_name']; ?></p>
<div>
  <?php if(isset($user['icon'])): ?>
    <img src="data:image/png;base64,<?php base64_encode($user['icon']); ?>">
  <?php else: ?>
    <p>No Image</p>
  <?php endif; ?>
</div>
