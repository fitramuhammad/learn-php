<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Update Article</title>
</head>

<body>
  <header>
    <h1>Update Article</h1>
  </header>
  <main>
    <form method="post">
      <input type="text" placeholder="Article Title" name="title" value="<?= $data[0]->title ?>" />
      <input type="date" placeholder="Publishing Date" name="date" value="<?= new DateTime($data[0]->date)->format("Y-m-d") ?>" />
      <textarea name="content" placeholder="Content"><?= $data[0]->content ?></textarea>
      <button type=" submit">Update</button>
    </form>
  </main>
</body>

</html>
