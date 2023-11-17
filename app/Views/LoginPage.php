<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="<?php base_url()?>/assets/common/css/style.css">
</head>

<body>
    <div class="login-form-container">
        <form class="login-form" action="/LoginController/login" method="POST">
            <table class="login-form__layout">
                <tr>
                    <td colspan="2">
                        <img class="login-form__logo" src="<?php base_url()?>/assets/img/logocourse.png" alt="Logo">
                    </td>
                </tr>
                <tr>
                    <td>Tài khoản</td>
                    <td>
                        <input class="login-form__account-input" type="text" placeholder="" name="account" id="1">

                    </td>
                </tr>
                <tr>
                    <td>Mật khẩu</td>
                    <td>
                        <input class="login-form__password-input" type="password" name="password" id="2">
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input class="login-form__submit" type="submit" name="login" value="Đăng nhập">
                    </td>
                </tr>
            </table>
            <?php
            if (isset($validator)) {
                echo $validator->listErrors();
            }
            ?>
            <?php
            if (isset($login_failed)) {
                echo $login_failed;
            }
            ?>
        </form>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>