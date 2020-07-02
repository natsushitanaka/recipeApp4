<?php $this->setLayoutvar('title', 'ユーザー情報編集')?>

<a href="<?= $base_url; ?>/">ホーム</a>
<a href="<?= $base_url; ?>/mypage/password">パスワード変更</a>

<h3>ユーザー情報編集</h3>

<?php if(isset($messages) && count($messages) > 0): ?>
  <?= $this->render('messages', array('messages' => $messages)); ?>
<?php endif; ?>

<form action="" method="POST">
  <p>ユーザー名</p>
  <input type="text" name="user_name" value="<?= $this->escape($user['user_name']); ?>">
  <input type="submit" value="変更する">
  <input type="hidden" name="_token" value="<?= $this->escape($_token); ?>">
</form>

<p>アイコン</p>

<div>
    <?php if(!empty($user['icon_data'])): ?>
        <img src="data:image/<?= $this->escape($user['icon_ext']); ?>;base64,<?= base64_encode($user['icon_data']); ?>" width="100" height="100">
    <?php else: ?>
        <p>No Icon</p>
    <?php endif; ?>
</div>

<form method="POST" action="" enctype="multipart/form-data">
  <input type="file" name="icon">
  <input type="submit" name="icon" value="送信">
  <input type="hidden" name="_token" value="<?= $this->escape($_token); ?>">
</form>

