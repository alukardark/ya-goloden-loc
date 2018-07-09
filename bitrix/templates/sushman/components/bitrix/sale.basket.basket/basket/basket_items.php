<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
} ?>
<?
echo ShowError($arResult["ERROR_MESSAGE"]);
global $bDelayColumn, $bDeleteColumn;
$bDelayColumn = false;
$bDeleteColumn = false;
$bWeightColumn = false;
$bPropsColumn = false;
$bPriceType = false;

function showItem($arItem, $arResult, $arUrls, $is_complex)
{
    global $bDelayColumn, $bDeleteColumn;

    // Anti-wok kostyl
    // правка: удалённые половинки пиццы/воки висят в корзине с нулевой ценой
    $the_sum = (int)trim(str_replace("руб.", "", $arItem["SUM"]));
    if ($the_sum == 0) {
        return false;
    }
    if ($arItem["DELAY"] == "N" && $arItem["CAN_BUY"] == "Y"):
        $product_id = $arItem["PRODUCT_ID"];
        $mxResult = CCatalogSku::GetProductInfo(
            $arItem["PRODUCT_ID"]
        );
        if (is_array($mxResult)) {
            $product_id = $mxResult["ID"];
        }
        $arItem["PRODUCT_SORT"] = $product_id;
        $res = CIBlockElement::GetById($product_id);
        if ($row = $res->GetNextElement()) {
            $arItem["PRODUCT_SORT"] = $row->fields['SORT'];
        }

        ?>
        <tr id="<?= $arItem["ID"] ?>"<?= ($is_complex ? " data-cid='{$arItem["CID"]}'" : "") ?>>
            <? if (!$arItem["IS_PARENT_PRODUCT"]) { ?>
                <td class="margin"></td><? } ?>
            <?
            foreach ($arResult["GRID"]["HEADERS"] as $id => $arHeader):

                if (in_array($arHeader["id"], array(
                    "PROPS",
                    "DELAY",
                    "DELETE",
                    "TYPE",
                    "PROPERTY_CUSTOM_WEIGHT_VALUE"
                ))) // some values are not shown in the columns in this template
                {
                    continue;
                }

                if ($arHeader["id"] == "NAME"):
                    if ($arItem["IS_PARENT_PRODUCT"]) {
                        ?>
                        <td class="item" colspan="4">
                            <div class="bx_ordercart_itemtitle"><?= $arItem["NAME"] ?></div>
                        </td>
                        <?

                    } else {

                        ?>
                        <td class="itemphoto">
                            <div class="bx_ordercart_photo_container">
                                <?
                                if (strlen($arItem["PREVIEW_PICTURE_SRC"]) > 0):
                                    $url = $arItem["PREVIEW_PICTURE_SRC"];
                                elseif (strlen($arItem["DETAIL_PICTURE_SRC"]) > 0):
                                    $url = $arItem["DETAIL_PICTURE_SRC"];
                                else:
                                    $url = $templateFolder . "/images/no_photo.png";
                                endif;
                                ?>

                                <? if (strlen($arItem["DETAIL_PAGE_URL"]) > 0): ?><a
                                    href="<?= $arItem["DETAIL_PAGE_URL"] ?>"><? endif; ?>
                                    <div class="bx_ordercart_photo" style="background-image:url('<?= $url ?>')"></div>
                                    <? if (strlen($arItem["DETAIL_PAGE_URL"]) > 0): ?></a><? endif; ?>
                            </div>
                            <?
                            if (!empty($arItem["BRAND"])):
                                ?>
                                <div class="bx_ordercart_brand">
                                    <img alt="" src="<?= $arItem["BRAND"] ?>"/>
                                </div>
                                <?
                            endif;
                            ?>
                        </td>
                        <td class="item">
                            <div class="bx_ordercart_itemtitle">
                                <? if (strlen($arItem["DETAIL_PAGE_URL"]) > 0): ?><a
                                    href="<?= $arItem["DETAIL_PAGE_URL"] ?>"><? endif; ?>
                                    <?= $arItem["NAME"] ?>
                                    <? if (strlen($arItem["DETAIL_PAGE_URL"]) > 0): ?></a><? endif; ?>
                            </div>
                            <?
                            if (is_array($arItem["SKU_DATA"]) && !empty($arItem["SKU_DATA"])):
                                foreach ($arItem["SKU_DATA"] as $propId => $arProp):

                                    // if property contains images or values
                                    $isImgProperty = false;
                                    if (array_key_exists('VALUES',
                                            $arProp) && is_array($arProp["VALUES"]) && !empty($arProp["VALUES"])
                                    ) {
                                        foreach ($arProp["VALUES"] as $id => $arVal) {
                                            if (isset($arVal["PICT"]) && !empty($arVal["PICT"])) {
                                                $isImgProperty = true;
                                                break;
                                            }
                                        }
                                    }

                                    $full = (count($arProp["VALUES"]) > 5) ? "full" : "";

                                    if ($isImgProperty): // iblock element relation property
                                        ?>
                                        <div class="bx_item_detail_scu_small_noadaptive <?= $full ?>">

													<span class="bx_item_section_name_gray">
														<?= $arProp["NAME"] ?>:
													</span>

                                            <div class="bx_scu_scroller_container">

                                                <div class="bx_scu">
                                                    <ul id="prop_<?= $arProp["CODE"] ?>_<?= $arItem["ID"] ?>"
                                                        style="width: 200%; margin-left:0%;"
                                                        class="sku_prop_list"
                                                        >
                                                        <?
                                                        foreach ($arProp["VALUES"] as $valueId => $arSkuValue):

                                                            $selected = "";
                                                            foreach ($arItem["PROPS"] as $arItemProp):
                                                                if ($arItemProp["CODE"] == $arItem["SKU_DATA"][$propId]["CODE"]) {
                                                                    if ($arItemProp["VALUE"] == $arSkuValue["NAME"] || $arItemProp["VALUE"] == $arSkuValue["XML_ID"]) {
                                                                        $selected = "bx_active";
                                                                    }
                                                                }
                                                            endforeach;
                                                            ?>
                                                            <li style="width:10%;"
                                                                class="sku_prop <?= $selected ?>"
                                                                data-value-id="<?= $arSkuValue["XML_ID"] ?>"
                                                                data-element="<?= $arItem["ID"] ?>"
                                                                data-property="<?= $arProp["CODE"] ?>"
                                                                >
                                                                <a href="javascript:void(0);">
                                                                    <span
                                                                        style="background-image:url(<?= $arSkuValue["PICT"]["SRC"] ?>)"></span>
                                                                </a>
                                                            </li>
                                                            <?
                                                        endforeach;
                                                        ?>
                                                    </ul>
                                                </div>

                                                <div class="bx_slide_left"
                                                     onclick="leftScroll('<?= $arProp["CODE"] ?>', <?= $arItem["ID"] ?>);"></div>
                                                <div class="bx_slide_right"
                                                     onclick="rightScroll('<?= $arProp["CODE"] ?>', <?= $arItem["ID"] ?>);"></div>
                                            </div>

                                        </div>
                                        <?
                                    else:
                                        ?>
                                        <div class="bx_item_detail_size_small_noadaptive <?= $full ?>">

													<span class="bx_item_section_name_gray">
														<?= $arProp["NAME"] ?>:
													</span>

                                            <div class="bx_size_scroller_container">
                                                <div class="bx_size">
                                                    <ul id="prop_<?= $arProp["CODE"] ?>_<?= $arItem["ID"] ?>"
                                                        style="width: 200%; margin-left:0%;"
                                                        class="sku_prop_list"
                                                        >
                                                        <?
                                                        foreach ($arProp["VALUES"] as $valueId => $arSkuValue):

                                                            $selected = "";
                                                            foreach ($arItem["PROPS"] as $arItemProp):
                                                                if ($arItemProp["CODE"] == $arItem["SKU_DATA"][$propId]["CODE"]) {
                                                                    if ($arItemProp["VALUE"] == $arSkuValue["NAME"]) {
                                                                        $selected = "bx_active";
                                                                    }
                                                                }
                                                            endforeach;
                                                            ?>
                                                            <li style="width:10%;"
                                                                class="sku_prop <?= $selected ?>"
                                                                data-value-id="<?= $arSkuValue["NAME"] ?>"
                                                                data-element="<?= $arItem["ID"] ?>"
                                                                data-property="<?= $arProp["CODE"] ?>"
                                                                >
                                                                <a href="javascript:void(0);"><?= $arSkuValue["NAME"] ?></a>
                                                            </li>
                                                            <?
                                                        endforeach;
                                                        ?>
                                                    </ul>
                                                </div>
                                                <div class="bx_slide_left"
                                                     onclick="leftScroll('<?= $arProp["CODE"] ?>', <?= $arItem["ID"] ?>);"></div>
                                                <div class="bx_slide_right"
                                                     onclick="rightScroll('<?= $arProp["CODE"] ?>', <?= $arItem["ID"] ?>);"></div>
                                            </div>

                                        </div>
                                        <?
                                    endif;
                                endforeach;
                            endif;
                            ?>
                        </td>
                        <?
                    }
                elseif ($arHeader["id"] == "QUANTITY"):
                    if ($is_complex) {
                        echo "<td class='custom' style='text-align:center;'>";
                        ?>
                        <input
                            type="text"
                            size="3"
                            id="QUANTITY_INPUT_<?= $arItem["ID"] ?>"
                            name="QUANTITY_INPUT_<?= $arItem["ID"] ?>"
                            size="2"
                            maxlength="18"
                            min="0"
                            <?= $max ?>
                            step="<?= $ratio ?>"
                            style="background:none;text-align:center;cursor:default;"
                            value="<?= $arItem["QUANTITY"] ?>"
                            readonly="true"

                            >
                        <input type="hidden" id="QUANTITY_<?= $arItem['ID'] ?>" name="QUANTITY_<?= $arItem['ID'] ?>"
                               value="<?= $arItem["QUANTITY"] ?>"/>
                        <?
                        echo "</td>";
                    } else {

                        ?>
                        <td class="custom">
                            <span><?= getColumnName($arHeader) ?>:</span>

                            <div class="centered price-block">
                                <div class="q-block">
                                    <?
                                    $ratio = isset($arItem["MEASURE_RATIO"]) ? $arItem["MEASURE_RATIO"] : 0;
                                    $max = isset($arItem["AVAILABLE_QUANTITY"]) ? "max=\"" . $arItem["AVAILABLE_QUANTITY"] . "\"" : "";
                                    $useFloatQuantity = ($arParams["QUANTITY_FLOAT"] == "Y") ? true : false;
                                    $useFloatQuantityJS = ($useFloatQuantity ? "true" : "false");
                                    if (!isset($arItem["MEASURE_RATIO"])) {
                                        $arItem["MEASURE_RATIO"] = 1;
                                    }

                                    if (
                                        floatval($arItem["MEASURE_RATIO"]) != 0
                                    ):
                                        ?>

                                        <a href="javascript:void(0);" class="arrow arrow-right plus"
                                           onclick="setComplexQuantity(<?= $arItem["ID"] ?>, <?= $arItem["MEASURE_RATIO"] ?>, 'up', <?= $useFloatQuantityJS ?>);"></a>
                                        <a href="javascript:void(0);" class="arrow arrow-left minus"
                                           onclick="setComplexQuantity(<?= $arItem["ID"] ?>, <?= $arItem["MEASURE_RATIO"] ?>, 'down', <?= $useFloatQuantityJS ?>);"></a>
                                    <? endif; ?>

                                    <input
                                        type="text"
                                        size="3"
                                        id="QUANTITY_INPUT_<?= $arItem["ID"] ?>"
                                        name="QUANTITY_INPUT_<?= $arItem["ID"] ?>"
                                        size="2"
                                        maxlength="18"
                                        min="0"
                                        <?= $max ?>
                                        step="<?= $ratio ?>"
                                        style="max-width: 50px"
                                        value="<?= $arItem["QUANTITY"] ?>"
                                        onchange="updateComplexQuantity('QUANTITY_INPUT_<?= $arItem["ID"] ?>', '<?= $arItem["ID"] ?>', <?= $ratio ?>, <?= $useFloatQuantityJS ?>)"
                                        >
                                </div>

                            </div>
                            <?
                            echo getMobileQuantityControl(
                                "QUANTITY_SELECT_" . $arItem["ID"],
                                "QUANTITY_SELECT_" . $arItem["ID"],
                                $arItem["QUANTITY"],
                                $arItem["AVAILABLE_QUANTITY"],
                                $useFloatQuantityJS,
                                $arItem["MEASURE_RATIO"],
                                $arItem["MEASURE_TEXT"]
                            );
                            ?>
                            <input type="hidden" id="QUANTITY_<?= $arItem['ID'] ?>" name="QUANTITY_<?= $arItem['ID'] ?>"
                                   value="<?= $arItem["QUANTITY"] ?>"/>
                        </td>

                        <?
                    }
                elseif ($arHeader["id"] == "PRICE"):
                elseif ($arHeader["id"] == "DISCOUNT"):
                    ?>
                    <td class="custom">
                        <span><?= getColumnName($arHeader) ?>:</span>

                        <div id="discount_value_<?= $arItem["ID"] ?>"><?= str_replace("руб.",
                                "<span class='ico-rub'></span>", $arItem["DISCOUNT_PRICE_PERCENT_FORMATED"]) ?></div>
                    </td>
                    <?
                elseif ($arHeader["id"] == "WEIGHT"):
                    ?>
                    <?
                    if ($arItem['PROPERTY_CUSTOM_WEIGHT_VALUE']) {
                        $arItem['WEIGHT'] = $arItem['PROPERTY_CUSTOM_WEIGHT_VALUE'];
                    }
                    ?>
                    <td class="custom">
                        <span><?= getColumnName($arHeader) ?>:</span>
                        <?= $arItem["WEIGHT"] > 0 ? $arItem["WEIGHT"] . "&nbsp;г" : "" ?>
                    </td>
                    <?
                else:
                    if ($arItem["IS_PARENT_PRODUCT"] && $arHeader["id"] == "PROPERTY_ARTNUMBER_VALUE") {
                        continue;
                    }
                    ?>
                    <td class="custom">
                        <span><?= getColumnName($arHeader) ?>:</span>
                        <? if ($arHeader["id"] == "PROPERTY_ARTNUMBER_VALUE") { ?><div class="num-wrapper"><span class="num"
                                                                                        id="num_<?= $arItem["ID"] ?>"><? } ?>
                            <? if ($arHeader["id"] == "SUM") {

                            ?>
                            <span class="sum" id="sum_<?= $arItem["ID"] ?>">
									<?
                                    } ?>
                                    <? echo($arHeader["id"] == "PROPERTY_ARTNUMBER_VALUE" ? str_pad($arItem["PRODUCT_SORT"],
                                        4, '0', STR_PAD_LEFT) : str_replace("руб.",
                                        "<span class='ico-rub' style='display:inline;'></span>",
                                        $arItem[$arHeader["id"]])); ?>
                                    <? if ($arHeader["id"] == "PROPERTY_ARTNUMBER_VALUE" || $arHeader["id"] == "SUM") { ?></span></div><? } ?>
                    </td>
                    <?
                endif;
            endforeach;

            //            var_dump($is_complex);
            //
            if (!$is_complex && ($bDelayColumn || $bDeleteColumn)):
                ?>
                <td class="control">
                    <?
                    if ($bDeleteColumn):
                        ?>
                        <a class="basket-item-remove"<?= ($arItem["IS_PARENT_PRODUCT"] ? " data-cid='{$arItem["CID"]}'" : "") ?>
                           href="<?= $arItem["IS_PARENT_PRODUCT"] ? ("/cart/complex/remove/?cid={$arItem["CID"]}") : str_replace("#ID#",
                               $arItem["ID"], $arUrls["delete"]) ?>" title="<?= GetMessage("SALE_DELETE") ?>"></a><br/>
                        <?
                    endif;
                    if ($bDelayColumn):
                        ?>
                        <a class="basket-item-remove"<?= ($arItem["IS_PARENT_PRODUCT"] ? " data-cid='{$arItem["CID"]}'" : "") ?>
                           href="<?= $arItem["IS_PARENT_PRODUCT"] ? ("/cart/complex/remove/?cid={$arItem["CID"]}") : str_replace("#ID#",
                               $arItem["ID"], $arUrls["delay"]) ?>" title="<?= GetMessage("SALE_DELAY") ?>"></a>
                        <?
                    endif;
                    ?>
                </td>
                <?
            endif;
            ?>
            <td class="margin"></td>
        </tr>
        <?
    endif;
}

