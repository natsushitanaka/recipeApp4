<?php if(isset($menus) && count($menus) > 0): ?>
    <table>
      <tr>
        <th>メニュー名</th>
        <?php if($is_mypage !== 'yes'): ?>
          <th>ユーザー名</th>
        <?php endif; ?>
        <th>カテゴリ</th>
        <th>お気に入り数</th>
        <?php if($is_mypage === 'yes'): ?>
          <th>公開範囲</th>
        <?php endif; ?>
        <th>作成日</th>
        <th>更新日</th>
      </tr>
      <?php foreach($menus as $menu): ?>
      <tr>
        <td>
          <a href="<?= $base_url; ?>/recipe/<?= $this->escape($menu['id']); ?>">
            <?= $this->escape($menu['title']); ?>
          </a>
        </td>
        <?php if($is_mypage !== 'yes'): ?>
          <td>
          <a href="<?= $base_url; ?>/recipe/user/<?= $this->escape($menu['user_id']); ?>">
            <?= $this->escape($menu['user_name']); ?>
          </a>
        </td>
        <?php endif; ?>
        <td><?= $this->escape($menu['category']); ?></td>
        <td><?= $this->escape($menu['favorite']); ?></td>
        <?php if($is_mypage === 'yes'): ?>
          <td><?php if($menu['is_displayed'] === '1'){echo '公開';}else{echo '非公開';} ?></td>
        <?php endif; ?>
        <td><?= $this->escape($menu['created_at']); ?></td>
        <td><?= $this->escape($menu['updated_at']); ?></td>
        <td>
            <?php if($session->isAuthenticated() && $menu['user_id'] === $_SESSION['user']['id']): ?>
              <form action="<?= $base_url; ?>/recipe/delete/<?= $this->escape($menu['id']); ?>" method="POST">
                <input class="delete" type="submit" value="x">
                <input type="hidden" name="_token" value="<?= $this->escape($_token); ?>">
              </form>
            <?php endif; ?>
        </td>
      </tr>
      <?php endforeach; ?>
    </table>
<?php endif; ?>
