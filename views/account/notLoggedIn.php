<?php $this->setLayoutvar('title', '未ログイン')?>

<h3>ログインが必要な機能です。</h3>

<a href="<?= $this->escape($_SERVER['HTTP_REFERER']); ?>">戻る</a>
<a href="<?= $base_url; ?>/account/signin">ログイン画面へ</a>
<a href="<?= $base_url; ?>/account/signup">アカウント登録へ</a>
