<?php

    require_once('./smsimple.config.php');
    require_once('./smsimple.class.php');

    header('Content-type: text/html; charset=utf-8');

    $sms = new SMSimple(array(
        'url' => SMS_API,
        'username' => SMS_USERNAME,
        'password' => SMS_PASSWORD,
    ));

    $message = '';
    $error = false;

    try {
        $sms->connect();
        $profile = $sms->get_profile();
    }
    catch (SMSimpleException $e) {
        $error = true;
        $message = $e->getMessage();
    }

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
    <title>SMSimple API</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <link href="style.css" rel="stylesheet" type="text/css"/>
</head>
<body>

    <h2>Мой профиль</h2>

    <? if ($message) : ?>
        <p class="red"><?= htmlspecialchars($message) ?></p>
    <? endif ?>

    <? if ($profile) : ?>
        <table class="properties">
            <tr><th>Кредитный лимит:</th><td><?= $profile['leverate'] ?> sms</td></tr>
            <tr><th>Зарезервировано:</th><td><?= $profile['reserved'] ?> sms</td></tr>
            <tr><th>Баланс:</th><td><?= $profile['balance'] ?> sms</td></tr>
            <tr><th>Логин:</th><td><?= $profile['username'] ?></td></tr>
            <tr><th>Электронная почта:</th><td><?= htmlspecialchars($profile['email']) ?></td></tr>
            <tr><th>Контактный телефон:</th><td><?= htmlspecialchars($profile['phone']) ?></td></tr>
        </table>
    <? endif ?>

</body>
</html>