<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title><?php if(isset($title)): echo $title . '-'; endif; ?>My Recipes</title>
</head>
<body>
  <div>
    <?= $_content; ?>
  </div>
</body>
</html>
