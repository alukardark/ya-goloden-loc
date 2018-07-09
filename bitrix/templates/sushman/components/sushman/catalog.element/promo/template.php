<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
?>
<?
$strMainID = "promo_" . $this->GetEditAreaId($arResult['ID']);
$arItemIDs = array(
    'ID' => $strMainID,
    'PICT' => $strMainID . '_pict',
    'DISCOUNT_PICT_ID' => $strMainID . '_dsc_pict',
    'STICKER_ID' => $strMainID . '_sticker',
    'OLD_PRICE' => $strMainID . '_old_price',
    'PRICE' => $strMainID . '_price',
    'DISCOUNT_PRICE' => $strMainID . '_price_discount',
    'QUANTITY' => $strMainID . '_quantity',
    'QUANTITY_DOWN' => $strMainID . '_quant_down',
    'QUANTITY_UP' => $strMainID . '_quant_up',
    'QUANTITY_MEASURE' => $strMainID . '_quant_measure',
    'QUANTITY_LIMIT' => $strMainID . '_quant_limit',
    'BUY_LINK' => $strMainID . '_buy_link',
    'ADD_BASKET_LINK' => $strMainID . '_add_basket_link',
    'COMPARE_LINK' => $strMainID . '_compare_link',
    'PROP' => $strMainID . '_prop_',
    'PROP_DIV' => $strMainID . '_skudiv',
    'DISPLAY_PROP_DIV' => $strMainID . '_sku_prop',
    'OFFER_GROUP' => $strMainID . '_set_group_',
    'BASKET_PROP_DIV' => $strMainID . '_basket_prop',
);
$strObName = 'ob' . preg_replace("/[^a-zA-Z0-9_]/", "x", $strMainID);

$strTitle = (
isset($arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_TITLE"]) && '' != $arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_TITLE"]
    ? $arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_TITLE"]
    : $arResult['NAME']
);
$strAlt = (
isset($arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_ALT"]) && '' != $arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_ALT"]
    ? $arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_ALT"]
    : $arResult['NAME']
);
$timeTo = '';
if ($arParams["PROMO_ACTIVE_TO"]) {
    $timeRemain = MakeTimeStamp($arParams["PROMO_ACTIVE_TO"]) - time();
    $timeTo = str_pad(intval($timeRemain / 3600), 2, "0", STR_PAD_LEFT) . ":" . str_pad(intval($timeRemain / 60) % 60,
            2, "0", STR_PAD_LEFT);
}

?>

