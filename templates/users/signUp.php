<?php include __DIR__ . '/../header.php'; ?>
    <div style="text-align: center;">
        <h1>Регистрация</h1>
        <?php if (!empty($error)): ?>
            <div style="background-color: red; padding: 5px; margin: 15px; color: #ffffff;"><?= $error ?></div>
        <?php endif; ?>
        <form action="/users/register" method="post">
            <label for="nickname">Nickname</label>
            <br>
            <input id="nickname" type="text" name="nickname" value="<?= $_POST['nickname'] ?? '' ?>">
            <br>
            <br>
            <label for="e-mail">Email</label>
            <br>
            <input id="e-mail" type="text" name="email" value="<?= $_POST['email'] ?? '' ?>">
            <br>
            <br>
            <label for="password">Пароль</label>
            <br>
            <input id="password" type="password" name="password">
            <br>
            <a href="#" id="s-h-pass">Показать пароль</a>
            <br>
            <br>
            <input type="submit" value="Зарегистрироваться">
        </form>
    </div>
<?php include __DIR__ . '/../footer.php'; ?>