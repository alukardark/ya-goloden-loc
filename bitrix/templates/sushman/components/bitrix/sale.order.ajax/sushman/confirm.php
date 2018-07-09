<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
} ?>
<?
if (!empty($arResult["ORDER"])) {
    if ($arResult["ORDER"]["PAY_SYSTEM_ID"] != 6) {
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
        if (WS_PSettings::getFieldValue('specialThanksMsg', false) and (int)$arResult['ORDER']['PRICE'] >= 500 and $checkDay) {
//            $sum = round((int)$arResult['ORDER']['PRICE'] / 2);
//            if ($cities[$city]["CODE"] == 'nkz' or $cities[$city]["CODE"] == 'kem') {
                $sum = round((int)$arResult['ORDER']['PRICE'] * 0.3);
//            }
            ?>
            <div class="bigger" style="text-align:center;padding:40px 0 0;">
                <div style="font-weight: bold;margin-bottom: 20px;">Благодарим за заказ!</div>
                <div style="margin-bottom: 20px;">Ваш подарок – любые* блюда из <a href="/menu">меню</a>

                    <div style="font-size: 30px;margin-top: 10px;font-weight: bold;">
                        на <? echo $sum; ?> <? echo num2word($sum, ['рубль', 'рубля', 'рублей']); ?>!
                    </div>
                </div>
                <div style="margin-bottom: 20px;">Выберите их и назовите нашему оператору – ожидайте звонка в течение 10
                    минут.
                </div>
                <div style="margin-bottom: 30px;">* В данном предложении не участвуют эконом роллы, напитки, суши,
                    десерты и соуса, т.е. они не могут входить в «бесплатные» блюда.
                    <?
                    if ($cities[$city]["CODE"] == 'nkz') {
                        echo 'Предложение действует с воскресенья по четверг.';
                    }
                    ?>
                </div>
                <div><a href="/specials/50-ot-summy-zakaza-vozvrashchaetsya-<? echo $cities[$city]["CODE"]; ?>">Подробнее
                        об акции</a></div>
            </div>

            <?
        } else {
            ?>
            <p class="bigger" style="text-align:center;padding:40px 0 0;">Ваш заказ принят!<br/>
                Ожидайте звонка в течение 10 минут.<br/>
                Спасибо, что выбираете нас!<br/>
            </p>

            <?
        }
    }
    if (!empty($arResult["PAY_SYSTEM"])) {
        $_SESSION['order_value_712392'] = $arResult['ORDER']['PRICE'];
        ?>
        <?
        if (strlen($arResult["PAY_SYSTEM"]["ACTION_FILE"]) > 0) {
            ?>
            <?
            if ($arResult["PAY_SYSTEM"]["NEW_WINDOW"] == "Y") {
                ?>
                <script language="JavaScript">
                    window.open('<?=$arParams["PATH_TO_PAYMENT"]?>?ORDER_ID=<?=urlencode(urlencode($arResult["ORDER"]["ACCOUNT_NUMBER"]))?>');
                </script>
                <?= GetMessage("SOA_TEMPL_PAY_LINK",
                    Array("#LINK#" => $arParams["PATH_TO_PAYMENT"] . "?ORDER_ID=" . urlencode(urlencode($arResult["ORDER"]["ACCOUNT_NUMBER"])))) ?>
                <?
                if (CSalePdf::isPdfAvailable() && CSalePaySystemsHelper::isPSActionAffordPdf($arResult['PAY_SYSTEM']['ACTION_FILE'])) {
                    ?><br/>
                    <?= GetMessage("SOA_TEMPL_PAY_PDF",
                        Array("#LINK#" => $arParams["PATH_TO_PAYMENT"] . "?ORDER_ID=" . urlencode(urlencode($arResult["ORDER"]["ACCOUNT_NUMBER"])) . "&pdf=1&DOWNLOAD=Y")) ?>
                    <?
                }
            } else {
                if (strlen($arResult["PAY_SYSTEM"]["PATH_TO_ACTION"]) > 0) {
                    include($arResult["PAY_SYSTEM"]["PATH_TO_ACTION"]);
                }
            }
            ?>
            <?
        }
        ?>
        <?
    } ?>
    <p class="bigger" style="text-align:center;padding:20px 0 40px;"><a href="/"><img
                src="/bitrix/templates/sushman/img/logo.png" alt="" title=""/></a></p>

    <?

} else {
    ?>
    <p class="bigger" style="text-align:center;padding:40px 0;">
        <b><?= GetMessage("SOA_TEMPL_ERROR_ORDER") ?></b><br/><br/></p>

    <?
}
?>