<div class="inner-block" id="<? echo $arItemIDs['ID']; ?>">
    <div class="title">Лови момент</div>
    <div class="bcont">
        <?
        if ($arResult["DETAIL_PICTURE"]["ID"]) {
            $arUserPhoto = CFile::GetFileArray($arResult["DETAIL_PICTURE"]["ID"]);
            $preview = CFile::ResizeImageGet($arUserPhoto, array("width" => 230, "height" => 170),
                BX_RESIZE_IMAGE_PROPORTIONAL);
        } else {
            $preview['src'] = $arResult["DETAIL_PICTURE"]["SRC"];
        }
        ?>
        <a href="<?= $arResult['DETAIL_PAGE_URL']; ?>" data-popup_href="/menu/detail.php?ELEMENT_ID=<?= $arResult["ID"] ?>"
           id="<? echo $arItemIDs['PICT']; ?>" class="pic" title="<? echo $strTitle; ?>"
           <? if ($preview['src']) { ?>style="background-image:url(<?= $preview['src'] ?>);"<? } ?>></a>

        <div class="t1"><?= $arResult["NAME"] ?></div>
        <div class="price">
            <?
            $boolDiscountShow = (0 < $arResult['MIN_PRICE']['DISCOUNT_DIFF']);
            ?>
            <div class="old-price"
                 id="<? echo $arItemIDs['OLD_PRICE']; ?>"><? echo(true || $boolDiscountShow ? str_replace("руб.",
                    "<span class='ico-rub'></span>", $arResult['MIN_PRICE']['PRINT_VALUE']) : ''); ?></div>
            <div class="current-price" id="<? echo $arItemIDs['PRICE']; ?>"><? echo str_replace("руб.",
                    "<span class='ico-rub'></span>", $arResult['MIN_PRICE']['PRINT_DISCOUNT_VALUE']); ?></div>
        </div>
    </div>
    <div class="bottom-cont">
        <div class="subtitle">Осталось</div>
        <div class="centered">
            <?
            if (isset($arResult['OFFERS']) && !empty($arResult['OFFERS'])) {
                global $city, $cities;
                $cityCode = $cities[$city]["CODE"];
                $arProp = $arResult['SKU_PROPS']['CITY'];
                $arResult['OFFERS_SELECTED'] = -1;
                foreach ($arResult['OFFERS'] as $k => $v) {
                    if ($cityCode == $v["PROPERTIES"]["CITY"]["VALUE"]) {
                        $arResult['OFFERS_SELECTED'] = $k;
                    }
                }
                $canBuy = $arResult['OFFERS_SELECTED'] == -1 ? false : $arResult['OFFERS'][$arResult['OFFERS_SELECTED']]['CAN_BUY'];
                $remains = $arResult['OFFERS'][$arResult['OFFERS_SELECTED']]['CATALOG_QUANTITY'];
            } else {
                $canBuy = $arResult['CAN_BUY'];
                $remains = $arResult['CATALOG_QUANTITY'];
            }
            if ($canBuy) {
                $buyBtnMessage = ('' != $arParams['MESS_BTN_BUY'] ? $arParams['MESS_BTN_BUY'] : GetMessage('CT_BCE_CATALOG_BUY'));
                $buyBtnClass = 'bx_big bx_bt_button bx_cart';
            } else {
                $buyBtnMessage = ('' != $arParams['MESS_NOT_AVAILABLE'] ? $arParams['MESS_NOT_AVAILABLE'] : GetMessageJS('CT_BCE_CATALOG_NOT_AVAILABLE'));
                $buyBtnClass = 'bx_big bx_bt_button_type_2 bx_cart';
            }
            ?>
            <div class="timer">
                <? if ($timeTo) { ?>
                    <div class="time">
                        <div class="label">времени</div>
                        <div class="data"><?= $timeTo ?></div>
                    </div>
                <? } ?>
                <div class="q">
                    <div class="label">штук</div>
                    <div class="data"><?= $remains ?></div>
                </div>
                <div class="clear"></div>
            </div>

            <?
            if ('Y' == $arParams['USE_PRODUCT_QUANTITY'] && $canBuy) {
                ?>
                <div class="q-block" style="display:none;">
                    <span id="<? echo $arItemIDs['QUANTITY_DOWN']; ?>" class="arrow arrow-left"></span><span
                        id="<? echo $arItemIDs['QUANTITY_UP']; ?>" class="arrow arrow-right"></span>
                    <input type="text" class="bx_col_input" id="<? echo $arItemIDs['QUANTITY']; ?>"
                           value="<? echo(isset($arResult['OFFERS']) && !empty($arResult['OFFERS'])
                               ? 1
                               : $arResult['CATALOG_MEASURE_RATIO']
                           ) ?>">
                </div>
                <?
            }
            ?>

            <a href="javascript:void(0);" class="buy btn <?= $buyBtnClass; ?>"
               id="<?= $arItemIDs['BUY_LINK']; ?>"><span></span><? echo $buyBtnMessage; ?></a>
        </div>

    </div>
</div>

