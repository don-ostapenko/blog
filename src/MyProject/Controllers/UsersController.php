<?php

namespace MyProject\Controllers;

use MyProject\Exceptions\ActivateException;
use MyProject\Exceptions\InvalidArgumentException;
use MyProject\Models\Users\User;
use MyProject\Models\Users\UserActivationService;
use MyProject\Services\EmailSender;
use MyProject\Services\UsersAuthService;

class UsersController extends AbstractController
{
    // Экшен регистрации нового пользователя
    public function signUp()
    {
        // При выполнении срипта, в первую очередь проверяем наличие данных в POST-переменной (если данных НЕТ - подключаем сразу шаблон templates/users/signUp.php в конеце нашего экшена)
        // если данные ЕСТЬ - проваливаемся внутрь условия
        if (!empty($_POST)) {
            try {
                // Выполняем статический метод signUp у класса User (аргументом, передаем массив данных полученные формой на шаблоне templates/users/signUp.php)
                $user = User::signUp($_POST);
            } catch (InvalidArgumentException $e) {
                $this->view->renderHtml('users/signUp.php', ['error' => $e->getMessage(), 'title' => 'Регистрация']);
                return;
            }

            // Если регистрация прошла успешно и новый пользователь явзяется часть класса User, то создается код активации по e-mail
            if ($user instanceof User) {
                $code = UserActivationService::createActivationCode($user);

                EmailSender::send($user, 'Активация', 'userActivation.php', [
                    'userId' => $user->getId(),
                    'code' => $code,
                    'title' => 'Активация пользователя'
                ]);

                $this->view->renderHtml('users/signUpSuccessful.php', ['title' => 'Регистрация']);
                return;
            }
        }

        $this->view->renderHtml('users/signUp.php', ['title' => 'Регистрация']);
    }

    // Экшен активации пользователя по e-mail
    public function activate(int $userId, string $activationCode)
    {
        try {
            $user = User::getById($userId);
            if ($user === null) {
                throw new ActivateException('Нет такого пользователя');
            }

            if ($user->getIsConfirmed()) {
                throw new ActivateException('E-mail уже активирован');
            }

            $isCodeValid = UserActivationService::checkActivationCode($user, $activationCode);

            if ($isCodeValid) {
                $user->activate();
                UserActivationService::deleteActivationCode($userId, $activationCode);
                $this->view->renderHtml('mail/successUserActivation.php', ['title' => 'Активация пользователя']);
            } else {
                throw new ActivateException('Код активации не существует');
            }
        } catch (ActivateException $e) {
            $this->view->renderHtml('errors/userActivationError.php', ['error' => $e->getMessage(), 'title' => 'Активация пользователя']);
            return;
        }
    }

    // Экшен авторизациии пользователя
    public function login()
    {
        if (!empty($_POST)) {
            try {
                // Если все проверки внутри метода "login" пройдены то метод возвращает нам массив пользователя
                $user = User::login($_POST);
                // Создаем токен авторизации и устанавливаем куки в браузере пользователя
                UsersAuthService::createToken($user);
                // Редиректим на главную страницу
                header('Location: ' . $_POST['link']);
                exit();
            } catch (InvalidArgumentException $e) {
                $this->view->renderHtml('users/login.php', ['error' => $e->getMessage(), 'title' => 'Авторизация']);
                return;
            }
        }

        $this->view->renderHtml('users/login.php', ['title' => 'Авторизация']);
    }

    // Экшен деавторизации пользователя
    public function logOut()
    {
        setcookie('token', '', -10, '/', '', false, true);
        header('Location: /');
    }
}