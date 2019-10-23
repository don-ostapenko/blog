<?php include __DIR__ . '/../header.php'; ?>
<div class="row d-flex justify-content-center align-items-center">
    <?php foreach ($articles as $article): ?>
        <article class="col-xl-8 post">
            <?php if (!$article->getImgName()): ?>
                <div class="post_img" style="background-image: url(http://work.loc/img/1.jpg)"></div>
            <?php else: ?>
                <div class="post_img" style="background-image: url(http://work.loc/uploads/img/<?= $article->getImgName() ?>.jpg)"></div>
            <?php endif; ?>
                <!--<img src="/img/1.jpg" alt="Изображение статьи">-->
            <div class="post_content">
                <div class="post_title">
                    <h2><a href="/articles/<?= $article->getId() ?>"><?= $article->getName() ?></a></h2>
                </div>
                <div class="post_text">
                    <p><?= $article->getText() ?></p>
                </div>
                <div class="post_link">
                    <a href="/articles/<?= $article->getId() ?>">Read More</a>
                </div>
            </div>
        </article>
    <?php endforeach; ?>
</div>

<?php include __DIR__ . '/../footer.php'; ?>