if ($normalCount > 0):
    ?>
    <div id="basket_items_list">
        <div class="bg"></div>
        <div class="bx_ordercart_order_table_container">
            <table id="basket_items">
                <thead>
                <tr>
                    <td class="margin"></td>
                    <?
                    foreach ($arResult["GRID"]["HEADERS"] as $id => $arHeader):

                        $arHeaders[] = $arHeader["id"];

                        if ($arHeader['id'] == 'PROPERTY_CUSTOM_WEIGHT_VALUE') {
                            continue;
                        }

                        // remember which values should be shown not in the separate columns, but inside other columns
                        if (in_array($arHeader["id"], array("TYPE"))) {
                            $bPriceType = true;
                            continue;
                        } elseif ($arHeader["id"] == "PROPS") {
                            $bPropsColumn = true;
                            continue;
                        } elseif ($arHeader["id"] == "DELAY") {
                            $bDelayColumn = true;
                            continue;
                        } elseif ($arHeader["id"] == "DELETE") {
                            $bDeleteColumn = true;
                            continue;
                        } elseif ($arHeader["id"] == "PRICE") {
                            continue;
                        } elseif ($arHeader["id"] == "WEIGHT") {
                            $bWeightColumn = true;
                        }

                        if ($arHeader["id"] == "NAME"):
                            ?>
                            <td class="item" colspan="2" id="col_<?= getColumnId($arHeader) ?>">
                            <?
                        else:
                            ?>
                            <td class="custom" id="col_<?= getColumnId($arHeader) ?>">
                            <?
                        endif;
                        ?>
                        <?= getColumnName($arHeader) ?>
                        </td>
                        <?
                    endforeach;

                    if ($bDeleteColumn || $bDelayColumn):
                        ?>
                        <td class="custom"></td>
                        <?
                    endif;
                    ?>
                    <td class="margin"></td>
                </tr>
                </thead>

                <tbody>
                <?php
                $cids = array();

                foreach ($arResult["GRID"]["ROWS"] as $k => $arItem):

                    $found = false;
                    foreach ($arItem["PROPS"] as $prop) {
                        if ($prop["CODE"] == "cid_type") {
                            $arResult["GRID"]["ROWS"][$k]["CID_TYPE"] = $prop["VALUE"];
                        }

                        if ($prop["CODE"] == "cid") {
                            $arResult["GRID"]["ROWS"][$k]["CID"] = $prop["VALUE"];
                            if (!$cids[$prop["VALUE"]]) {
                                $cids[$prop["VALUE"]] = array(
                                    "NAME" => "Вок",
                                    "QUANTITY" => 0,
                                    "WEIGHT" => 0,
                                    "PRICE" => 0,
                                    "SUM" => 0
                                );
                            }
                            if (!$cids[$prop["VALUE"]]["QUANTITY"]) {
                                $cids[$prop["VALUE"]]["QUANTITY"] = $arItem["QUANTITY"];
                            }
                            $cids[$prop["VALUE"]]["WEIGHT"] += $arItem["WEIGHT"];
                            $cids[$prop["VALUE"]]["PRICE"] += $arItem["PRICE"];
                            $cids[$prop["VALUE"]]["SUM"] += $arItem["SUM"];
                            $found = true;
                        }
                    }
                    if ($found) {
                        continue;
                    }
