<?php

return [

    // Роут для главной страницы
    '~^$~' => [\MyProject\Controllers\MainController::class, 'main'],



    // Роут для вывода отдельной статьи на сайт
    '~^articles/(\d+)$~' => [\MyProject\Controllers\ArticlesController::class, 'view'],

    // Роут для добавления статьи
    '~^articles/add$~' => [\MyProject\Controllers\ArticlesController::class, 'add'],

    // Роут для редактирования статьи
    '~^articles/(\d+)/edit$~' => [\MyProject\Controllers\ArticlesController::class, 'edit'],

    // Роут для удаления конкретной статьи
    '~^articles/(\d+)/delete$~' => [\MyProject\Controllers\ArticlesController::class, 'delete'],



    // Роут для добавления комментария к статье
    '~^articles/(\d+)/comments$~' => [\MyProject\Controllers\ArticlesController::class, 'addComment'],

    // Роут при успешном добавлении комментария к статье
    '~^articles/(\d+)#comment(\d+)$~' => [\MyProject\Controllers\ArticlesController::class, 'view'],

    // Роут для редактирования комментария
    '~^comments/(\d+)/edit$~' => [\MyProject\Controllers\ArticlesController::class, 'editComment'],

    // Роут для удаления комментария к статье
    '~^comments/(\d+)/delete$~' => [\MyProject\Controllers\ArticlesController::class, 'deleteComment'],

    // Роут для ответа на комментарий
    '~^articles/(\d+)/comments/(\d+)/reply$~' => [\MyProject\Controllers\ArticlesController::class, 'replyToComment'],



    // Роут для регистрации нового пользователя
    '~^users/register$~' => [MyProject\Controllers\UsersController::class, 'signUp'],

    // Роут для активация пользователя по e-mail
    '~^users/(\d+)/activate/(.+)$~' => [\MyProject\Controllers\UsersController::class, 'activate'],

    // Роут для авторизации пользователя
    '~^users/login$~' => [\MyProject\Controllers\UsersController::class, 'login'],

    // Роут для деавторизации пользователя
    '~^users/logOut$~' => [\MyProject\Controllers\UsersController::class, 'logOut'],



    // Роут для доступа к админке
    '~^admin$~' => [\MyProject\Controllers\AdminController::class, 'admin'],

    // Роут для страница "Список статей" (раздел админ-панель)
    '~^admin/articles$~' => [\MyProject\Controllers\AdminController::class, 'getAllArticles'],

    // Роут для редактирования статьи
    '~^admin/articles/(\d+)/edit$~' => [\MyProject\Controllers\ArticlesController::class, 'edit'],
];