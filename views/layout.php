<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title><?php if(isset($title)): echo $title . '-'; endif; ?>My Recipes</title>
  <link href="/style.css" rel="stylesheet" type="text/css">
</head>
<body>
  <div>
    <?= $_content; ?>
  </div>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="/main.js"></script>
</body>
</html>
