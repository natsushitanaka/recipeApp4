<?php if($page_nation['current_page'] > 1): ?>
  <a href="<?= $base_url; ?>/recipe?page=<?= $this->escape($page_nation['current_page'] - 1); ?>">前へ</a>
<?php else: ?>
  <span>前へ</span>
<?php endif; ?>

<?php for($i = $page_nation['range'] + $page_nation['next_diff']; $i > 0; $i--): ?>
  <?php if($page_nation['current_page'] - $i < 1)continue; ?>
  <a href="<?= $base_url; ?>/recipe?page=<?= $this->escape($page_nation['current_page'] - $i); ?>"><?= $this->escape($page_nation['current_page'] - $i); ?></a>
<?php endfor; ?>

<span><?= $this->escape($page_nation['current_page']); ?></span>

<?php for ($i = 1; $i <= $page_nation['range'] + $page_nation['prev_diff']; $i++) : ?>
  <?php if ($page_nation['current_page'] + $i > $page_nation['max_page']) break; ?>
  <a href="<?= $base_url; ?>/recipe?page=<?= $this->escape($page_nation['current_page'] + $i); ?>"><?= $this->escape($page_nation['current_page'] + $i); ?></a>
<?php endfor; ?>

<?php if($page_nation['current_page'] < $page_nation['max_page']): ?>
  <a href="<?= $base_url; ?>/recipe?page=<?= $this->escape($page_nation['current_page'] + 1); ?>">次へ</a>
<?php else: ?>
  <span>次へ</span>
<?php endif; ?>
