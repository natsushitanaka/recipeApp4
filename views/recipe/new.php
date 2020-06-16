<?php $this->setLayoutvar('title', '新規レシピ作成')?>

<a href="<?= $base_url; ?>/">ホーム</a>

<h3>新規レシピ作成</h3>

<?php if(isset($messages) && count($messages) > 0): ?>
  <?= $this->render('messages', array('messages' => $messages)); ?>
<?php endif; ?>

<form method="POST" action="" enctype="multipart/form-data">
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
<input type="file" name="img"><br>
<input type="radio" name="openRange" value="1"
<?php if($menu['openRange'] === '1'){echo 'checked'; } ?>>
<label>公開</label>
<input type="radio" name="openRange" value="0"
<?php if($menu['openRange'] === '0' || $menu['openRange'] === null){echo 'checked'; } ?>>
<label>非公開</label><br>
<input type="submit" name="submit" value="登録する">
<input type="hidden" name="_token" value="<?= $this->escape($_token); ?>"><br>
</form>

