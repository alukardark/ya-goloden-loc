<?php
$header_class = "basket-header";
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Заказ успешно оплачен");
?>
<?
CModule::includeModule('ws.projectsettings');
global $city, $cities;
$checkDay = true;
if ($cities[$city]["CODE"] == 'nkz') {
    $t = getdate();
    if ($t['wday'] >= 0 && $t['wday'] <= 4) {
        $checkDay = true;
    } else {
        $checkDay = false;
    }
}
if (WS_PSettings::getFieldValue('specialThanksMsg', false) and $_SESSION['order_value_712392'] >= 500 and $checkDay) {
//    $sum = round((int)$_SESSION['order_value_712392'] / 2);
//    if ($cities[$city]["CODE"] == 'nkz' or $cities[$city]["CODE"] == 'kem') {
        $sum = round((int)$_SESSION['order_value_712392'] * 0.3);
//    }
    unset($_SESSION['order_value_712392']);
    ?>
    <div class="bigger" style="text-align:center;padding:40px 0 0;position:relative;">
        <img class="cart-success-ny-image" style="position: absolute; top: 0; left: 0; width: 100%;"
             src="http://img-fotki.yandex.ru/get/5630/54833049.7d/0_b62bd_21889b64_orig.gif"/>

        <div style="position:relative; background: rgba(0,0,0,0.7); padding-top: 50px; padding-bottom: 50px;">
            <div style="font-weight: bold;margin-bottom: 20px;">Благодарим за заказ!</div>
            <div style="margin-bottom: 20px;">Ваш подарок – любые* блюда из <a href="/menu">меню</a>

                <div style="font-size: 30px;margin-top: 10px;font-weight: bold;">
                    на <? echo $sum; ?> <? echo num2word($sum, ['рубль', 'рубля', 'рублей']); ?>!
                </div>
            </div>
            <div style="margin-bottom: 20px;">Выберите их и назовите нашему оператору – ожидайте звонка в течение 10
                минут.
            </div>
            <div style="margin-bottom: 30px;">* В данном предложении не участвуют эконом роллы, напитки, суши, десерты и
                соуса, т.е. они не могут входить в «бесплатные» блюда.
                <?
                if ($cities[$city]["CODE"] == 'nkz') {
                    echo 'Предложение действует с воскресенья по четверг.';
                }
                ?>
            </div>
            <div><a href="/specials/50-ot-summy-zakaza-vozvrashchaetsya-<? echo $cities[$city]["CODE"]; ?>">Подробнее об
                    акции</a></div>
        </div>
    </div>
    <?
} else {
    ?>
    <p class="bigger" style="text-align:center;padding:40px 0 0;">Ваш заказ принят и успешно оплачен!<br/>
        Ожидайте звонка в течение 10 минут.<br/>
        Спасибо, что выбираете нас!<br/>
    </p>

    <?
}
?>
    <p class="bigger" style="text-align:center;padding:20px 0 40px;"><a href="/"><img
                src="/bitrix/templates/sushman/img/logo.png" alt="" title=""/></a></p>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>