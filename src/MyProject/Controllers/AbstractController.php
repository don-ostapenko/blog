<?php

namespace MyProject\Controllers;

use MyProject\Models\Users\User;
use MyProject\Services\UsersAuthService;
use MyProject\View\View;

abstract class AbstractController
{
    /** @var View */
    protected $view;

    /** @var User|null */
    protected $user;

    public function __construct()
    {
        // Получаем пользователя по токену авторизации
        $this->user = UsersAuthService::getUserByToken();
        // Создаем объект View
        $this->view = new View(__DIR__ . '/../../../templates');
        // Передаем нужные нам данные (которые будут распаковываться при рендеренге нужнного шаблона и будут там использоваться)
        $this->view->setVar('user', $this->user);
    }

    protected function getInputData()
    {
        return json_decode(file_get_contents('php://input'), true);
    }
}