<ul>
<?php foreach($messages as $message): ?>
  <li><?= $this->escape($message); ?></li>
<?php endforeach; ?>
</ul>
