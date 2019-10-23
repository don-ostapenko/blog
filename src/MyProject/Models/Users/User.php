<?php

namespace MyProject\Models\Users;

use MyProject\Models\ActiveRecordEntity;
use MyProject\Exceptions\InvalidArgumentException;

class User extends ActiveRecordEntity
{
    // Свойства объекта
    /** @var string */
    protected $nickname;

    /** @var string */
    protected $email;

    /** @var int */
    protected $isConfirmed;

    /** @var string */
    protected $role;

    /** @var string */
    protected $passwordHash;

    /** @var string */
    protected $authToken;

    /** @var string */
    protected $createdAt;


    // Геттеры

    /**
     * @return string
     */
    public function getNickname(): string
    {
        return $this->nickname;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getIsConfirmed(): string
    {
        return $this->isConfirmed;
    }

    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    public function getAuthToken(): string
    {
        return $this->authToken;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    protected static function getTableName(): string
    {
        return 'users';
    }

    //Метод для проверки, является ли пользователь админом
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    // Метод для генерации нового токена авторизации (используется после каждого входа)
    public function refreshAuthToken()
    {
        $this->authToken = sha1(random_bytes(100)) . sha1(random_bytes(100));
    }


    // Метод для регистрации пользователя
    public static function signUp(array $userData)
    {
        // Обрабатываем поле "nickname" формы регистрации
        if (empty($userData['nickname'])) {
            throw new InvalidArgumentException('Не передан nickname');
        }

        if (!preg_match('/[a-zA-Z0-9]+/', $userData['nickname'])) {
            throw new InvalidArgumentException('Nickname может состоять только из символов латинского алфавита и цифр');
        }

        if (static::findOneByColumn('nickname', $userData['nickname']) !== null) {
            throw new InvalidArgumentException('Пользователь с таким nickname уже существует');
        }


        // Обрабатываем поле "email" формы регистрации
        if (empty($userData['email'])) {
            throw new InvalidArgumentException('Не передан email');
        }

        if (!filter_var($userData['email'], FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Email некорректен');
        }

        if (static::findOneByColumn('email', $userData['email']) !== null) {
            throw new InvalidArgumentException('Пользователь с таким email уже существует');
        }


        // Обрабатываем поле "password" формы регистрации
        if (empty($userData['password'])) {
            throw new InvalidArgumentException('Не передан password');
        }

        if (mb_strlen($userData['password']) < 8) {
            throw new InvalidArgumentException('Пароль должен быть не менее 8 символов');
        }


        $user = new User();
        $user->nickname = $userData['nickname'];
        $user->email = $userData['email'];
        $user->passwordHash = password_hash($userData['password'], PASSWORD_DEFAULT);
        $user->isConfirmed = false;
        $user->role = 'user';
        $user->authToken = sha1(random_bytes(100)) . sha1(random_bytes(100));
        $user->save();

        return $user;
    }

    // Метод для активации пользователя по e-mail (вносит в таблицу БД значение true (т.е. 1))
    public function activate(): void
    {
        $this->isConfirmed = true;
        $this->save();
    }

    // Метод для авторизации пользователя
    public static function login(array $loginData): User
    {
        // Проверяем поле "email", что данные переданы
        if (empty($loginData['email'])) {
            throw new InvalidArgumentException('Не передан email');
        }

        // Проверяем поле "password", что данные переданы
        if (empty($loginData['password'])) {
            throw new InvalidArgumentException('Не передан пароль');
        }

        // Проверяем поле "email", что переданный e-mail есть в базе
        $user = User::findOneByColumn('email', $loginData['email']);
        if ($user === null) {
            throw new InvalidArgumentException('Нет пользователя с таким email');
        }

        // Проверяем поле "password" на соответствие свойству passwordHash
        if (!password_verify($loginData['password'], $user->getPasswordHash())) {
            throw new InvalidArgumentException('Неправильный пароль');
        }

        // Проверяем пользователя на подтвержденность e-mail
        if (!$user->isConfirmed) {
            throw new InvalidArgumentException('Пользователь не подтверждён');
        }

        // Перезаписываем токен авторизации
        $user->refreshAuthToken();
        $user->save();

        return $user;
    }
}