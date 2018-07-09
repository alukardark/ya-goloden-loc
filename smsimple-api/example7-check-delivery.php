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
    $delivery = false;

    try {
        $sms->connect();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $message_id = $_POST['message_id'];
            $delivery = $sms->check_delivery($message_id);
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

    <h2>Проверить статус доставки SMS</h2>

    <? if ($message) : ?>
        <p class="red"><?= htmlspecialchars($message) ?></p>
    <? endif ?>

    <? if (!$error) : ?>
        <form method="post" action="">
            <dl>
                <dt>Код сообщения:</dt>
                <dd>
                    <input type="text" name="message_id" size="50" value="<?= htmlspecialchars($_POST['message_id']) ?>"/><br />
                    <span class="gray small"></span>
                </dd>
                <dt>&nbsp;</dt>
                <dd>
                    <input type="submit" value="Проверить"/>
                </dd>
            </dl>
        </form>
    <? else : ?>
        <p><a href="">Проверить другое сообщение</a></p>
    <? endif ?>

    <?
        if ($delivery) {
            $output = 'Сообщение #'.$message_id.' ';
            $color = '#000';
            if ($delivery['sms_delayed']) {
                $output .= 'пока не доставлено';
                $color = '#000';
            } elseif ($delivery['sms_delivered'] ) {
                $output .= 'доставлено';
                $color = '#080';
            } elseif ($delivery['sms_failed']) {
                $output .= 'не удалось доставить';
                $color = '#f00';
            } else {
                $output .= 'потеряно';
                $color = '#f00';
            }
        }
    ?>
    <div style="<?= 'color:'.$color ?>"><?= $output ?></div>

</body>
</html>