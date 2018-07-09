<?php

    require_once('./smsimple.config.php');
    require_once('./smsimple.class.php');

    header('Content-type: text/html; charset=utf-8');

    $_POST['message'] = 'Смс-рассылки просто > и удобно! Отправлено с сайта smsimple.ru';
    
    $sms = new SMSimple(array(
        'url' => SMS_API,
        'username' => SMS_USERNAME,
        'password' => SMS_PASSWORD,
    ));

    try {

        $sms->connect();
        $origins_list = $sms->origins();
        $message = '';
        $error = false;

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $result = $sms->getPrice($_POST['origin_id'], $_POST['phone'], $_POST['message']);
            $message = 'Получена информация о цене: cost='.$result['cost'].', sms_count='.$result['sms_count'].', sms_out_of_balance='.$result['sms_out_of_balance'];
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

    <h2>Узнать цену SMS</h2>

    <? if ($message) : ?>
        <p class="red"><?= htmlspecialchars($message) ?></p>
    <? endif ?>

    <? if (!$error) : ?>
        <form method="post" action="">
            <dl>
                <dt>Подпись отправителя:</dt>
                <dd>
                    <select name="origin_id">
                        <? foreach ($origins_list as $origin) : ?>
                            <option value="<?= $origin['id'] ?>"><?= htmlspecialchars($origin['title']) ?></option>
                        <? endforeach ?>
                    </select>
                </dd>
                <dt>Телефоны получателей (один или несколько через запятую):</dt>
                <dd>
                    <input type="text" name="phone" size="50" value="<?= htmlspecialchars($_POST['phone']) ?>"/><br />
                    <span class="gray small">например, 7-926-111-2233</span>
                </dd>
                <dt>Сообщение:</dt>
                <dd>
                    <textarea name="message" cols="50" rows="6" readonly="readonly">Смс-рассылки & просто и удобно! Отправлено с сайта smsimple.ru</textarea>
                </dd>
                <dt>&nbsp;</dt>
                <dd>
                    <input type="submit" value="Проверить цену SMS"/>
                </dd>
            </dl>
        </form>
    <? else : ?>
        <p><a href="">Проверить ещё</a></p>
    <? endif ?>

</body>
</html>