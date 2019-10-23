<?php include __DIR__ . '/../header.php'; ?>
<table>
    <tr>
        <th>Id</th>
        <th>Автор</th>
        <th>Название статьи</th>
        <th>Краткий текст статьи</th>
        <th>Дата публикации</th>
        <th>Действия</th>
    </tr>
    <?php foreach ($articles as $article): ?>
    <tr>
        <td><?= $article->getId() ?></td>
        <td><?= $article->getAuthor()->getNickname() ?></td>
        <td><?= $article->getName() ?></td>
        <td><?= $article->getText() ?></td>
        <td><?= $article->getCreatedAt() ?></td>
        <td><a href="/admin/articles/<?= $article->getId() ?>/edit">Редактировать</a></td>
    </tr>
    <?php endforeach; ?>
</table>
<?php include __DIR__ . '/../footer.php'; ?>
