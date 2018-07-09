<?php

    require_once('./smsimple.config.php');
    require_once('./smsimple.class.php');

    header('Content-type: text/html; charset=utf-8');

    $sms = new SMSimple(array(
        'url' => SMS_API,
        'username' => SMS_USERNAME,
        'password' => SMS_PASSWORD,
    ));

    try {

        $error = false;
        $sms->connect();

        if ($_SERVER['REQUEST_METHOD'] == 'POST')
            $arr = $_POST;
        else
            $arr = $_GET;
        if ($arr['title'])
            $result = $sms->originOrder($arr['title']);

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

    <h2>Добавление подписи</h2>
    
    <? if ($result) : ?>
        <u>Подпись "<?= $arr['title'] ?>" заказана. Ожидайте подтверждения.</u>
    <? endif ?>

    <? if (!$error) : ?>
        <form method="get" action="">
            <dl>
                <dt><b>Новая подпись</b></dt>
                <dd>
                    <input type="text" name="title" size="11" value=""/><br />
                </dd>
                <dt>&nbsp;</dt>
                <dd>
                    <input type="submit" value="Заказать подпись"/>
                </dd>
            </dl>
        </form>
    <? else : ?>
        Ошибка: <?= $message ?>
    <? endif ?>

</body>
</html>