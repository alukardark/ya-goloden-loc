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
        if ($arr['year_begin'])
            $result = $sms->sms_statistic($arr['year_begin'], $arr['month_begin'], $arr['day_begin'], $arr['year_end'], $arr['month_end'], $arr['day_end'], $arr['count'], $arr['start'], $arr['limit']);

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

    <h2>Статистика СМС</h2>
    
    <? if (is_array($result)) : ?>
        <table border="1">
            <tr>
                <th>Статус</th>
                <th>Дата доставки</th>
                <th>Подпись</th>
                <th>Дата отправки</th>
                <th>ID сообщения</th>
                <th>ID рассылки</th>
                <th>Адрес получателя</th>
                <th>Код ошибки</th>
                <th>Текс сообщения</th>
            </tr>
            <? foreach ($result as $row) : ?>
            <tr>
                <td><?= $row['status'] ?></td>
                <td><?= $row['done_date'] ?></td>
                <td><?= $row['source_addr'] ?></td>
                <td><?= $row['submit_date'] ?></td>
                <td><?= $row['message_id'] ?></td>
                <td><?= $row['job_id'] ?></td>
                <td><?= $row['dest_addr'] ?></td>
                <td><?= $row['error_code'] ?></td>
                <td><?= $row['short_message'] ?></td>
            </tr>
            <? endforeach ?>
        </table>
    <? else : ?>
        <u>Количество СМС в статистике за выбранный период: <b><?= $result ?></b></u>
    <? endif ?>

    <? if (!$error) : ?>
        <form method="get" action="">
            <dl>
                <dt><b>Начало интервала</b></dt>
                <dd>
                    Год <input type="text" name="year_begin" size="4" value="<?= htmlspecialchars($arr['year_begin']) ?>"/><br />
                    Месяц <input type="text" name="month_begin" size="4" value="<?= htmlspecialchars($arr['month_begin']) ?>"/><br />
                    День <input type="text" name="day_begin" size="4" value="<?= htmlspecialchars($arr['day_begin']) ?>"/><br />
                </dd>
                <dt><b>Начало интервала</b></dt>
                <dd>
                    Год <input type="text" name="year_end" size="4" value="<?= htmlspecialchars($arr['year_end']) ?>"/><br />
                    Месяц <input type="text" name="month_end" size="4" value="<?= htmlspecialchars($arr['month_end']) ?>"/><br />
                    День <input type="text" name="day_end" size="4" value="<?= htmlspecialchars($arr['day_end']) ?>"/><br />
                </dd>
                <dt><b>Показывать сообщения</b></dt>
                <dd>
                    Количество <input type="text" name="limit" size="4" value="<?= htmlspecialchars($arr['limit']?$arr['limit']:'1000') ?>"/><br />
                    Начиная с <input type="text" name="start" size="4" value="<?= htmlspecialchars($arr['start']?$arr['start']:'0') ?>"/><br />
                </dd>
                <dt><b>Только количество</b></dt>
                <dd>
                    Показывать только количество <input type="checkbox" name="count" value="1" <?= ($arr['count']=='1')?'checked=checked':'' ?>/><br />
                </dd>
                <dt>&nbsp;</dt>
                <dd>
                    <input type="submit" value="Показать статистику"/>
                </dd>
            </dl>
        </form>
    <? else : ?>
        Ошибка: <?= $message ?>
    <? endif ?>

</body>
</html>