###                                        showItem($arItem,$arResult,$arUrls);
                    showItem($arItem, $arResult, $arUrls, false);
                endforeach;

                foreach ($cids as $cid => $v) {
                    $showed = false;
                    foreach ($arResult["GRID"]["ROWS"] as $k => $arItem) {
                        if ($arItem["CID"] == $cid) {
                            if (!$showed) {
                                showItem(array_merge($arItem, array(
                                    "ID" => $cid,
                                    "IS_PARENT_PRODUCT" => true,
                                    "WEIGHT" => $v["WEIGHT"],
                                    "QUANTITY" => $v["QUANTITY"],
                                    "PRICE" => $v["PRICE"] . " руб.",
                                    "PRICE_FORMATED" => $v["PRICE"] . " руб.",
                                    "SUM" => $v["SUM"] . " руб.",
                                    "NAME" => $arItem["CID_TYPE"] == "wok" ? "Вок" : "Половинки пиццы",
                                    "DELAY" => "N",
                                    "CAN_BUY" => "Y"
###                                                )),$arResult,$arUrls);
                                )), $arResult, $arUrls, false);
                            }
                            showItem($arItem, $arResult, $arUrls, true);
                            $showed = true;
                        }
                    }
                }
                ?>
                </tbody>
            </table>
        </div>
        <input type="hidden" id="column_headers" value="<?= CUtil::JSEscape(implode($arHeaders, ",")) ?>"/>
        <input type="hidden" id="offers_props" value="<?= CUtil::JSEscape(implode($arParams["OFFERS_PROPS"], ",")) ?>"/>
        <input type="hidden" id="action_var" value="<?= CUtil::JSEscape($arParams["ACTION_VARIABLE"]) ?>"/>
        <input type="hidden" id="quantity_float" value="<?= $arParams["QUANTITY_FLOAT"] ?>"/>
        <input type="hidden" id="count_discount_4_all_quantity"
               value="<?= ($arParams["COUNT_DISCOUNT_4_ALL_QUANTITY"] == "Y") ? "Y" : "N" ?>"/>
        <input type="hidden" id="price_vat_show_value"
               value="<?= ($arParams["PRICE_VAT_SHOW_VALUE"] == "Y") ? "Y" : "N" ?>"/>
        <input type="hidden" id="hide_coupon" value="<?= ($arParams["HIDE_COUPON"] == "Y") ? "Y" : "N" ?>"/>
        <input type="hidden" id="coupon_approved" value="N"/>
        <input type="hidden" id="use_prepayment" value="<?= ($arParams["USE_PREPAYMENT"] == "Y") ? "Y" : "N" ?>"/>
    </div>

    <div class="bx_ordercart_order_pay">

        <div class="bx_ordercart_order_pay_left">
        </div>

        <div class="bx_ordercart_order_pay_right">
            <table class="bx_ordercart_order_sum">
                <thead>
                <tr>
                    <th class="total-title">Итого:</th>
                    <th>Кол-во</th>
                    <th style="display:none !important;">Вес</th>
                    <th>Цена</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td></td>
                    <td id="allCount">
                        <?
                        $totalCount = 0;
                        foreach ($arResult["ITEMS"]["AnDelCanBuy"] as $item) {
                            $totalCount += $item["QUANTITY"];
                        }
                        ?>
                        <?= $totalCount ?></td>
                    <? if ($bWeightColumn): ?>
                        <td class="custom_t2" id="allWeight_FORMATED"
                            style="display:none !important;"><?= (int)$arResult["allWeight"] > 0 ? $arResult["allWeight"] . " г" : "" ?></td>
                    <? endif; ?>
                    <td class="fwb" id="allSum_FORMATED">
                        <?= str_replace("руб.", "<span class='ico-rub'></span>",
                            str_replace(" ", "&nbsp;", $arResult["allSum_FORMATED"])) ?>
                    </td>
                </tr>
                </tbody>

            </table>
            <div style="clear:both;"></div>
            <?

            if (
                $_SERVER['REMOTE_ADDR'] == '158.46.22.198' or
                $_SERVER['REMOTE_ADDR'] == '46.149.226.65'
            ) {
                require($_SERVER['DOCUMENT_ROOT'] . '/include/show-discount-prices.php');
                if ($discount['ID']) {
                    echo '<div class="sum-note">* Сумма указана без учета скидки</div>';
                }
            }

            ?>
        </div>
    </div>
    <?
else:
    ?>
    <p class="bigger" style="text-align:center;padding:40px 0;"><?= GetMessage("SALE_NO_ITEMS"); ?></p>
    <?
endif;
?>