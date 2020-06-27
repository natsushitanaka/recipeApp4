<?php $this->setLayoutvar('title', 'ログイン')?>

<h3>ログイン</h3>

<?php if(isset($messages) && count($messages) > 0): ?>
  <?= $this->render('messages', ['messages' => $messages]); ?>
<?php endif; ?>

<form action="<?= $base_url; ?>/account/authenticate" method="post">
  <input type="hidden" name="_token" value="<?= $this->escape($_token); ?>">

  <?= $this->render('account/account_form', [
    'user_name' => $user_name,
    'password' => $password,
  ]); ?>

  <input type="submit" value="ログイン">
</form>

<a href="<?= $base_url; ?>/account/signup">アカウント登録</a>
<a href="<?= $base_url; ?>/">ホーム画面</a>
