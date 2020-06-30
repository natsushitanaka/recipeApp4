<?php $this->setLayoutvar('title', 'レシピ検索')?>

<?php if($session->isAuthenticated()): ?>
  <a href="<?= $base_url; ?>/">ホーム</a>
<?php else: ?>
  <a href="<?= $base_url; ?>/account/signin">ログイン</a>
  <a href="<?= $base_url; ?>/account/signup">アカウント登録</a>
<?php endif; ?>

<h3>レシピ検索</h3>

<p>
【フリーワード：
  <?php if(strlen($_GET['freeword'])): ?>
    <?= $this->escape($_GET['freeword']); ?>
  <?php else: ?>
    指定なし
  <?php endif; ?>
  】
【カテゴリ：
  <?php if(strlen($_GET['category'])): ?>
    <?= $this->escape($_GET['category']); ?>
  <?php else: ?>
    指定なし
  <?php endif; ?>
  】
【並び順：<?= $this->escape($_GET['sort']); ?>】
【件数：<?= $this->escape($total_menu); ?>】
</p>

<?= $this->render('searchForm', [
  'menus' => $menus, 
  'categories' => $categories,
  'sorts' => $sorts,
  'path' => $path,
  '_token' => $_token,
  'is_mypage' => '',
  ]); ?>

<?= $this->render('menuTable', ['menus' => $menus, 'is_mypage' => '']); ?>
<?= $this->render('pageNation', ['page_nation' => $page_nation, 'path' => $path]); ?>
