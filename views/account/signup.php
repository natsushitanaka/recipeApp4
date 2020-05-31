<?php $this->setLayoutVar('title', 'アカウント登録') ?>

<h3>アカウント登録</h3>

<?php if(isset($messages) && count($messages) > 0): ?>
  <?= $this->render('messages', array('messages' => $messages)); ?>
<?php endif; ?>

<form action="<?= $base_url; ?>/account/register" method="post">
  <input type="hidden" name="_token" value="<?= $this->escape($_token); ?>">

  <?= $this->render('account/account_form', array(
    'user_name' => $user_name, 'password' => $password,
  )); ?>

  <input type="submit" value="登録">
</form>
<a href="<?= $base_url; ?>/account">ログイン画面</a>
<a href="<?= $base_url; ?>/">ホーム画面</a>
