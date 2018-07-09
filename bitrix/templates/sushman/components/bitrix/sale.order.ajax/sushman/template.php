<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

if ($USER->IsAuthorized() || $arParams["ALLOW_AUTO_REGISTER"] == "Y") {
    if ($arResult["USER_VALS"]["CONFIRM_ORDER"] == "Y" || $arResult["NEED_REDIRECT"] == "Y") {
        if (strlen($arResult["REDIRECT_URL"]) > 0) {
            $APPLICATION->RestartBuffer();
            ?>            <script type="text/javascript">
                window.top.location.href = '<?=CUtil::JSEscape($arResult["REDIRECT_URL"])?>';
            </script>
            <?
            die();
        }

    }
}

$APPLICATION->SetAdditionalCSS($templateFolder . "/style.css");

CJSCore::Init(array('fx', 'popup', 'window', 'ajax'));

if (strlen($arResult["REDIRECT_URL"]) == 0 && ($arResult["USER_VALS"]["CONFIRM_ORDER"] == "Y" || $arResult["NEED_REDIRECT"] == "Y")) {
    include($_SERVER["DOCUMENT_ROOT"] . $templateFolder . "/confirm.php");
} else {

    ?>
    <form action="<?= $APPLICATION->GetCurPage(); ?>" method="POST" name="ORDER_FORM" id="ORDER_FORM"
          enctype="multipart/form-data">
        <a name="order_form"></a>
        <?
        if (!($arResult["USER_VALS"]["CONFIRM_ORDER"] == "Y" || $arResult["NEED_REDIRECT"] == "Y")) {
            ?>
            <div class="bx_order_make payment-type">
                <h3>Способ оплаты:</h3>
                <?
                if ($arParams["DELIVERY_TO_PAYSYSTEM"] == "p2d") {
                    include($_SERVER["DOCUMENT_ROOT"] . $templateFolder . "/paysystem.php");
                } else {
                    include($_SERVER["DOCUMENT_ROOT"] . $templateFolder . "/delivery.php");
                    include($_SERVER["DOCUMENT_ROOT"] . $templateFolder . "/paysystem.php");
                }
                ?>
            </div>
            <div style="clear:both;"></div>
            <hr/>
            <!--
                    <div class="bx_ordercart_order_pay_center">
                        <a href="javascript:void(0)" onclick="ajaxCheckout();" class="checkout">Далее</a>
                    </div>
            -->

            <div class="paysystems" style="display:none;">
                <h1 class='left-header'>Платежные системы:</h1>

                <img src="/bitrix/templates/sushman/img/paysystems/visa.png" alt="Платежная система Visa"
                     title="Платежная система Visa">
                <img src="/bitrix/templates/sushman/img/paysystems/mastercard.png" alt="Платежная система MasterCard"
                     title="Платежная система MasterCard">
                <img src="/bitrix/templates/sushman/img/paysystems/mir.png" alt="Платежная система Мир"
                     title="Платежная система Мир">
                <img style="display:none;" src="/bitrix/templates/sushman/img/paysystems/visa_el.jpg"
                     alt="Visa Electron"
                     title="Visa Electron">
                <img src="/bitrix/templates/sushman/img/paysystems/maestro.png" alt="Maestro" title="Maestro">
                <img style="display:none;" src="/bitrix/templates/sushman/img/paysystems/ae.jpg"
                     alt="Международная платежная система American Express"
                     title="Международная платежная система American Express">
                <img style="display:none;" src="/bitrix/templates/sushman/img/paysystems/jcb.png"
                     alt="Международная платежная система JCB"
                     title="Международная платежная система JCB">
                <img style="display:none;" src="/bitrix/templates/sushman/img/paysystems/dc.png"
                     alt="Международная платежная система Diners Club"
                     title="Международная платежная система Diners Club">
                <img src="/bitrix/templates/sushman/img/paysystems/yad.png" style="background-color: rgb(77, 76, 76);"
                     alt="Электронная платежная система Яндекс.Деньги"
                     title="Электронная платежная система Яндекс.Деньги">
                <img src="/bitrix/templates/sushman/img/paysystems/wm.png" alt="Электронная платежная система WebMoney"
                     title="Электронная платежная система WebMoney">
                <img src="/bitrix/templates/sushman/img/paysystems/qiwi.png" alt="Электронный кошелек Qiwi"
                     title="Электронный кошелек Qiwi">
            </div>

            <?
            global $city, $cities;
            if (true):
                ?>
                <h1 class='left-header'>Ваша скидка:</h1>
                <?
                $hlblock_id = 6;
                $hlblock = Bitrix\Highloadblock\HighloadBlockTable::getById($hlblock_id)->fetch();
                $entity = Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock);
                $main_query = new Bitrix\Main\Entity\Query($entity);
                $main_query->setSelect(array(
                    'ID',
                    'UF_NAME',
                    'UF_DESCRIPTION',
                    'UF_ACTIVE_TIME',
                    'UF_PIC',
                    'UF_HANDLER',
                    'UF_ACTIVE',
                    'UF_SHOW_IN_MENU',
                    'UF_DISCOUNT'
                ));
                $main_query->setOrder(array("UF_SORT"));
                $main_query->setFilter(array("UF_CITY" => $city, "UF_ACTIVE" => 1));
                $result = $main_query->exec();
                $result = new CDBResult($result);
                $rows = array();
                while ($row = $result->Fetch()) {
                    $rows[] = $row;
                }

                ?>
                <ul id="discounts">
                    <input type="radio" name="discount" value="" id="discount_none"/>
                    <?php
                    CModule::includeModule('ws.projectsettings');
                    $holidays_str = WS_PSettings::getFieldValue("HOLIDAYS");
                    $holidays = explode(",", $holidays_str);
                    $is_holiday = false;
                    foreach ($holidays as $hd) {
                        $hd = trim($hd);
                        $cur_dm = date("d.m");
                        if ($cur_dm == $hd) {
                            $is_holiday = true;
                        }
                    }

                    $discount = ['UF_DISCOUNT' => 0];
                    $arRes = [];
                    foreach ($rows as $row) {

                        if ($row["UF_HANDLER"] == "notholiday" && $is_holiday) {
                            continue;
                        }

                        if (count($row["UF_ACTIVE_TIME"]) > 0) {
                            $workings = array();
                            $dw = Array(
                                'Пн' => 1,
                                'Вт' => 2,
                                'Ср' => 3,
                                'Чт' => 4,
                                'Пт' => 5,
                                'Сб' => 6,
                                'Вс' => 7
                            );
                            $dont_hide_this_shit = false;
                            foreach ($row["UF_ACTIVE_TIME"] as $r) {

                                $parts = explode("/", $r);
                                $ds = explode("-", trim($parts[0]));
                                $dws = $dw[trim($ds[0])];
                                $dwe = $dw[trim($ds[1])];
                                if ($dwe === null) {
                                    $dwe = $dws;
                                }
                                $tstr = explode("-", trim($parts[1]));
                                $ts = MakeTimeStamp(date("d.m.Y") . " " . trim($tstr[0]));
                                $te = MakeTimeStamp(date("d.m.Y") . " " . trim($tstr[1]));
                                $cur = getdate();
                                $cur_stamp = time();

                                $cur_dw = $cur["wday"];
                                $cur_h = $cur["hours"];
                                $cur_m = $cur["minutes"];
                                if ($cur_dw === 0) {
                                    $cur_dw = 7;
                                }

                                if ($cur_dw >= $dws && $cur_dw <= $dwe &&
                                    $cur_stamp >= $ts && $cur_stamp <= $te
                                ) {
                                    $dont_hide_this_shit = true;
                                    break;
                                } else {
//                                    $hide_this_shit = true;
//                                    break;
                                }

                            }

                            if (!$dont_hide_this_shit) {
                                continue;
                            }

                        }

                        if ($row['UF_SHOW_IN_MENU']) {
                            if ($discount['UF_DISCOUNT'] < $row['UF_DISCOUNT']) {
                                $discount = $row;
                            }
                        }

                        $arRes[] = $row;
                    }

                    if (isset($_GET['secret'])) {
//                        echo '<pre>';
//                        var_dump($arRes);
//                        echo '</pre>';
                    }

                    foreach ($arRes as $row) {
                        if ($row['UF_SHOW_IN_MENU'] == 1 and $discount['ID'] and $row['ID'] != $discount['ID']) {
                            continue;
                        }
                        if ($row['UF_SHOW_IN_MENU'] == 0 and $discount['ID'] and $row['UF_DISCOUNT'] <= $discount['UF_DISCOUNT']) {
                            continue;
                        }
                        $pic = "";
                        if (intval($row["UF_PIC"]) > 0) {
                            $pic = CFile::GetFileArray($row["UF_PIC"]);
                            $pic = $pic["SRC"];
                        }
						// value="<?= $row["ID"]"
                        ?>
                        <li>
                            <input type="radio" name="discount" 
                                   id="discount_<?= $row["ID"] ?>"/>
                            <label for="discount_<?= $row["ID"] ?>">
                                <?= ($pic ? "<img src='{$pic}' alt='{$row["UF_NAME"]}'/>" : "") ?>
                                <!-- <span class="title"><?= $row["UF_NAME"] ?></span> -->
                                <span class="desc"><span
                                        class="valigned"><span><?= $row["UF_DESCRIPTION"] ?></span></span></span>
                            </label>
                        </li>
                        <?
                    }

                    if ($discount['ID']) {?>
                    <li class="li-discount-id" style="display:none;position: absolute;left: -20000px"><?=$discount['ID']?></li>
                    <?
						echo '<script type="text/javascript">$(document).ready(function(){ $("#discount_' . $discount['ID'] . '").attr("checked", "true"); $("label[for=discount_' . $discount['ID'] . ']").addClass("ccc"); $("#ORDER_PROP_ORDER_DISCOUNT_ID").val(' . $discount['ID'] . '); submitForm(); });</script>';
                    }

                    ?>
                </ul>
                <div class="clear"></div>
                <? if ($cities[$city]["CODE"] != 'nsk') { ?>
                <p class="note" style="color:#999;margin:10px 0">* Скидка действует на всё меню, кроме напитков,
                    десертов, сетов со скидкой 50%. Скидки не суммируются</p>
            <? } else { ?>
                <p class="note" style="color:#999;margin:10px 0">* Скидка действует на всё меню, кроме напитков,
                    десертов и соусов</p>
            <? } ?>
            <? endif; ?>

            <!-- <div class="bx_ordercart_order_discount">
                    <div class="bx_ordercart_order_discount_right">
                        <table class="bx_ordercart_order_sum">
                            <tbody>
                                <tr>
                                    <td class="custom_t1">Стоимость без скидки:</td>
                                    <td class="custom_t2"></td>
                                </tr>
                                <tr>
                                                            <td class="custom_t1">Скидка "<span class="bx_ordercart_order_discount_name"></span>"</td>
                                    <td class="custom_t2"></td>
                                </tr>
                                                    <tr>
                                                            <td class="custom_t1 fwb">Итого:</td>
                                                            <td class="custom_t2 fwb"></td>
                                                    </tr>
                                            </tbody>
                        </table>
                        <div style="clear:both;"></div>
                    </div>
            </div>
            -->

            <h3 class="order-title">Данные о доставке:</h3>
        <? } ?>
        <div id="order_form_div" class="order-checkout">


            <NOSCRIPT>
                <div class="errortext"><?= GetMessage("SOA_NO_JS") ?></div>
            </NOSCRIPT>
            <?
            if (!function_exists("getColumnName")) {
                function getColumnName($arHeader)
                {
                    return (strlen($arHeader["name"]) > 0) ? $arHeader["name"] : GetMessage("SALE_" . $arHeader["id"]);
                }
            }

            if (!function_exists("cmpBySort")) {
                function cmpBySort($array1, $array2)
                {
                    if (!isset($array1["SORT"]) || !isset($array2["SORT"])) {
                        return -1;
                    }

                    if ($array1["SORT"] > $array2["SORT"]) {
                        return 1;
                    }

                    if ($array1["SORT"] < $array2["SORT"]) {
                        return -1;
                    }

                    if ($array1["SORT"] == $array2["SORT"]) {
                        return 0;
                    }
                }
            }
            ?>
            <div class="bx_order_make">
                <?

                if (!$USER->IsAuthorized() && $arParams["ALLOW_AUTO_REGISTER"] == "N") {
                    if (!empty($arResult["ERROR"])) {
                        foreach ($arResult["ERROR"] as $v) {
                            echo ShowError($v);
                        }
                    } elseif (!empty($arResult["OK_MESSAGE"])) {
                        foreach ($arResult["OK_MESSAGE"] as $v) {
                            echo ShowNote($v);
                        }
                    }

                    include($_SERVER["DOCUMENT_ROOT"] . $templateFolder . "/auth.php");
                } else {
                    if ($arResult["USER_VALS"]["CONFIRM_ORDER"] == "Y" || $arResult["NEED_REDIRECT"] == "Y") {
                        /*if(strlen($arResult["REDIRECT_URL"]) == 0)
                        {
                            include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/confirm.php");
                        } */
                    } else {
                        ?>
                        <script type="text/javascript">
                            function submitForm(val) {
                                if (val != 'Y')
                                    BX('confirmorder').value = 'N';

                                var set = false;
                                var pizza = false;
                                $('#basket_items tbody tr:visible').each(function () {
                                    if ($(this).find('td.item a:visible').text().toLowerCase().indexOf('сет') != -1) {
                                        set = true;
                                    }
                                    if ($(this).find('td.item a:visible').text().toLowerCase().indexOf('пицц') != -1) {
                                        pizza = true;
                                    }
                                });

                                if (set && !pizza && '<?= $cities[$city]["CODE"] ?>' != 'nsk' && '<?= isSet50time(); ?>') {
                                    var text = 'Скидка на все сеты 50% действует при покупке любой пиццы. Положите пиццу в корзину, чтобы оформить заказ.';
                                    $.fancybox(text, {
                                        type: 'html',
                                        autoHeight: true,
                                        minHeight: 20,
                                        padding: 40,
                                        wrapCSS: 'fancybox-responseMessage'
                                    });
                                    return false;
                                }

                                var orderForm = BX('ORDER_FORM');
                                //BX.showWait();
                                BX.ajax.submit(orderForm, ajaxResult);

                                return true;
                            }

                            function ajaxResult(res) {
                                //BX.closeWait();
                                try {
                                    var json = JSON.parse(res);

                                    if (json.error) {
                                        return;
                                    }
                                    else if (json.redirect) {
                                        window.top.location.href = json.redirect;
                                    }
                                }
                                catch (e) {
                                    BX('order_form_content').innerHTML = res;
                                    $("#order_form_content select").not("#ORDER_PROP_10").selectBox();
                                    $("select#ORDER_PROP_10").chosen();

                                    $("input#total_cash").inputmask({'alias': 'numeric'});
                                    $('input#ORDER_PROP_3').mask("+7 (999) 999-9999");
                                }

                                //BX.closeWait();
                            }

                            function SetContact(profileId) {
                                BX("profile_change").value = "Y";
                                submitForm();
                            }
                        </script>
                    <? if ($_POST["is_ajax_post"] != "Y")
                    {
                    ?>
                    <?= bitrix_sessid_post() ?>
                        <div id="order_form_content">
                            <?
                            }
                            else {
                                $APPLICATION->RestartBuffer();
                            }
                            if (!empty($arResult["ERROR"]) && $arResult["USER_VALS"]["FINAL_STEP"] == "Y") {
                                foreach ($arResult["ERROR"] as $v) {
                                    echo ShowError($v);
                                }
                                ?>
                                <script type="text/javascript">
                                    top.BX.scrollToNode(top.BX('ORDER_FORM'));
                                </script>
                                <?
                            }

                            if ($arParams["DELIVERY_TO_PAYSYSTEM"] == "p2d") {
                                include($_SERVER["DOCUMENT_ROOT"] . $templateFolder . "/delivery.php");
                            }
                            include($_SERVER["DOCUMENT_ROOT"] . $templateFolder . "/person_type.php");
                            include($_SERVER["DOCUMENT_ROOT"] . $templateFolder . "/props.php");
                            include($_SERVER["DOCUMENT_ROOT"] . $templateFolder . "/summary.php");
                            if (strlen($arResult["PREPAY_ADIT_FIELDS"]) > 0) {
                                echo $arResult["PREPAY_ADIT_FIELDS"];
                            }
                            ?>

                            <? if ($_POST["is_ajax_post"] != "Y")
                            {
                            ?>
                        </div>
                    <input type="hidden" name="confirmorder" id="confirmorder" value="Y">
                    <input type="hidden" name="profile_change" id="profile_change" value="N">
                    <input type="hidden" name="is_ajax_post" id="is_ajax_post" value="Y">
                    <input type="hidden" name="json" value="Y">

                        <?
                    if ($arParams["DELIVERY_NO_AJAX"] == "N")
                    {
                        ?>
                        <div
                            style="display:none;"><? $APPLICATION->IncludeComponent("bitrix:sale.ajax.delivery.calculator",
                                "", array(), null, array('HIDE_ICONS' => 'Y')); ?></div>
                    <?
                    }
                    }
                    else
                    {
                    ?>
                        <script type="text/javascript">
                            top.BX('confirmorder').value = 'Y';
                            top.BX('profile_change').value = 'N';

                        </script>
                        <?
                        die();
                    }
                    }
                }
                ?>
            </div>
        </div>
    </form>
    <div class="clear"></div>
    <div class="privacy-agr" style="text-align:right; margin-top:20px;">Нажимая на кнопку, вы даете <a target="_blank"
                                                                                                       href="/pi/">согласие
            на обработку персональных данных</a></div>
<? } ?>