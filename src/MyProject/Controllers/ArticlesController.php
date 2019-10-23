<?php

namespace MyProject\Controllers;

use MyProject\Exceptions\InvalidArgumentException;
use MyProject\Exceptions\NotFoundException;
use MyProject\Models\Articles\Article;
use MyProject\Exceptions\UnauthorizedException;
use MyProject\Exceptions\ForbiddenException;
use MyProject\Models\Comments\Comment;
use MyProject\Exceptions\NotFoundCommentException;

class ArticlesController extends AbstractController
{
    // Экшен для вывода статей на сайт (а также вывод комментов к статье)
    public function view(int $articleId): void
    {
        $comments = Comment::showCommentsByArticleId($articleId);
        $article = Article::getById($articleId);
        $arrayComments = Comment::sortArrayComments($comments);
        $finishedTree = $this->getComments($arrayComments);

        if ($article === null) {
            throw new NotFoundException();
        }

        $this->view->renderHtml('articles/view.php', [
            'article' => $article,
            'title' => $article->getName(),
            'comments' => $comments,
            'arrayComments' => $arrayComments,
            'finishedTree' => $finishedTree,
        ]);
    }

    // Экшен для рендеринга шаблона комментариев используемый экшеном view
    public function getComments(array $finishedTree): string {
        $html = '';
        foreach ($finishedTree as $comment) {
            ob_start();
            $this->view->renderHtml('comments/commentsTemplate1.php', ['comment' => $comment]);
            include __DIR__ . '/../../../templates/comments/commentsTemplate2.php';
            $html .= ob_get_clean();
        }
        return $html;
    }

    // Экшен для добавления статьи в блог
    public function add(): void
    {
        if ($this->user === null) {
            throw new UnauthorizedException();
        }

        if (!$this->user->isAdmin()) {
            throw new ForbiddenException('Для добавления статьи нужно обладать правами администратора');
        }

        if (!empty($_POST)) {
            try {
                $article = Article::createFromArray($_POST, $this->user, $_FILES);
            } catch (InvalidArgumentException $e) {
                $this->view->renderHtml('articles/add.php', ['error' => $e->getMessage()]);
                return;
            }
            header('Location: /articles/' . $article->getId(), true, 302);
            exit();
        }
        $this->view->renderHtml('articles/add.php', [
            'title' => 'Создание новой статьи'
        ]);
    }

    // Экшен для редактирования статьи блога
    public function edit(int $articleId): void
    {
        $article = Article::getById($articleId);

        if ($article === null) {
            throw new NotFoundException();
        }

        if ($this->user === null) {
            throw new UnauthorizedException();
        }

        if (!$this->user->isAdmin()) {
            throw new ForbiddenException('Для редактирования статьи нужно обладать правами администратора');
        }

        if (!empty($_POST)) {
            try {
                $article->updateFromArray($_POST, $_FILES);
            } catch (InvalidArgumentException $e) {
                $this->view->renderHtml('articles/edit.php', ['error' => $e->getMessage(), 'article' => $article]);
                return;
            }
            header('Location: /articles/' . $article->getId(), true, 302);
            exit();
        }
        $this->view->renderHtml('articles/edit.php', ['article' => $article]);
    }

    // Экшен для удаления статьи из блога
    public function delete(int $articleId): void
    {
        $article = Article::getById($articleId);

        if ($article === null) {
            throw new NotFoundException();
        }

        if ($this->user === null) {
            throw new UnauthorizedException();
        }

        if ($this->user->isAdmin()) {
            $article->delete();
            $this->view->renderHtml('success/successDelete.php');
        } else {
            throw new ForbiddenException('Для удаления статьи нужно обладать правами администратора');
        }
    }


    // Экшен для добавления комментария к статье
    public function addComment(int $articleId): void
    {
        if ($this->user === null) {
            throw new UnauthorizedException();
        }

        if (!empty($_POST)) {
            $comments = Comment::showCommentsByArticleId($articleId);
            $article = Article::getById($articleId);
            try {
                $comment = Comment::addNewComment($_POST, $this->user, $article);
            } catch (InvalidArgumentException $e) {
                $this->view->renderHtml('articles/view.php', [
                    'article' => $article,
                    'title' => $article->getName(),
                    'error' => $e->getMessage(),
                    'comments' => $comments,
                ]);
                return;
            }
            header('Location: /articles/' . $articleId . '#comment' . $comment->getId(), true, 302);
            exit();
        }
    }


    // Экшен для редактирования комментария
    public function editComment(int $commentId): void
    {
        $comment = Comment::getById($commentId);

        if ($comment === null) {
            throw new NotFoundCommentException();
        }

        if ($this->user === null) {
            throw new UnauthorizedException();
        }

        if ($this->user->getId() == $comment->getCommentAuthorId() || $this->user->isAdmin()) {
            if (!empty($_POST)) {
                try {
                    $comment->updateComment($_POST);
                } catch (InvalidArgumentException $e) {
                    $this->view->renderHtml('comments/editComment.php', [
                        'error' => $e->getMessage(),
                        'comment' => $comment,
                    ]);
                    return;
                }
                header('Location: /articles/' . $comment->getCommentArticleId() . '#comment' . $comment->getId(), true, 302);
                exit();
            }
        } else {
            throw new ForbiddenException('Для редактирования комментария нужно быть автором комментария или обладать правами администратора');
        }

        $this->view->renderHtml('comments/editComment.php', ['comment' => $comment]);
    }


    // Экшен для удаления комментария
    public function deleteComment(int $commentId): void
    {
        $comment = Comment::getById($commentId);

        if ($comment === null) {
            throw new NotFoundCommentException();
        }

        if ($this->user === null) {
            throw new UnauthorizedException();
        }

        if (/*$this->user->getId() == $comment->getCommentAuthorId() || */$this->user->isAdmin()) {
            $comment->delete();
            header('Location: /articles/' . $comment->getCommentArticleId(), true, 302);
        } else {
            throw new ForbiddenException('Для удаления комментария нужно обладать правами администратора');
        }
    }


    // Экшен для ответа на комментарий
    public function replyToComment(int $articleId, int $parentId): void
    {
        if ($this->user === null) {
            throw new UnauthorizedException();
        }

        //$comments = Comment::showCommentsById($articleId);
        $article = Article::getById($articleId);
        $parentComment = Comment::getById($parentId);

        if (!empty($_POST)) {
            try {
                $comment = Comment::replyComment($_POST, $this->user, $article, $parentComment);
            } catch (InvalidArgumentException $e) {
                $this->view->renderHtml('comments/replyComment.php', [
                    'article' => $article,
                    'title' => $article->getName(),
                    'error' => $e->getMessage(),
                    'comment' => $parentComment,
                ]);
                return;
            }

            header('Location: /articles/' . $articleId . '#comment' . $comment->getId(), true, 302);
            exit();
        }

        $this->view->renderHtml('comments/replyComment.php', [
            'article' => $article,
            'comment' => $parentComment,
        ]);
    }

}