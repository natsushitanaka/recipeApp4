<?php $this->setLayoutvar('title', 'メニュー詳細')?>

<a href="<?= $base_url; ?>/">ホーム</a>

<table>
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
        <td><?php if($menu['openRange'] === '1'){echo '公開';}else{echo '非公開';} ?></td>
    </tr>
    <tr>
        <td>作成日</td>
        <td><?= $this->escape($menu['created_at']); ?></td>
    </tr>
    <tr>
        <td>更新日</td>
        <td><?= $this->escape($menu['updated_at']); ?></td>
    </tr>

</table>
