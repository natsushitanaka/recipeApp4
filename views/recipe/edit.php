<?php $this->setLayoutvar('title', 'メニュー編集')?>

<a href="<?= $base_url; ?>/">ホーム</a>
<a href="<?= $this->escape($_SERVER['HTTP_REFERER']); ?>">戻る</a>

<h3>メニュー編集</h3>

<?php if(isset($messages) && count($messages) > 0): ?>
  <?= $this->render('messages', array('messages' => $messages)); ?>
<?php endif; ?>

<div>
    <?php if($menu['img'] !== null): ?>
        <img src="/imgs/<?php echo $menu['img']; ?>" width="200" height="200">
    <?php else: ?>
        <p>No Image</p>
    <?php endif; ?>
</div>

<form method="POST" action="">
    <input type="text" maxlength="40" name="title" value="<?= $this->escape($menu['title']); ?>" placeholder="メニュー名(20字以内)">*必須<br>
    <input type="number" name="cost" value="<?= $this->escape($menu['cost']); ?>" placeholder="コスト"><br>
    <select name="category"><br>
        <?php foreach($categories as $category): ?>
            <option value="<?= $this->escape($category); ?>" 
            <?php if($this->escape($category) === $this->escape($menu['category'])){echo "selected";} ?>>
            <?= $this->escape($category); ?></option>
        <?php endforeach; ?>
    </select><br>
    <textarea name="body" placeholder="レシピ">
    <?= $this->escape($menu['body']); ?>
    </textarea><br>
    <input type="radio" name="openRange" value="1"
    <?php if($menu['openRange'] === '1'){echo 'checked'; } ?>>
    <label>公開</label>
    <input type="radio" name="openRange" value="0"
    <?php if($menu['openRange'] === '0' || $menu['openRange'] === null){echo 'checked'; } ?>>
    <label>非公開</label><br>
    <input type="submit" name="submit" value="変更する">
    <input type="hidden" name="_token" value="<?= $this->escape($_token); ?>">
</form>

<h3>画像編集</h3>

<?php if(isset($_SESSION['editImg_error'])): ?>
    <p><?= $this->escape($_SESSION['editImg_error']); ?></p>
<?php endif; ?>

<form method="POST" action="" enctype="multipart/form-data">
    <input type="file" name="img">
    <input type="submit" name="img" value="画像を変更する">
    <input type="hidden" name="_token" value="<?= $this->escape($_token); ?>">
</form>

