<?php $this->setLayoutvar('title', 'ホーム')?>

<a href="<?= $base_url; ?>/account/signout">ログアウト</a>
<a href="<?= $base_url; ?>/account/unsubscribe">退会</a>

<h3>ホーム</h3>

<p>name:<?= $user['user_name']; ?></p>
<div>
  <?php if(isset($user['icon'])): ?>
    <img src="data:image/png;base64,<?php base64_encode($user['icon']); ?>">
  <?php else: ?>
    <p>No Image</p>
  <?php endif; ?>
</div>
<form method="POST" action="" enctype="multipart/form-data">
  <input type="file" name="icon">
  <input type="submit" value="送信">
</form>

<!-- <?php if(isset($messages) && count($messages) > 0): ?>
  <?= $this->render('messages', array('messages' => $messages)); ?>
<?php endif; ?>

<form action="<?= $base_url; ?>/account/authenticate" method="post">
  <input type="hidden" name="_token" value="<?= $this->escape($_token); ?>">

  <?= $this->render('account/account_form', array(
    'user_name' => $user_name, 'password' => $password,
  )); ?>

  <input type="submit" value="ログイン">
</form>

<a href="<?= $base_url; ?>/account/signup">アカウント登録</a>
<a href="<?= $base_url; ?>/">ホーム画面</a> -->