<?
if (isset($arResult['OFFERS']) && !empty($arResult['OFFERS']) && !empty($arResult['OFFERS_PROP'])) {
    $arSkuProps = array();
    ?>
    <div class="item_info_section" style="display:none;" id="<? echo $arItemIDs['PROP_DIV']; ?>">
        <?

        foreach ($arResult['SKU_PROPS'] as &$arProp) {
            if (!isset($arResult['OFFERS_PROP'][$arProp['CODE']])) {
                continue;
            }
            $arSkuProps[] = array(
                'ID' => $arProp['ID'],
                'SHOW_MODE' => $arProp['SHOW_MODE'],
                'VALUES_COUNT' => $arProp['VALUES_COUNT']
            );
            if ('TEXT' == $arProp['SHOW_MODE']) {
                if (5 < $arProp['VALUES_COUNT']) {
                    $strClass = 'bx_item_detail_size full';
                    $strOneWidth = (100 / $arProp['VALUES_COUNT']) . '%';
                    $strWidth = (20 * $arProp['VALUES_COUNT']) . '%';
                    $strSlideStyle = '';
                } else {
                    $strClass = 'bx_item_detail_size';
                    $strOneWidth = '20%';
                    $strWidth = '100%';
                    $strSlideStyle = 'display: none;';
                }
                ?>
                <div class="<? echo $strClass; ?>" id="<? echo $arItemIDs['PROP'] . $arProp['ID']; ?>_cont">
                    <span class="bx_item_section_name_gray"><? echo htmlspecialcharsex($arProp['NAME']); ?></span>

                    <div class="bx_size_scroller_container">
                        <div class="bx_size">
                            <ul id="<? echo $arItemIDs['PROP'] . $arProp['ID']; ?>_list"
                                style="width: <? echo $strWidth; ?>;margin-left:0%;">
                                <?
                                foreach ($arProp['VALUES'] as $arOneValue) {
                                    ?>
                                    <li
                                        data-treevalue="<? echo $arProp['ID'] . '_' . $arOneValue['ID']; ?>"
                                        data-onevalue="<? echo $arOneValue['ID']; ?>"
                                        style="width: <? echo $strOneWidth; ?>; display: none;"
                                        ><i></i><span
                                            class="cnt"><? echo htmlspecialcharsex($arOneValue['NAME']); ?></span></li>
                                    <?
                                }
                                ?>
                            </ul>
                        </div>
                        <div class="bx_slide_left" style="<? echo $strSlideStyle; ?>"
                             id="<? echo $arItemIDs['PROP'] . $arProp['ID']; ?>_left"
                             data-treevalue="<? echo $arProp['ID']; ?>"></div>
                        <div class="bx_slide_right" style="<? echo $strSlideStyle; ?>"
                             id="<? echo $arItemIDs['PROP'] . $arProp['ID']; ?>_right"
                             data-treevalue="<? echo $arProp['ID']; ?>"></div>
                    </div>
                </div>
                <?
            } elseif ('PICT' == $arProp['SHOW_MODE']) {
                if (5 < $arProp['VALUES_COUNT']) {
                    $strClass = 'bx_item_detail_scu full';
                    $strOneWidth = (100 / $arProp['VALUES_COUNT']) . '%';
                    $strWidth = (20 * $arProp['VALUES_COUNT']) . '%';
                    $strSlideStyle = '';
                } else {
                    $strClass = 'bx_item_detail_scu';
                    $strOneWidth = '20%';
                    $strWidth = '100%';
                    $strSlideStyle = 'display: none;';
                }
                ?>
                <div class="<? echo $strClass; ?>" id="<? echo $arItemIDs['PROP'] . $arProp['ID']; ?>_cont">
                    <span class="bx_item_section_name_gray"><? echo htmlspecialcharsex($arProp['NAME']); ?></span>

                    <div class="bx_scu_scroller_container">
                        <div class="bx_scu">
                            <ul id="<? echo $arItemIDs['PROP'] . $arProp['ID']; ?>_list"
                                style="width: <? echo $strWidth; ?>;margin-left:0%;">
                                <?
                                foreach ($arProp['VALUES'] as $arOneValue) {
                                    ?>
                                    <li
                                        data-treevalue="<? echo $arProp['ID'] . '_' . $arOneValue['ID'] ?>"
                                        data-onevalue="<? echo $arOneValue['ID']; ?>"
                                        style="width: <? echo $strOneWidth; ?>; padding-top: <? echo $strOneWidth; ?>; display: none;"
                                        ><i title="<? echo htmlspecialcharsbx($arOneValue['NAME']); ?>"></i>
				<span class="cnt"><span class="cnt_item"
                                        style="background-image:url('<? echo $arOneValue['PICT']['SRC']; ?>');"
                                        title="<? echo htmlspecialcharsbx($arOneValue['NAME']); ?>"
                        ></span></span></li>
                                    <?
                                }
                                ?>
                            </ul>
                        </div>
                        <div class="bx_slide_left" style="<? echo $strSlideStyle; ?>"
                             id="<? echo $arItemIDs['PROP'] . $arProp['ID']; ?>_left"
                             data-treevalue="<? echo $arProp['ID']; ?>"></div>
                        <div class="bx_slide_right" style="<? echo $strSlideStyle; ?>"
                             id="<? echo $arItemIDs['PROP'] . $arProp['ID']; ?>_right"
                             data-treevalue="<? echo $arProp['ID']; ?>"></div>
                    </div>
                </div>
                <?
            }
        }
        unset($arProp);
        ?>
    </div>
    <?
}
?>


