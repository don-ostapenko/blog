<?php include __DIR__ . '/../header.php'; ?>
    <div class="row d-flex justify-content-center align-items-center">
        <article class="col-xl-8 post">

            <h1>Создание новой статьи</h1>

            <?php if(!empty($error)): ?>
                <div class="alert alert-danger" role="alert"><?= $error ?></div>
            <?php endif; ?>

            <form action="/articles/add" enctype="multipart/form-data" method="post">
                <div class="form-group">
                    <label for="name">Название статьи</label>
                    <br>
                    <input class="form-control" type="text" name="name" id="name" value="<?= $_POST['name'] ?? '' ?>" size="50">
                    <br>
                    <br>
                    <label for="text">Текст статьи</label>
                    <br>
                    <textarea class="form-control" name="text" id="text" rows="10" cols="80"><?= $_POST['text'] ?? '' ?></textarea>
                    <br>
                    <label for="text">Изображение статьи</label>
                    <br>
                    <input class="form-control-file" type="file" name="img">
                </div>

                <input class="btn btn-primary" type="submit" value="Создать">
                <a href="<?= $_SERVER['HTTP_REFERER'] ?>" class="btn btn-link">Отмена</a>
            </form>

        </article>
    </div>
<?php include __DIR__ . '/../footer.php'; ?>
