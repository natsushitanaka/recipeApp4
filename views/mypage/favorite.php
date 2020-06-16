<?php $this->setLayoutvar('title', 'お気に入り')?>

<a href="<?= $base_url; ?>/">ホーム</a>

<h3>お気に入りリスト</h3>

<?php if(isset($favorites) && count($favorites) > 0): ?>
  <?php foreach($favorites as $favorite): ?>
    <table>
      <tr>
        <td>
          <a href="<?= $base_url; ?>/recipe/detail/<?= $this->escape($favorite['id']); ?>">
            <?= $this->escape($favorite['title']); ?>
          </a>
        </td>
        <td><?= $this->escape($favorite['category']); ?></td>
        <td><?php if($favorite['openRange'] === '1'){echo '公開';}else{echo '非公開';} ?></td>
        <td><?= $this->escape($favorite['created_at']); ?></td>
      </tr>
    </table>
  <?php endforeach; ?>
<?php endif; ?>
