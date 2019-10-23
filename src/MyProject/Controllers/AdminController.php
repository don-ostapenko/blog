<?php

namespace MyProject\Controllers;

use MyProject\Exceptions\ForbiddenException;
use MyProject\Exceptions\UnauthorizedException;
use MyProject\Models\Articles\Article;

class AdminController extends AbstractController
{
    public function __construct()
    {
        parent::__construct();
        if ($this->user === null) {
            throw new UnauthorizedException();
        }

        if (!$this->user->isAdmin()) {
            throw new ForbiddenException('Для доступа к админ-панели нужно обладать правами администратора');
        }
    }

    public function admin()
    {
        $this->view->renderHtml('admins/mainAdmin.php', []);
    }

    public function getAllArticles()
    {
        $articles = Article::findAll();
        $this->view->renderHtml('admins/allArticles.php', [
            'articles' => $articles,
            'title' => 'Список статей'
        ]);
    }

    protected static function getTableName(): string
    {
        return 'articles';
    }
}