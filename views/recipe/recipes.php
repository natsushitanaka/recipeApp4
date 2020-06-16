<?php $this->setLayoutvar('title', 'ホーム')?>

<a href="<?= $base_url; ?>/">ホーム</a>

<h3>メニューリスト</h3>

<p>
【フリーワード：
  <?php if(strlen($freeword)): ?>
    <?= $this->escape($freeword); ?>
  <?php else: ?>
    指定なし
  <?php endif; ?>
】
【カテゴリ：<?= $this->escape($category_selected); ?>】
【並び順：<?= $this->escape($sort_selected); ?>】
【件数：<?= $this->escape($total_menu); ?>】
</p>
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

    <input type="submit" name="find" value="検索">
    <input type="hidden" name="_token" value="<?= $this->escape($_token); ?>">
</form>

<?php if(isset($menus) && count($menus) > 0): ?>
    <table>
      <tr>
        <th>メニュー名</th>
        <th>カテゴリ</th>
        <th>お気に入り数</th>
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
        <td><?= $this->escape($menu['created_at']); ?></td>
        <td><?= $this->escape($menu['updated_at']); ?></td>
        <td>
            <?php if($menu['user_id'] === $_SESSION['user']['id']): ?>
                <a class="delete" href="<?= $base_url; ?>/recipe/delete/<?= $this->escape($menu['id']); ?>">x</a>
            <?php endif; ?>
        </td>
      </tr>
      <?php endforeach; ?>
    </table>
<?php endif; ?>

<?php if($pageNation['current_page'] > 1): ?>
  <a href="<?= $base_url; ?>/recipes?page=<?= $this->escape($pageNation['current_page'] - 1); ?>">前へ</a>
<?php else: ?>
  <span>前へ</span>
<?php endif; ?>

<?php for($i = $pageNation['range'] + $pageNation['nextDiff']; $i > 0; $i--): ?>
  <?php if($pageNation['current_page'] - $i < 1)continue; ?>
  <a href="<?= $base_url; ?>/recipes?page=<?= $this->escape($pageNation['current_page'] - $i); ?>"><?= $this->escape($pageNation['current_page'] - $i); ?></a>
<?php endfor; ?>

<span><?= $this->escape($pageNation['current_page']); ?></span>

<?php for ($i = 1; $i <= $pageNation['range'] + $pageNation['prevDiff']; $i++) : ?>
  <?php if ($pageNation['current_page'] + $i > $pageNation['max_page']) break; ?>
  <a href="<?= $base_url; ?>/recipes?page=<?= $this->escape($pageNation['current_page'] + $i); ?>"><?= $this->escape($pageNation['current_page'] + $i); ?></a>
<?php endfor; ?>

<?php if($pageNation['current_page'] < $pageNation['max_page']): ?>
  <a href="<?= $base_url; ?>/recipes?page=<?= $this->escape($pageNation['current_page'] + 1); ?>">次へ</a>
<?php else: ?>
  <span>次へ</span>
<?php endif; ?>
