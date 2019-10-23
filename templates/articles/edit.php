<?php include __DIR__ . '/../header.php'; ?>
    <div class="row d-flex justify-content-center align-items-center">
        <article class="col-xl-8 post">

            <h1>Редактирование статьи</h1>

            <?php if(!empty($error)): ?>
                <div class="alert alert-danger" role="alert"><?= $error ?></div>
            <?php endif; ?>

            <form action="/articles/<?= $article->getId() ?>/edit" enctype="multipart/form-data" method="post">
                <div class="form-group">
                    <label for="name">Название статьи</label>
                    <br>
                    <input type="text" class="form-control" name="name" id="name" value="<?= $_POST['name'] ?? $article->getName() ?>" size="50">
                    <br>
                    <label for="text">Текст статьи</label>
                    <br>
                    <textarea class="form-control" name="text" id="text" rows="10" cols="80"><?= $_POST['text'] ?? $article->getText() ?></textarea>
                    <br>
                    <label for="text">Изображение статьи</label>
                    <input class="form-control-file" type="file" name="img">
                </div>

                <input class="btn btn-primary" type="submit" value="Обновить">
                <a href="<?= $_SERVER['HTTP_REFERER'] ?>" class="btn btn-link">Отмена</a>
            </form>
        </article>
    </div>

<?php include __DIR__ . '/../footer.php'; ?>