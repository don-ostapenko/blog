<li>
    <div class="comment">
        <div class="author" id="comment<?= $comment['id'] ?>">
            <?= $comment['commentAuthorNickname'] ?>
            <span class="date"><?php $formatData = strtotime($comment['commentPublicationDate']); echo date('d.m.Y H:i', $formatData) ?></span>
        </div>

        <div class="comment_text"><?= $comment['commentText'] ?></div>

        <?php if (!empty($user)): ?>
        <div class="button">
            <a href="/articles/<?= $comment['commentArticleId'] ?>/comments/<?= $comment['id'] ?>/reply" class="button-item">Ответить</a>

            <?php if ($user->isAdmin() == true || $user->getId() == $comment['commentAuthorId']): ?>
            <a href="/comments/<?= $comment['id'] ?>/edit" class="button-item">Редактировать</a>
            <?php endif; ?>

            <?php if ($user->isAdmin() == true): ?>
            <a href="/comments/<?= $comment['id'] ?>/delete" class="button-item">Удалить</a>
            <?php endif; ?>


        </div>
        <?php endif; ?>


    </div>
