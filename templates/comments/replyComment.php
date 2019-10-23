<?php include __DIR__ . '/../header.php'; ?>
    <h1>Редактирование комментария</h1>
<?php if(!empty($error)): ?>
    <div style="color: red;"><?= $error ?></div>
<?php endif; ?>
    <form action="/articles/<?= $article->getId() ?>/comments/<?= $comment->getId() ?>/reply" method="post">
        <label for="name">Ваше имя</label>
        <br>
        <input id="name" type="text" name="name" value="<?= $_POST['name'] ?? '' ?>">
        <br>
        <br>
        <label for="text">Ваш комментарий</label>
        <br>
        <textarea id="text" name="text" rows="10" cols="80"><?= $_POST['text'] ?? '' ?></textarea>
        <br>
        <br>
        <input type="submit" value="Ответить">
    </form>
    </form>
<?php include __DIR__ . '/../footer.php'; ?>