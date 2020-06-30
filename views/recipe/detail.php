<?php $this->setLayoutvar('title', 'メニュー詳細')?>

<a href="<?= $base_url; ?>/">ホーム</a>
<a href="<?= $this->escape($_SERVER['HTTP_REFERER']); ?>">戻る</a>

<?php if($menu['user_id'] !== $_SESSION['user']['id'] && $menu['is_displayed'] === '0'): ?>
    <p>非公開メニューです。</p>
<?php else: ?>
<h3>
    メニュー詳細
    <?php if($session->isAuthenticated() && $menu['user_id'] === $_SESSION['user']['id']): ?>
        【<a href="<?= $base_url; ?>/recipe/edit/<?= $this->escape($menu['id']); ?>">編集</a>】
    <?php endif; ?>

</h3>

<table>
    <div>
        <?php if($menu['img'] !== null): ?>
            <img src="/imgs/<?php echo $menu['img']; ?>" width="200" height="200">
        <?php else: ?>
            <p>No Image</p>
        <?php endif; ?>
    </div>
    <tr>
        <td>ユーザー名</td>
        <td>
            <a href="<?= $base_url; ?>/recipe/user/<?= $this->escape($menu['user_id']); ?>">
            <?= $this->escape($menu['user_name']); ?></a>
        </td>
    </tr>
    <tr>
        <td>メニュー名</td>
        <td><?= $this->escape($menu['title']); ?></td>
    </tr>
    <tr>
        <td>カテゴリ</td>
        <td><?= $this->escape($menu['category']); ?></td>
    </tr>
    <tr>
        <td>原価</td>
        <td><?= $this->escape($menu['cost']); ?>円</td>
    </tr>
    <tr>
        <td>公開範囲</td>
        <td><?php if($menu['is_displayed'] === '1'){echo '公開';}else{echo '非公開';} ?></td>
    </tr>
    <tr>
        <td>作成日</td>
        <td><?= $this->escape($menu['created_at']); ?></td>
    </tr>
    <tr>
        <td>更新日</td>
        <td><?= $this->escape($menu['updated_at']); ?></td>
    </tr>
    <tr>
        <td>お気に入り獲得数</td>
        <td><?= $this->escape($count_favorite); ?></td>
    </tr>
    <?php if($session->isAuthenticated()): ?>
    <tr>
        <td>お気に入り</td>
        <?php if($favorite === true): ?>
            <td>◯</td>
            <td>
                <form action="<?= $base_url; ?>/favorite/delete/<?= $this->escape($menu['id']); ?>" method="POST">
                    <input type="submit" value="外す">
                    <input type="hidden" name="_token" value="<?= $this->escape($_token); ?>">
                </form>   
            </td>
        <?php elseif($favorite === false): ?>
            <td>×</td>
            <td>
                <form action="<?= $base_url; ?>/favorite/new/<?= $this->escape($menu['id']); ?>" method="POST">
                    <input type="submit" value="登録する">
                    <input type="hidden" name="_token" value="<?= $this->escape($_token); ?>">
                </form>   
            </td>
        <?php endif; ?> 
    </tr>
    <?php endif; ?> 
</table>

<h3>コメント一覧</h3>

<?php if(count($comments) === 0): ?>
    <p>＊コメントはありません</p>
<?php else: ?>
    <table>
        <tr>
            <td>ユーザー</td>
            <td>本文</td>
            <td>投稿日</td>
        </tr>
        <?php foreach($comments as $comment): ?>
        <tr>
            <td><?= $this->escape($comment['user_name']); ?></td>
            <td><?= $this->escape($comment['body']); ?></td>
            <td><?= $this->escape($comment['created_at']); ?></td>
            <?php if($comment['user_id'] === $_SESSION['user']['id']): ?>
                <td>
                    <form action="<?= $base_url; ?>/comment/delete/<?= $this->escape($comment['id']); ?>" method="POST">
                        <input class="delete" type="submit" value="x">
                        <input type="hidden" name="_token" value="<?= $this->escape($_token); ?>">
                    </form>   
            <?php endif; ?>
        </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

<h3>コメント入力</h3>

<?php if(isset($_SESSION['comment_error'])): ?>
    <p><?= $this->escape($_SESSION['comment_error']); ?></p>
<?php endif; ?>

<form action="<?= $base_url; ?>/comment/new/<?= $this->escape($menu['id']); ?>" method="POST">
    <textarea name="comment" placeholder="コメント入力欄"></textarea><br>
    <input type="submit" value="コメントする">
    <input type="hidden" name="_token" value="<?= $this->escape($_token); ?>">
</form>
<?php endif; ?>