 <!doctype html>
 <html lang="en">

 <head>
   <meta charset="UTF-8" />
   <meta name="viewport" content="width=device-width, initial-scale=1" />
   <title>Personal Blog</title>
 </head>

 <body>
   <header>
     <h1>Personal Blog</h1>
   </header>
   <main>
     <?php if (!empty($data)) : ?>
       <?php foreach ($data as $d) : ?>
         <a href="/blog/<?= htmlspecialchars($d->id) ?>"> <?= htmlspecialchars($d->title) ?> <?= htmlspecialchars($d->date) ?></a>
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
