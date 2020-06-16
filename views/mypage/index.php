<?php $this->setLayoutvar('title', 'ホーム')?>

<a href="<?= $base_url; ?>/account/signin">ログイン</a>
<a href="<?= $base_url; ?>/account/signup">アカウント登録</a>
<a href="<?= $base_url; ?>/account/signout">ログアウト</a>
<a href="<?= $base_url; ?>/account/unsubscribe">退会</a>

<h3>ホーム</h3>

<a href="<?= $base_url; ?>/recipes">レシピ検索</a>
<a href="<?= $base_url; ?>/recipe/new">新規レシピ作成</a>
<a href="<?= $base_url; ?>/mypage/favorite">お気に入り一覧</a>

<h3>ユーザー情報
  <?php if($session->isAuthenticated()): ?>
    【<a href="<?= $base_url; ?>/mypage/edit">編集</a>】
  <?php endif; ?>
</h3>

<?php if($session->isAuthenticated()): ?>
  <p>name:<?= $user['user_name']; ?></p>
  <div>
    <?php if(isset($user['icon'])): ?>
      <img src="data:image/png;base64,<?php base64_encode($user['icon']); ?>">
    <?php else: ?>
      <p>No Image</p>
    <?php endif; ?>
  </div>
<?php else: ?>
  <p>未ログイン</p>
<?php endif; ?>

<h3>マイメニューリスト【<?= $this->escape($total_menu); ?>件】</h3>

<form action="" method="post">
    <input type="text" name="freeword" value="<?= $this->escape($freeword); ?>" placeholder="フリーワード検索">

    <select name="category">
        <option value="">--Category--</option>
        <?php foreach($categories as $category => $count): ?>
            <option value="<?= $this->escape($category); ?>" 
            <?php if($this->escape($category) === $this->escape($category_selected)){echo "selected";} ?>>
                <?= $this->escape($category); ?>【<?= $this->escape($count); ?>】
            </option>
        <?php endforeach; ?>
    </select>
    
    <select name="sort">
      <?php foreach($sorts as $sort): ?>
          <option value="<?= $this->escape($sort); ?>" 
          <?php if($this->escape($sort) === $this->escape($sort_selected)){echo "selected";} ?>>
          <?= $this->escape($sort); ?>
        </option>
        <?php endforeach; ?>
    </select>

    <select name="openRange">
      <option value="1" <?php if($openRange === '1'){echo 'selected';} ?>>公開</option>
      <option value="0" <?php if($openRange === '0'){echo 'selected';} ?>>非公開</option>
    </select>

    <input type="submit" name="find" value="検索">
    <input type="hidden" name="_token" value="<?= $this->escape($_token); ?>">
</form>

<?php if(isset($menus) && count($menus) > 0): ?>
  <table>
    <tr>
        <th>メニュー名</th>
        <th>カテゴリ</th>
        <th>お気に入り数</th>
        <th>公開範囲</th>
        <th>作成日</th>
        <th>更新日</th>
    </tr>
    <?php foreach($menus as $menu): ?>
    <tr>
      <td>
        <a href="<?= $base_url; ?>/recipe/detail/<?= $this->escape($menu['id']); ?>">
          <?= $this->escape($menu['title']); ?>
        </a>
      </td>
      <td><?= $this->escape($menu['category']); ?></td>
      <td><?= $this->escape($menu['favorite']); ?></td>
      <td><?php if($menu['openRange'] === '1'){echo '公開';}else{echo '非公開';} ?></td>
      <td><?= $this->escape($menu['created_at']); ?></td>
      <td><?= $this->escape($menu['updated_at']); ?></td>
      <td>
        <a class="delete" href="<?= $base_url; ?>/recipe/delete/<?= $this->escape($menu['id']); ?>">x</a>
      </td>
    </tr>
    <?php endforeach; ?>
  </table>
<?php endif; ?>

<?php if($pageNation['current_page'] > 1): ?>
  <a href="<?= $base_url; ?>/?page=<?= $this->escape($pageNation['current_page'] - 1); ?>">前へ</a>
<?php else: ?>
  <span>前へ</span>
<?php endif; ?>

<?php for($i = $pageNation['range'] + $pageNation['nextDiff']; $i > 0; $i--): ?>
  <?php if($pageNation['current_page'] - $i < 1)continue; ?>
  <a href="<?= $base_url; ?>/?page=<?= $this->escape($pageNation['current_page'] - $i); ?>"><?= $this->escape($pageNation['current_page'] - $i); ?></a>
<?php endfor; ?>

<span><?= $this->escape($pageNation['current_page']); ?></span>

<?php for ($i = 1; $i <= $pageNation['range'] + $pageNation['prevDiff']; $i++) : ?>
  <?php if ($pageNation['current_page'] + $i > $pageNation['max_page']) break; ?>
  <a href="<?= $base_url; ?>/?page=<?= $this->escape($pageNation['current_page'] + $i); ?>"><?= $this->escape($pageNation['current_page'] + $i); ?></a>
<?php endfor; ?>

<?php if($pageNation['current_page'] < $pageNation['max_page']): ?>
  <a href="<?= $base_url; ?>/?page=<?= $this->escape($pageNation['current_page'] + 1); ?>">次へ</a>
<?php else: ?>
  <span>次へ</span>
<?php endif; ?>

