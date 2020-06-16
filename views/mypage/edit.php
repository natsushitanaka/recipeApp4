<?php $this->setLayoutvar('title', 'ユーザー情報編集')?>

<a href="<?= $base_url; ?>/">ホーム</a>
<a href="<?= $base_url; ?>/mypage/password">パスワード変更</a>

<h3>ユーザー情報編集</h3>

<?php if(isset($messages) && count($messages) > 0): ?>
  <?= $this->render('messages', array('messages' => $messages)); ?>
<?php endif; ?>

<form action="" method="POST">
<input type="hidden" name="_token" value="<?= $this->escape($_token); ?>">
<p>ユーザー名</p>
<input type="text" name="user_name" value="<?= $this->escape($user['user_name']); ?>">
<input type="submit" value="変更する">
</form>

<p>アイコン</p>
<form method="POST" action="" enctype="multipart/form-data">
  <input type="file" name="icon">
  <input type="submit" value="送信">
</form>

