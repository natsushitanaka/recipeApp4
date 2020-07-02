<?php if($page_nation['current_page'] > 1): ?>
  <a href="<?= $base_url; ?>/<?= $path; ?>?
  page=<?= $this->escape($page_nation['current_page'] - 1); ?>
  <?php if($path === '' || $path === 'recipe'): ?>&
  freeword=<?= $this->escape($_GET['freeword']); ?>&
  category=<?= $this->escape($_GET['category']); ?>&
  is_displayed=<?= $this->escape($_GET['is_displayed']); ?>&
  sort=<?= $this->escape($_GET['sort']); ?>
  <?php endif; ?>
  ">前へ</a>
<?php else: ?>
  <span>前へ</span>
<?php endif; ?>

<?php for($i = $page_nation['range'] + $page_nation['next_diff']; $i > 0; $i--): ?>
  <?php if($page_nation['current_page'] - $i < 1)continue; ?>
  <a href="<?= $base_url; ?>/<?= $path; ?>?
  page=<?= $this->escape($page_nation['current_page'] - $i); ?>
  <?php if($path === '' || $path === 'recipe'): ?>&
  freeword=<?= $this->escape($_GET['freeword']); ?>&
  category=<?= $this->escape($_GET['category']); ?>&
  is_displayed=<?= $this->escape($_GET['is_displayed']); ?>&
  sort=<?= $this->escape($_GET['sort']); ?>
  <?php endif; ?>
  ">
  <?= $this->escape($page_nation['current_page'] - $i); ?></a>
<?php endfor; ?>

<span><?= $this->escape($page_nation['current_page']); ?></span>

<?php for ($i = 1; $i <= $page_nation['range'] + $page_nation['prev_diff']; $i++) : ?>
  <?php if ($page_nation['current_page'] + $i > $page_nation['max_page']) break; ?>
  <a href="<?= $base_url; ?>/<?= $path; ?>?
  page=<?= $this->escape($page_nation['current_page'] + $i); ?>
  <?php if($path === '' || $path === 'recipe'): ?>&
  freeword=<?= $this->escape($_GET['freeword']); ?>&
  category=<?= $this->escape($_GET['category']); ?>&
  is_displayed=<?= $this->escape($_GET['is_displayed']); ?>&
  sort=<?= $this->escape($_GET['sort']); ?>
  <?php endif; ?>
  "><?= $this->escape($page_nation['current_page'] + $i); ?></a>
<?php endfor; ?>

<?php if($page_nation['current_page'] < $page_nation['max_page']): ?>
  <a href="<?= $base_url; ?>/<?= $path; ?>?
  page=<?= $this->escape($page_nation['current_page'] + 1); ?>
  <?php if($path === '' || $path === 'recipe'): ?>&
    freeword=<?= $this->escape($_GET['freeword']); ?>&
    category=<?= $this->escape($_GET['category']); ?>&
    is_displayed=<?= $this->escape($_GET['is_displayed']); ?>&
    sort=<?= $this->escape($_GET['sort']); ?>
  <?php endif; ?>
  ">次へ</a>
<?php else: ?>
  <span>次へ</span>
<?php endif; ?>
