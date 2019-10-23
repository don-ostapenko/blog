<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="/css/styles.css">
    <link rel="shortcut icon" type="image/x-icon" href="/img/favicon2.png"/>
    <meta name="theme-color" content="#ff3239">
    <title><?= $title ?? 'Мой блог' ?></title>

</head>
<body>
<header>
    <div class="container-fluid shadow">
        <div class="container">
            <nav class="navbar navbar-expand-lg px-0 py-3 d-flex justify-content-between align-items-center">

                <a href="/" class="navbar-barnd mr-4">
                    <img src="/img/logo.png" alt="Logo" width="150">
                </a>

                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span><img class="no-focus" src="/img/menu.svg" alt="menu" width="35"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">

                    <ul class="navbar-nav mr-auto rd">
                        <li class="nav-item active">
                            <a href="/" class="nav-link">Main</a>
                        </li>
                        <li class="nav-item">
                            <a href="/about-us" class="nav-link">About us</a>
                        </li>
                        <?php if (!empty($user) && $user->isAdmin()): ?>
                            <li class="nav-item">
                                <a href="/admin" class="nav-link">Admin</a>
                            </li>
                        <?php endif; ?>

                        <?php if (!empty($user)): ?>
                            <li class="nav-item">
                                <span class="hello-user">Hello, <?= $user->getNickname() ?></span>
                            </li>
                            <li class="nav-item">
                                <a class="btn btn-outline-primary" href="/users/logOut">Log Out</a>
                            </li>
                        <?php else: ?>
                            <li class="nav-item">
                                <a class="btn btn-link mr" href="/users/login">Log In</a>
                            </li>
                            <li class="nav-item">
                                <a class="btn btn-outline-primary" href="/users/register">Sign Up</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>

            </nav>
        </div>
    </div>
</header>
<table class="layout">
    <tr>
        <td style="text-align: center">