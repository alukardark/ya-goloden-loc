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

		$groups = array(1,2,345,6788); // список номеров групп-участниц рассылки
		$origin_id = 134; // номер подписи

        $sms->connect();
        $job_id = $sms->addJob(array(
            'origin_id'   => $origin_id,    // id-подписи отправителя
            'groups_ids'  => $groups,       // список id-групп контактов
            'title'       => 'New job',     // название рассылки
            'message'     => 'SMS message', // текст сообщения
            'start_date'  => date('Y-m-d'), // дата старта рассылки
            'start_time'  => '20:00:00',    // в какое время суток
            'stop_time'   => '23:59:59',    //   позволять рассылать сообщения
        ));
        print 'Job #'.$job_id.' successfully created';

    }
    catch (SMSimpleException $e) {
        print $e->getMessage();
    }
