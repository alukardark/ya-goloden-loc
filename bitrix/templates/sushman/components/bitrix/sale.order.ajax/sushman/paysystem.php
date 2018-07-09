<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
} ?>
<?
function cmpBySort($a, $b)
{
    if ($a['ID'] == $b['ID']) {
        return 0;
    }
    return ($a['ID'] < $b['ID']) ? -1 : 1;
}

?>
<div class="section">
    <script type="text/javascript">
        function changePaySystem(param) {
            //fadein/out paysystems block
            if ($('input[name=PAY_SYSTEM_ID]:checked').val() == 6 || $('input[name=PAY_SYSTEM_ID]:checked').val() == 7 || $('input[name=PAY_SYSTEM_ID]:checked').val() == 8) {
                $('.paysystems').fadeIn(300);
            }
            else {
                $('.paysystems').fadeOut(300);
            }

            if (BX("account_only") && BX("account_only").value == 'Y') // PAY_CURRENT_ACCOUNT checkbox should act as radio
            {
                if (param == 'account') {
                    if (BX("PAY_CURRENT_ACCOUNT")) {
                        BX("PAY_CURRENT_ACCOUNT").checked = true;
                        BX("PAY_CURRENT_ACCOUNT").setAttribute("checked", "checked");
                        BX.addClass(BX("PAY_CURRENT_ACCOUNT_LABEL"), 'selected');

                        // deselect all other
                        var el = document.getElementsByName("PAY_SYSTEM_ID");
                        for (var i = 0; i < el.length; i++)
                            el[i].checked = false;
                    }
                }
                else {
                    BX("PAY_CURRENT_ACCOUNT").checked = false;
                    BX("PAY_CURRENT_ACCOUNT").removeAttribute("checked");
                    BX.removeClass(BX("PAY_CURRENT_ACCOUNT_LABEL"), 'selected');
                }
            }
            else if (BX("account_only") && BX("account_only").value == 'N') {
                if (param == 'account') {
                    if (BX("PAY_CURRENT_ACCOUNT")) {
                        BX("PAY_CURRENT_ACCOUNT").checked = !BX("PAY_CURRENT_ACCOUNT").checked;

                        if (BX("PAY_CURRENT_ACCOUNT").checked) {
                            BX("PAY_CURRENT_ACCOUNT").setAttribute("checked", "checked");
                            BX.addClass(BX("PAY_CURRENT_ACCOUNT_LABEL"), 'selected');
                        }
                        else {
                            BX("PAY_CURRENT_ACCOUNT").removeAttribute("checked");
                            BX.removeClass(BX("PAY_CURRENT_ACCOUNT_LABEL"), 'selected');
                        }
                    }
                }
            }

            submitForm();
        }
    </script>
    <div class="bx_section">
        <?
        if ($arResult["PAY_FROM_ACCOUNT"] == "Y") {
            $accountOnly = ($arParams["ONLY_FULL_PAY_FROM_ACCOUNT"] == "Y") ? "Y" : "N";
            ?>
            <input type="hidden" id="account_only" value="<?= $accountOnly ?>"/>
            <div class="bx_block w100 vertical">
                <div class="bx_element">
                    <input type="hidden" name="PAY_CURRENT_ACCOUNT" value="N">
                    <label for="PAY_CURRENT_ACCOUNT" id="PAY_CURRENT_ACCOUNT_LABEL"
                           onclick="changePaySystem('account');"
                           class="<? if ($arResult["USER_VALS"]["PAY_CURRENT_ACCOUNT"] == "Y") echo "selected" ?>">
                        <input type="checkbox" name="PAY_CURRENT_ACCOUNT" id="PAY_CURRENT_ACCOUNT"
                               value="Y"<? if ($arResult["USER_VALS"]["PAY_CURRENT_ACCOUNT"] == "Y") {
                            echo " checked=\"checked\"";
                        } ?>>

                        <div class="bx_description">
                            <strong><?= GetMessage("SOA_TEMPL_PAY_ACCOUNT") ?></strong>

                            <p>

                            <div><?= GetMessage("SOA_TEMPL_PAY_ACCOUNT1") . " <b>" . $arResult["CURRENT_BUDGET_FORMATED"] ?></b></div>
                            <? if ($arParams["ONLY_FULL_PAY_FROM_ACCOUNT"] == "Y"): ?>
                                <div><?= GetMessage("SOA_TEMPL_PAY_ACCOUNT3") ?></div>
                            <? else: ?>
                                <div><?= GetMessage("SOA_TEMPL_PAY_ACCOUNT2") ?></div>
                            <? endif; ?>
                            </p>
                        </div>
                    </label>

                    <div class="clear"></div>
                </div>
            </div>
            <?
        }
        uasort($arResult["PAY_SYSTEM"], "cmpBySort"); // resort arrays according to SORT value
        global $city;
        foreach ($arResult["PAY_SYSTEM"] as $arPaySystem) {
//            if ($city == 3 && $arPaySystem["ID"] == 6) {
//                continue;
//            }

            if (strlen(trim(str_replace("<br />", "",
                    $arPaySystem["DESCRIPTION"]))) > 0 || intval($arPaySystem["PRICE"]) > 0
            ) {
                if (count($arResult["PAY_SYSTEM"]) == 1) {
                    ?>
                    <div class="bx_block w100 vertical">
                        <div class="bx_element">
                            <input type="hidden" name="PAY_SYSTEM_ID" value="<?= $arPaySystem["ID"] ?>">
                            <input type="radio"
                                   id="ID_PAY_SYSTEM_ID_<?= $arPaySystem["ID"] ?>"
                                   name="PAY_SYSTEM_ID"
                                   value="<?= $arPaySystem["ID"] ?>"
                                <? if ($arPaySystem["CHECKED"] == "Y" && !($arParams["ONLY_FULL_PAY_FROM_ACCOUNT"] == "Y" && $arResult["USER_VALS"]["PAY_CURRENT_ACCOUNT"] == "Y")) {
                                    echo " checked=\"checked\"";
                                } ?>
                                   onclick="changePaySystem();"
                                />
                            <label for="ID_PAY_SYSTEM_ID_<?= $arPaySystem["ID"] ?>"
                                   onclick="BX('ID_PAY_SYSTEM_ID_<?= $arPaySystem["ID"] ?>').checked=true;changePaySystem();">
                                <?
                                if (count($arPaySystem["PSA_LOGOTIP"]) > 0):
                                    $imgUrl = $arPaySystem["PSA_LOGOTIP"]["SRC"];
                                else:
                                    $imgUrl = "";
                                endif;
                                ?>
                                <div class="bx_description">
                                    <? if ($arParams["SHOW_PAYMENT_SERVICES_NAMES"] != "N"): ?>
                                        <strong><?= $arPaySystem["PSA_NAME"]; ?></strong>
                                    <? endif; ?>
                                    <p>
                                        <?
                                        if (intval($arPaySystem["PRICE"]) > 0) {
                                            echo str_replace("#PAYSYSTEM_PRICE#",
                                                SaleFormatCurrency(roundEx($arPaySystem["PRICE"], SALE_VALUE_PRECISION),
                                                    $arResult["BASE_LANG_CURRENCY"]),
                                                GetMessage("SOA_TEMPL_PAYSYSTEM_PRICE"));
                                        } else {
                                            echo $arPaySystem["DESCRIPTION"];
                                        }
                                        ?>
                                    </p>
                                </div>
                                <? if ($imgUrl) { ?>
                                    <div class="bx_logotype">
                                        <span style="background-image:url(<?= $imgUrl ?>);"></span>
                                    </div>
                                <? } ?>
                            </label>

                            <div class="clear"></div>
                        </div>
                    </div>
                    <?
                } else // more than one
                {
                    ?>
                    <div class="bx_block w100 vertical">
                        <div class="bx_element">
                            <input type="radio"
                                   id="ID_PAY_SYSTEM_ID_<?= $arPaySystem["ID"] ?>"
                                   name="PAY_SYSTEM_ID"
                                   value="<?= $arPaySystem["ID"] ?>"
                                <? if ($arPaySystem["CHECKED"] == "Y" && !($arParams["ONLY_FULL_PAY_FROM_ACCOUNT"] == "Y" && $arResult["USER_VALS"]["PAY_CURRENT_ACCOUNT"] == "Y")) {
                                    echo " checked=\"checked\"";
                                } ?>
                                   onclick="changePaySystem();"/>
                            <label for="ID_PAY_SYSTEM_ID_<?= $arPaySystem["ID"] ?>"
                                   onclick="BX('ID_PAY_SYSTEM_ID_<?= $arPaySystem["ID"] ?>').checked=true;changePaySystem();">
                                <div class="bx_description">
                                    <? if ($arParams["SHOW_PAYMENT_SERVICES_NAMES"] != "N"): ?>
                                        <strong><?= $arPaySystem["PSA_NAME"]; ?></strong>
                                    <? endif; ?>
                                    <p>
                                        <?
                                        if (intval($arPaySystem["PRICE"]) > 0) {
                                            echo str_replace("#PAYSYSTEM_PRICE#",
                                                SaleFormatCurrency(roundEx($arPaySystem["PRICE"], SALE_VALUE_PRECISION),
                                                    $arResult["BASE_LANG_CURRENCY"]),
                                                GetMessage("SOA_TEMPL_PAYSYSTEM_PRICE"));
                                        } else {
                                            echo $arPaySystem["DESCRIPTION"];
                                        }
                                        ?>
                                    </p>
                                </div>
                                <?
                                if (count($arPaySystem["PSA_LOGOTIP"]) > 0):
                                    $imgUrl = $arPaySystem["PSA_LOGOTIP"]["SRC"];
                                else:
                                    $imgUrl = "";
                                endif;
                                ?>
                                <? if ($imgUrl) { ?>
                                    <div class="bx_logotype">
                                        <span style='background-image:url(<?= $imgUrl ?>);'></span>
                                    </div>
                                <? } ?>
                            </label>

                            <div class="clear"></div>
                        </div>
                    </div>
                    <?
                }
            }

            if (strlen(trim(str_replace("<br />", "",
                    $arPaySystem["DESCRIPTION"]))) == 0 && intval($arPaySystem["PRICE"]) == 0
            ) {
                if (count($arResult["PAY_SYSTEM"]) == 1) {
                    ?>
                    <div class="bx_block horizontal">

                        <div class="bx_element">
                            <input type="hidden" name="PAY_SYSTEM_ID" value="<?= $arPaySystem["ID"] ?>">
                            <input type="radio"
                                   id="ID_PAY_SYSTEM_ID_<?= $arPaySystem["ID"] ?>"
                                   name="PAY_SYSTEM_ID"
                                   value="<?= $arPaySystem["ID"] ?>"
                                <? if ($arPaySystem["CHECKED"] == "Y" && !($arParams["ONLY_FULL_PAY_FROM_ACCOUNT"] == "Y" && $arResult["USER_VALS"]["PAY_CURRENT_ACCOUNT"] == "Y")) {
                                    echo " checked=\"checked\"";
                                } ?>
                                   onclick="changePaySystem();"
                                />
                            <label for="ID_PAY_SYSTEM_ID_<?= $arPaySystem["ID"] ?>"
                                   onclick="BX('ID_PAY_SYSTEM_ID_<?= $arPaySystem["ID"] ?>').checked=true;changePaySystem();">
                                <? if ($arParams["SHOW_PAYMENT_SERVICES_NAMES"] != "N"): ?>
                                    <div class="bx_description">
                                        <div class="clear"></div>
                                        <strong><?= $arPaySystem["PSA_NAME"]; ?></strong>
                                    </div>
                                <? endif; ?>
                                <?
                                if (count($arPaySystem["PSA_LOGOTIP"]) > 0):
                                    $imgUrl = $arPaySystem["PSA_LOGOTIP"]["SRC"];
                                else:
                                    $imgUrl = "";
                                endif;
                                ?>
                                <? if ($imgUrl) { ?>
                                    <div class="bx_logotype">
                                        <span style='background-image:url(<?= $imgUrl ?>);'></span>
                                    </div>
                                <? } ?>
                        </div>
                    </div>
                    <?
                } else // more than one
                {
                    global $city;
                    ?>
                    <div class="bx_block horizontal">
                        <?
                        global $city;
//                        if ($city == 1 && ($arPaySystem["PSA_NAME"] == 'онлайн-оплата NSK' or $arPaySystem["PSA_NAME"] == 'онлайн-оплата KEM')) { // NKZ
//                            continue;
//                        }
//                        if ($city == 2 && ($arPaySystem["PSA_NAME"] == 'онлайн-оплата' or $arPaySystem["PSA_NAME"] == 'онлайн-оплата NSK')) { // KEM
//                            continue;
//                        }
//                        if ($city == 3 && ($arPaySystem["PSA_NAME"] == 'онлайн-оплата' or $arPaySystem["PSA_NAME"] == 'онлайн-оплата KEM')) { // NSK
//                            continue;
//                        }

                        ?>
                        <div class="bx_element">

                            <input type="radio"
                                   id="ID_PAY_SYSTEM_ID_<?= $arPaySystem["ID"] ?>"
                                   name="PAY_SYSTEM_ID"
                                   value="<?= $arPaySystem["ID"] ?>"
                                <? if ($arPaySystem["CHECKED"] == "Y" && !($arParams["ONLY_FULL_PAY_FROM_ACCOUNT"] == "Y" && $arResult["USER_VALS"]["PAY_CURRENT_ACCOUNT"] == "Y")) {
                                    echo " checked=\"checked\"";
                                } ?>
                                   onclick="changePaySystem();"/>

                            <label for="ID_PAY_SYSTEM_ID_<?= $arPaySystem["ID"] ?>"
                                   onclick="BX('ID_PAY_SYSTEM_ID_<?= $arPaySystem["ID"] ?>').checked=true;changePaySystem();">
                                <? if ($arParams["SHOW_PAYMENT_SERVICES_NAMES"] != "N"): ?>
                                    <div class="bx_description">
                                        <div class="clear"></div>
                                        <strong style="font-weight: normal;">
                                            <? if ($arParams["SHOW_PAYMENT_SERVICES_NAMES"] != "N"): ?>
                                                <?= str_replace(array(' NSK', ' KEM'), array('', ''),
                                                    $arPaySystem["PSA_NAME"]); ?>
                                                <?
                                            else:?>
                                                <?= "&nbsp;" ?>
                                            <? endif; ?>
                                        </strong>
                                    </div>
                                <? endif; ?>
                                <?
                                if (count($arPaySystem["PSA_LOGOTIP"]) > 0):
                                    $imgUrl = $arPaySystem["PSA_LOGOTIP"]["SRC"];
                                else:
                                    $imgUrl = "";
                                endif;
                                ?>
                                <? if ($imgUrl) { ?>
                                    <div class="bx_logotype">
                                        <span style='background-image:url(<?= $imgUrl ?>);'></span>
                                    </div>
                                <? } ?>
                            </label>
                        </div>
                    </div>
                    <?
                }
            }
        }
        ?>
        <div style="clear: both;"></div>
    </div>
</div>