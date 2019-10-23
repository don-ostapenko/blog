<?php include __DIR__ . '/../header.php'; ?>
    <div style="text-align: center;">
        <h1>Вход</h1>
        <?php if (!empty($error)): ?>
            <div style="background-color: red;padding: 5px;margin: 15px"><?= $error ?></div>
        <?php endif; ?>
        <form action="/users/login" method="post">
            <label for="email">Email</label>
            <br>
            <input id="email" type="text" name="email" value="<?= $_POST['email'] ?? '' ?>">
            <br><br>
            <label for="password">Пароль</label>
            <br>
            <input id="password" type="password" name="password" value="<?= $_POST['password'] ?? '' ?>">
            <br>
            <a href="#" id="s-h-pass">Показать пароль</a>
            <br><br>
            <input type="submit" value="Войти">
            <input type="hidden" name="link" value="<?= $_SERVER['HTTP_REFERER']; ?>">
        </form>
    </div>
<?php include __DIR__ . '/../footer.php'; ?>