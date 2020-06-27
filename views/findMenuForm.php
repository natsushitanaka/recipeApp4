<form action="" method="post">
    <input type="text" name="freeword" value="<?= $this->escape($_SESSION[$path.'_freeword']); ?>" placeholder="フリーワード検索">

    <select name="category">
        <option value="">--Category--</option>
        <?php foreach($categories as $category => $count): ?>
            <option value="<?= $this->escape($category); ?>" 
            <?php if($this->escape($category) === $this->escape($_SESSION[$path.'_category_selected'])){echo "selected";} ?>>
                <?= $this->escape($category); ?>【<?= $this->escape($count); ?>】
            </option>
        <?php endforeach; ?>
    </select>
    
    <?php if($is_mypage === 'yes'): ?>
    <select name="is_displayed">
      <option value="1" <?php if($_SESSION[$path.'_is_displayed'] === '1'){echo 'selected';} ?>>公開</option>
      <option value="0" <?php if($_SESSION[$path.'_is_displayed'] === '0'){echo 'selected';} ?>>非公開</option>
    </select>
    <?php endif; ?>

    <select name="sort">
      <?php foreach($sorts as $sort): ?>
          <option value="<?= $this->escape($sort); ?>" 
          <?php if($this->escape($sort) === $this->escape($_SESSION[$path.'_sort_selected'])){echo "selected";} ?>>
          <?= $this->escape($sort); ?>
        </option>
        <?php endforeach; ?>
    </select>

    <input type="submit" name="find" value="検索">
    <input type="hidden" name="_token" value="<?= $this->escape($_token); ?>">
</form>
