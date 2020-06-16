<?php $this->setLayoutvar('title', $this->escape($user['user_name']) .'のメニューリスト')?>

<a href="<?= $base_url; ?>/">ホーム</a>
<a href="<?= $this->escape($_SERVER['HTTP_REFERER']); ?>">戻る</a>

<h3><?= $this->escape($user['user_name']); ?>さんのメニューリスト【<?= $this->escape($total_menu); ?>件】</h3>

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

<?php for($i = 1; $i <= $max_page; $i++): ?>
  <a href="<?= $base_url; ?>/recipe/others?user=<?= $this->escape($user['id']); ?>&page=<?= $this->escape($i); ?>">
  <?= $this->escape($i); ?></a>
<?php endfor; ?>
