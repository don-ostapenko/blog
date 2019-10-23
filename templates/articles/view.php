<?php include __DIR__ . '/../header.php'; ?>
    <div class="row d-flex justify-content-center align-items-center">
        <article class="col-xl-8 post">
        <?php if (!$article->getImgName()): ?>
            <div class="post_img" style="background-image: url(http://work.loc/img/1.jpg)"></div>
        <?php else: ?>
            <div class="post_img" style="background-image: url(http://work.loc/uploads/img/<?= $article->getImgName() ?>.jpg)"></div>
        <?php endif; ?>
            <div class="post_content">
                <div class="post_title">
                    <h1><?= $article->getName() ?></h1>
                </div>
                <div class="post_text">
                    <p><?= $article->getParsedText() ?></p>
                </div>
                <div class="post_author">
                    <p>Автор: <?= $article->getAuthor()->getNickname() ?></p>
                </div>

                <?php if (!empty($user) && $user->isAdmin()): ?>
                    <a href="/articles/<?= $article->getId() ?>/edit" class="btn btn-link">Редактировать статью</a>
                    <a href="/articles/<?= $article->getId() ?>/delete" class="btn btn-link">Удалить статью</a>
                <?php endif; ?>
            </div>


            <?php if (!empty($user)): ?>
                <br><br>
                <hr>
                <h2>Оставить комментарий</h2>
                <?php if (!empty($error)): ?>
                    <div style="background-color: red;padding: 5px;margin: 15px"><?= $error ?></div>
                <?php endif; ?>
                <form action="/articles/<?= $article->getId() ?>/comments" method="post">
                    <label for="name">Ваше имя</label>
                    <br>
                    <input id="name" type="text" name="name" value="<?= $_POST['email'] ?? '' ?>">
                    <br>
                    <br>
                    <label for="text">Ваш комментарий</label>
                    <br>
                    <textarea id="text" name="text" rows="10" cols="80"><?= $_POST['text'] ?? '' ?></textarea>
                    <br>
                    <br>
                    <input type="submit" value="Опубликовать">
                </form>
            <?php else: ?>
                <br><br>
                <hr>
                <p>Для добавления комментария <a href="/users/login">Авторизуйтесь</a> или <a href="/users/register">Зарегистрируйтесь</a>
                </p>
            <?php endif; ?>
            <br><br>
            <hr>
            <h2>Комментарии</h2>

            <?php if (!empty($comments)): ?>

                <div class="comments_wrap">
                    <ul class="not-parent">
                        <?= $finishedTree ?>
                    </ul>
                </div>

            <?php else: ?>
                <p>К данной статье нет ни одного комментария</p>
            <?php endif; ?>
        </article>

<?php include __DIR__ . '/../footer.php'; ?>