<?
if (isset($arResult['OFFERS']) && !empty($arResult['OFFERS']) && !empty($arResult['OFFERS_PROP'])) {
    foreach ($arResult['JS_OFFERS'] as &$arOneJS) {
        if ($arOneJS['PRICE']['DISCOUNT_VALUE'] != $arOneJS['PRICE']['VALUE']) {
            $arOneJS['PRICE']['PRINT_DISCOUNT_DIFF'] = GetMessage('ECONOMY_INFO',
                array('#ECONOMY#' => $arOneJS['PRICE']['PRINT_DISCOUNT_DIFF']));
            $arOneJS['PRICE']['DISCOUNT_DIFF_PERCENT'] = -$arOneJS['PRICE']['DISCOUNT_DIFF_PERCENT'];
        }
        $strProps = '';
        if ($arResult['SHOW_OFFERS_PROPS']) {
            if (!empty($arOneJS['DISPLAY_PROPERTIES'])) {
                foreach ($arOneJS['DISPLAY_PROPERTIES'] as $arOneProp) {
                    $strProps .= '<dt>' . $arOneProp['NAME'] . '</dt><dd>' . (
                        is_array($arOneProp['VALUE'])
                            ? implode(' / ', $arOneProp['VALUE'])
                            : $arOneProp['VALUE']
                        ) . '</dd>';
                }
            }
        }
        $arOneJS['DISPLAY_PROPERTIES'] = $strProps;
    }
    if (isset($arOneJS)) {
        unset($arOneJS);
    }
    $arJSParams = array(
        'CONFIG' => array(
            'USE_CATALOG' => $arResult['CATALOG'],
            'SHOW_QUANTITY' => $arParams['USE_PRODUCT_QUANTITY'],
            'SHOW_PRICE' => true,
            'SHOW_DISCOUNT_PERCENT' => ('Y' == $arParams['SHOW_DISCOUNT_PERCENT']),
            'SHOW_OLD_PRICE' => ('Y' == $arParams['SHOW_OLD_PRICE']),
            'DISPLAY_COMPARE' => ('Y' == $arParams['DISPLAY_COMPARE']),
            'SHOW_SKU_PROPS' => $arResult['SHOW_OFFERS_PROPS'],
            'OFFER_GROUP' => $arResult['OFFER_GROUP'],
            'MAIN_PICTURE_MODE' => $arParams['DETAIL_PICTURE_MODE']
        ),
        'PRODUCT_TYPE' => $arResult['CATALOG_TYPE'],
        'VISUAL' => array(
            'ID' => $arItemIDs['ID'],
        ),
        'DEFAULT_PICTURE' => array(
            'PREVIEW_PICTURE' => $arResult['DEFAULT_PICTURE'],
            'DETAIL_PICTURE' => $arResult['DEFAULT_PICTURE']
        ),
        'PRODUCT' => array(
            'ID' => $arResult['ID'],
            'NAME' => $arResult['~NAME']
        ),
        'BASKET' => array(
            'QUANTITY' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
            'BASKET_URL' => $arParams['BASKET_URL'],
            'SKU_PROPS' => $arResult['OFFERS_PROP_CODES']
        ),
        'OFFERS' => $arResult['JS_OFFERS'],
        'OFFER_SELECTED' => $arResult['OFFERS_SELECTED'],
        'TREE_PROPS' => $arSkuProps
    );
} else {
    $emptyProductProperties = empty($arResult['PRODUCT_PROPERTIES']);
    if ('Y' == $arParams['ADD_PROPERTIES_TO_BASKET'] && !$emptyProductProperties) {
        ?>
        <div id="<? echo $arItemIDs['BASKET_PROP_DIV']; ?>" style="display: none;">
            <?
            if (!empty($arResult['PRODUCT_PROPERTIES_FILL'])) {
                foreach ($arResult['PRODUCT_PROPERTIES_FILL'] as $propID => $propInfo) {
                    ?>
                    <input
                        type="hidden"
                        name="<? echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<? echo $propID; ?>]"
                        value="<? echo htmlspecialcharsbx($propInfo['ID']); ?>"
                        >
                    <?
                    if (isset($arResult['PRODUCT_PROPERTIES'][$propID])) {
                        unset($arResult['PRODUCT_PROPERTIES'][$propID]);
                    }
                }
            }
            $emptyProductProperties = empty($arResult['PRODUCT_PROPERTIES']);
            if (!$emptyProductProperties) {
                ?>
                <table>
                    <?
                    foreach ($arResult['PRODUCT_PROPERTIES'] as $propID => $propInfo) {
                        ?>
                        <tr>
                            <td><? echo $arResult['PROPERTIES'][$propID]['NAME']; ?></td>
                            <td>
                                <?
                                if (
                                    'L' == $arResult['PROPERTIES'][$propID]['PROPERTY_TYPE']
                                    && 'C' == $arResult['PROPERTIES'][$propID]['LIST_TYPE']
                                ) {
                                    foreach ($propInfo['VALUES'] as $valueID => $value) {
                                        ?><label><input
                                        type="radio"
                                        name="<? echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<? echo $propID; ?>]"
                                        value="<? echo $valueID; ?>"
                                        <? echo($valueID == $propInfo['SELECTED'] ? '"checked"' : ''); ?>
                                        ><? echo $value; ?></label><br><?
                                    }
                                } else {
                                    ?><select
                                    name="<? echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<? echo $propID; ?>]"><?
                                    foreach ($propInfo['VALUES'] as $valueID => $value) {
                                        ?>
                                        <option
                                        value="<? echo $valueID; ?>"
                                        <? echo($valueID == $propInfo['SELECTED'] ? '"selected"' : ''); ?>
                                        ><? echo $value; ?></option><?
                                    }
                                    ?></select><?
                                }
                                ?>
                            </td>
                        </tr>
                        <?
                    }
                    ?>
                </table>
                <?
            }
            ?>
        </div>
        <?
    }
    $arJSParams = array(
        'CONFIG' => array(
            'USE_CATALOG' => $arResult['CATALOG'],
            'SHOW_QUANTITY' => $arParams['USE_PRODUCT_QUANTITY'],
            'SHOW_PRICE' => (isset($arResult['MIN_PRICE']) && !empty($arResult['MIN_PRICE']) && is_array($arResult['MIN_PRICE'])),
            'SHOW_DISCOUNT_PERCENT' => ('Y' == $arParams['SHOW_DISCOUNT_PERCENT']),
            'SHOW_OLD_PRICE' => ('Y' == $arParams['SHOW_OLD_PRICE']),
            'DISPLAY_COMPARE' => ('Y' == $arParams['DISPLAY_COMPARE']),
            'MAIN_PICTURE_MODE' => $arParams['DETAIL_PICTURE_MODE']
        ),
        'VISUAL' => array(
            'ID' => $arItemIDs['ID'],
        ),
        'PRODUCT_TYPE' => $arResult['CATALOG_TYPE'],
        'PRODUCT' => array(
            'ID' => $arResult['ID'],
            'PICT' => $arFirstPhoto,
            'NAME' => $arResult['~NAME'],
            'SUBSCRIPTION' => true,
            'PRICE' => $arResult['MIN_PRICE'],
            'SLIDER_COUNT' => $arResult['MORE_PHOTO_COUNT'],
            'SLIDER' => $arResult['MORE_PHOTO'],
            'CAN_BUY' => $arResult['CAN_BUY'],
            'CHECK_QUANTITY' => $arResult['CHECK_QUANTITY'],
            'QUANTITY_FLOAT' => is_double($arResult['CATALOG_MEASURE_RATIO']),
            'MAX_QUANTITY' => $arResult['CATALOG_QUANTITY'],
            'STEP_QUANTITY' => $arResult['CATALOG_MEASURE_RATIO'],
            'BUY_URL' => $arResult['~BUY_URL'],
        ),
        'BASKET' => array(
            'ADD_PROPS' => ('Y' == $arParams['ADD_PROPERTIES_TO_BASKET']),
            'QUANTITY' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
            'PROPS' => $arParams['PRODUCT_PROPS_VARIABLE'],
            'EMPTY_PROPS' => $emptyProductProperties,
            'BASKET_URL' => $arParams['BASKET_URL']
        )
    );
    unset($emptyProductProperties);
}
?>
<script type="text/javascript">
    var <? echo $strObName; ?> =
    new JCCatalogElement(<? echo CUtil::PhpToJSObject($arJSParams, false, true); ?>);
    BX.message({
        MESS_BTN_BUY: '<? echo ('' != $arParams['MESS_BTN_BUY'] ? CUtil::JSEscape($arParams['MESS_BTN_BUY']) : GetMessageJS('CT_BCE_CATALOG_BUY')); ?>',
        MESS_BTN_ADD_TO_BASKET: '<? echo ('' != $arParams['MESS_BTN_ADD_TO_BASKET'] ? CUtil::JSEscape($arParams['MESS_BTN_ADD_TO_BASKET']) : GetMessageJS('CT_BCE_CATALOG_ADD')); ?>',
        MESS_NOT_AVAILABLE: '<? echo ('' != $arParams['MESS_NOT_AVAILABLE'] ? CUtil::JSEscape($arParams['MESS_NOT_AVAILABLE']) : GetMessageJS('CT_BCE_CATALOG_NOT_AVAILABLE')); ?>',
        TITLE_ERROR: '<? echo GetMessageJS('CT_BCE_CATALOG_TITLE_ERROR') ?>',
        TITLE_BASKET_PROPS: '<? echo GetMessageJS('CT_BCE_CATALOG_TITLE_BASKET_PROPS') ?>',
        BASKET_UNKNOWN_ERROR: '<? echo GetMessageJS('CT_BCE_CATALOG_BASKET_UNKNOWN_ERROR') ?>',
        BTN_SEND_PROPS: '<? echo GetMessageJS('CT_BCE_CATALOG_BTN_SEND_PROPS'); ?>',
        BTN_MESSAGE_CLOSE: '<? echo GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_CLOSE') ?>',
        SITE_ID: '<? echo SITE_ID; ?>'
    });
</script>