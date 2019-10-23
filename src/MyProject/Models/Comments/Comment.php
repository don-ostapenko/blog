<?php

namespace MyProject\Models\Comments;

use MyProject\Models\ActiveRecordEntity;
use MyProject\Models\Articles\Article;
use MyProject\Models\Users\User;
use MyProject\Exceptions\InvalidArgumentException;

class Comment extends ActiveRecordEntity
{
    // Свойства

    /** @var string */
    protected $commentAuthorId;

    /** @var string */
    protected $commentArticleId;

    /** @var string */
    protected $commentText;

    /** @var string */
    protected $commentPublicationDate;

    /** @var string */
    protected $commentName;

    /** @var string */
    protected $parentId;


    // Сеттеры

    public function setCommentAuthorId(string $commentAuthorId)
    {
        $this->commentAuthorId = $commentAuthorId;
    }

    public function setCommentArticleId(string $commentArticleId)
    {
        $this->commentArticleId = $commentArticleId;
    }

    public function setCommentText(string $commentText)
    {
        $this->commentText = $commentText;
    }

    public function setCommentName(string $commentName)
    {
        $this->commentName = $commentName;
    }

    public function setParentId(string $parentId)
    {
        $this->parentId = $parentId;
    }


    // Геттеры

    public function getCommentAuthorId(): string
    {
        return $this->commentAuthorId;
    }

    public function getCommentText(): string
    {
        return $this->commentText;
    }

    public function getCommentName(): string
    {
        return $this->commentName;
    }

    public function getCommentArticleId(): string
    {
        return $this->commentArticleId;
    }

    public function getParentId(): ?string
    {
        return $this->parentId;
    }

    public function getCommentPublicationDate(): string
    {
        return $this->commentPublicationDate;
    }


    /**
     * @return User
     */
    public function getCommentAuthor(): User
    {
        return User::getById($this->commentAuthorId);
    }


    // Метод для добавления комментария к статье
    public static function addNewComment(array $fields, User $author, Article $article): Comment
    {
        if (empty($fields['name'])) {
            throw new InvalidArgumentException('Введите ваше имя');
        }

        if (empty($fields['text'])) {
            throw new InvalidArgumentException('Введите текст комментария');
        }

        $comment = new Comment();

        $comment->setCommentAuthorId($author->getId());
        $comment->setCommentArticleId($article->getId());
        $comment->setCommentName($fields['name']);
        $comment->setCommentText($fields['text']);

        $comment->save();
        return $comment;
    }


    // Метод для редактирования комментария
    public function updateComment(array $fields): Comment
    {
        if (empty($fields['name'])) {
            throw new InvalidArgumentException('Не передано имя');
        }

        if (empty($fields['text'])) {
            throw new InvalidArgumentException('Не передан текст комментария');
        }

        $this->setCommentName($fields['name']);
        $this->setCommentText($fields['text']);

        $this->save();

        return $this;
    }


    // Метод для добавления комментария (ответа) на комментарий
    public static function replyComment(array $fields, User $author, Article $article, Comment $parentId): Comment
    {
        if (empty($fields['name'])) {
            throw new InvalidArgumentException('Введите ваше имя');
        }

        if (empty($fields['text'])) {
            throw new InvalidArgumentException('Введите текст комментария');
        }

        $comment = new Comment();

        $comment->setCommentAuthorId($author->getId());
        $comment->setCommentArticleId($article->getId());
        $comment->setCommentName($fields['name']);
        $comment->setCommentText($fields['text']);
        $comment->setParentId($parentId->getId());

        $comment->save();
        return $comment;
    }

    // Метод для превращения массива объектов в массив массивов и назначение ключам массива id-комметария
    public static function sortArrayComments(array $comments): array
    {
        // Назначаем ключам массива id-комметария
        for ($i = 0, $c = count($comments); $i < $c; $i++) {
            $newComments[$comments[$i]->getId()] = $comments[$i];
        }

        // Перегоняем массив объектов в массив массива
        $array = [];
        foreach ($comments as $object) {
            $array[$object->getId()] = [
                'commentAuthorNickname' => $object->getCommentAuthor()->getNickname(),
                'commentAuthorId' => $object->getCommentAuthorId(),
                'commentArticleId' => $object->getCommentArticleId(),
                'commentText' => $object->getCommentText(),
                'commentPublicationDate' => $object->getCommentPublicationDate(),
                'commentName' => $object->getCommentName(),
                'parentId' => $object->getParentId(),
                'id' => $object->getId(),
            ];
        }

        // Формируем иерархичное дерево (когда комметарий потомок лежит внутри родителя)
        $tree = [];
        foreach ($array as $id => &$comment) {
            if (empty($comment['parentId'])) {
                $tree[$id] = &$comment;
            } else {
                $array[$comment['parentId']]['children'][$id] = &$comment;
            }
        }
        return $tree;
    }

    static function getTableName(): string
    {
        return 'comments';
    }

}