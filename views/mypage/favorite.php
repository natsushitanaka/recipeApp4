<?php $this->setLayoutvar('title', 'お気に入り')?>

<a href="<?= $base_url; ?>/">ホーム</a>

<h3>お気に入りリスト</h3>

<?php if(count($menus) === 0):?>
    <p>＊お気に入りの登録がありません</p>
<?php else: ?>
    <?= $this->render('menuTable', ['menus' => $menus, '_token' => $_token, 'is_mypage' => '']); ?>
    <?= $this->render('pageNation', ['page_nation' => $page_nation]); ?>
<?php endif; ?>
