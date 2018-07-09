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

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $sms->ussd_session_start($_POST['phone'],'любые дополнительные данные к сессии до 200 символов',$_POST['encoding']);
        }

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

    <h2>USSD сессия: игра &laquo;Угадай число&raquo;</h2>
    
    <p class="small">Нужно угадать число от 0 до 9999. Подсказка: загадано число 8888.</p>
    
    <p class="small"><a href="/static/data/USSD-smsimple.pdf">Документация по USSD сессиям</a>, предоставляемым SMSimple.</p>

    <? if ($_POST['phone']) : ?>
        <p class="red">Отправлено приглашение: <?= htmlspecialchars($_POST['phone']) ?></p>
    <? endif ?>

    <? if (!$error) : ?>
        <form method="post" action="">
            <dl>
                <dt>Телефон получателя:</dt>
                <dd>
                    <input type="text" name="phone" size="50" value="<?= htmlspecialchars($_POST['phone']) ?>"/><br />
                    <span class="gray small">например, 7-926-111-2233</span>
                </dd>
                <dt>Кодировка</dt>
                <dd>
                    <input type="checkbox" name="encoding" value="UCS2" <?= $_POST['encoding']=='UCS2'?'checked="checked"':'' ?>/>
                    UCS2 (с поддержкой русских букв)
                </dd>
                <dt>&nbsp;</dt>
                <dd>
                    <input type="submit" value="Начать USSD-сессию"/>
                </dd>
            </dl>
        </form>
    <? else : ?>
        <p>Ошибка: <?= $message ?></p>
        <p><a href="">Начать еще одну сессию</a></p>
    <? endif ?>

</body>
</html>