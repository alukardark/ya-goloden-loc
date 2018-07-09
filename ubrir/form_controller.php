<?php

use Bitrix\Main\Loader;
use Bitrix\Sale\BusinessValue;


//include_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/ubrir.payment/install/sale_payment/ubrir/sdk/Ubrir.php");
//include_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/ubrir.payment/install/sale_payment/ubrir/sdk/UbrirException.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
Loader::includeModule('sale');
Loader::includeModule('ubrir.payment');


if (!empty($_POST['task_ubrir'])) {
    $Pid = $_POST["PID"];
    $status = $_POST['shoporderidforstatus'];
    $id = BusinessValue::get('ID', 'PAYSYSTEM_' . $Pid);
    $sert = BusinessValue::get('SERT', 'PAYSYSTEM_' . $Pid);
    switch ($_POST['task_ubrir']) {
        case '1':
            if (!empty($status) AND !empty($id) AND !empty($sert)) {
                $order_id = $status * 1;
                $arOrder = CSaleOrder::GetByID($order_id);
                if (!empty($arOrder['PS_STATUS_MESSAGE'])) {
                    $bankHandler = new Ubrir(array(                                                                                                    // для статуса
                        'shopId' => $id,
                        'order_id' => $order_id,
                        'sert' => $sert,
                        'twpg_order_id' => $arOrder['PS_STATUS_DESCRIPTION'],
                        'twpg_session_id' => $arOrder['PS_STATUS_MESSAGE']
                    ));
                    $out = '<div class="ubr_s">Статус заказа - ' . $bankHandler->check_status() . '</div>';
                } else $out = '<div class="ubr_f">Получить статус данного заказа невозможно, его не существует.</div>';
            }
            if (empty($status)) $out = "<div class='ubr_f'>Вы не ввели номер заказа</div>";
            break;

        case '2':
            if (!empty($status) AND !empty($id) AND !empty($sert)) {
                $order_id = $status * 1;
                $arOrder = CSaleOrder::GetByID($order_id);
                if (!empty($arOrder['PS_STATUS_MESSAGE'])) {
                    $bankHandler = new Ubrir(array(                                                                                                                   // для детализации
                        'shopId' => $id,
                        'order_id' => $order_id,
                        'sert' => $sert,
                        'twpg_order_id' => $arOrder['PS_STATUS_DESCRIPTION'],
                        'twpg_session_id' => $arOrder['PS_STATUS_MESSAGE']
                    ));
                    $out = $bankHandler->detailed_status();
                } else $out = '<div class="ubr_f">Получить детализацию данного заказа невозможноего не существует.</div>';
            }
            if (empty($status)) $out = "<div class='ubr_f'>Вы не ввели номер заказа</div>";
            break;

        case '3':
            if (!empty($status) AND !empty($id) AND !empty($sert)) {
                $order_id = $status * 1;
                $arOrder = CSaleOrder::GetByID($order_id);
                if ($arOrder['PAYED'] == 'Y') {
                    if (!empty($arOrder['PS_STATUS_MESSAGE'])) {
                        $bankHandler = new Ubrir(array(                                                                                                                  // для реверса
                            'shopId' => $id,
                            'order_id' => $order_id,
                            'sert' => $sert,
                            'twpg_order_id' => $arOrder['PS_STATUS_DESCRIPTION'],
                            'twpg_session_id' => $arOrder['PS_STATUS_MESSAGE']
                        ));
                        $res = $bankHandler->reverse_order();
                        if ($res == 'OK') {
                            $out = '<div class="ubr_s">Оплата успешно отменена</div>';
                            CSaleOrder::Update($order_id, array("PAYED" => "N"));
                            CSaleOrder::StatusOrder($order_id, "N");
                        } else $out = $res;
                    } else $out = '<div class="ubr_f">Получить реверс данного заказа невозможно, его не существует.</div>';
                } else $out = '<div class="ubr_f">Получить реверс данного заказа невозможно, его не существует.</div>';
            }
            if (empty($status)) $out = "<div class='ubr_f'>Вы не ввели номер заказа</div>";
            break;

        case '4':

            if (!empty($id) AND !empty($sert)) {
                $bankHandler = new Ubrir(array(
                    // // для сверки итогов
                    'shopId' => $id,
                    'sert' => $sert,
                ));
                $out = $bankHandler->reconcile();
            }


            break;

        case '5':
            if (!empty($id) AND !empty($sert)) {
                $bankHandler = new Ubrir(array(                                                                                                                 // // для журнала операции
                    'shopId' => $id,
                    'sert' => $sert,
                ));
                $out = $bankHandler->extract_journal();
            }
            break;

        // case '6':
        // 	if(!empty($_POST["VALUE2_UNI_LOGIN_1"])  AND !empty($_POST["VALUE2_UNI_EMP_1"])) {
        // 			$bankHandler = new Ubrir(array(																												 // // для журнала Uniteller
        // 				'uni_login' => $_POST["VALUE2_UNI_LOGIN_1"],
        // 				'uni_pass' => $_POST["VALUE2_UNI_EMP_1"],
        // 				));
        // 			$out = $bankHandler->uni_journal();
        // 	}
        // 	else $out = '<div class="ubr_f">Необходимо ввести логин и пароль ЛК для MasterCard</div>';
        // 	break;
        case '7':
            if (!empty($_POST['mailsubject']) AND !empty($_POST['maildesc']) AND !empty($_POST['mailem'])) {
                $to = 'ibank@ubrr.ru';
                $subject = htmlspecialchars($_GET['mailsubject'], ENT_QUOTES);
                $message = 'Отправитель: ' . htmlspecialchars($_GET['mailem'], ENT_QUOTES) . ' | ' . htmlspecialchars($_GET['maildesc'], ENT_QUOTES);
                $headers = 'From: ' . $_SERVER["HTTP_HOST"];
                mail($to, $subject, $message, $headers);
                echo 'Отправлено успешно';
            } else echo 'Заполнены не все поля';
            break;

        default:
            break;
    }
};
echo $out;
?>