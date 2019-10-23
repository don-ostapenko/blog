<?php

namespace MyProject\Controllers;

use MyProject\Models\Articles\Article;

class MainController extends AbstractController
{
    public function main() {
        $articles = Article::findAll();
        $this->view->renderHtml('main/main.php', [
            'articles' => $articles,
            'title' => 'Главная страница'
        ]);
    }

    public function error404() {
        $this->view->renderHtml('errors/404.php', ['title' => '404'], 404);
    }

}