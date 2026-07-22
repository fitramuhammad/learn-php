<?php

$title = $data[0]->title;
$date = $data[0]->date;
$content = $data[0]->content;

?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?= htmlspecialchars($title) ?></title>
</head>

<body>
  <header>
    <h1><?= htmlspecialchars($title) ?></h1>
    <p><?= htmlspecialchars($date) ?></p>
  </header>
  <main>
    <p><?= htmlspecialchars($content) ?></p>
  </main>
</body>

</html>
