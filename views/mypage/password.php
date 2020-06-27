<?php $this->setLayoutvar('title', 'パスワード変更')?>

<a href="<?= $base_url; ?>/">ホーム画面</a>
<a href="<?= $base_url; ?>/mypage/edit">ユーザー情報編集</a>

<h3>パスワード変更</h3>

<?php if(isset($messages) && count($messages) > 0): ?>
  <?= $this->render('messages', array('messages' => $messages)); ?>
<?php endif; ?>

<form action="" method="POST">
<input type="hidden" name="_token" value="<?= $this->escape($_token); ?>">
<p>パスワード</p>
<input type="password" name="now_password" value="<?= $this->escape($password['now']); ?>" placeholder="現在のパスワード"><br>
<input type="password" name="new_password" value="<?= $this->escape($password['new']); ?>" placeholder="新しいパスワード"><br>
<input type="password" name="validate_password" value="<?= $this->escape($password['validate']); ?>" placeholder="新しいパスワード(確認)"><br>
<input type="submit" value="変更する">
</form>
