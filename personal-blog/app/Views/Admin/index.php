<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Admin dashboard</title>
</head>

<body>
  <header>
    <h1>Personal Blog</h1>
    <a href="/new">+ Add</a>
  </header>
  <main>
    <?php if (!empty($data)) : ?>
      <?php foreach ($data as $d) : ?>
        <div>
          <a href="/blog/<?= $d->id ?>"> <?= $d->title ?> <?= $d->date ?></a>
          <div>
            <a href="/edit/<?= $d->id ?>">edit</a>
            <form action="/delete/<?= $d->id ?>" method="post" onsubmit="return confirm('Delete article?');">
              <button type="submit">delete</button>
            </form>
          </div>
        </div>
        <br />
      <?php endforeach; ?>
    <?php else : ?>
      <p>
        no articles yet
      </p>
    <?php endif; ?>
  </main>
</body>

</html>
