<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>

<?
if ($arResult["DETAIL_PICTURE"]) {
    $pict = CFile::ResizeImageGet($arResult["DETAIL_PICTURE"], array("width" => 731, "height" => 9999));
    $img = $pict['src'];
}
?>
    <div id="wok-container-wrapper">
        <div id="wok-container" <?= (!$img ? " class='no-image'" : "") ?>>
            <div class="wok-pic">
                <div class="wok-pic-filling"></div>
            </div>
            <div class="wok-title">Соберите свою коробочку!</div>
            <ul class="steps">
                <li id="wok-selected-garnish"><span class="num">1</span><span class="t">Выберите гарнир</span></li>
                <li id="wok-selected-filling"><span class="num">2</span><span class="t">Выберите начинку</span></li>
            </ul>
            <a href="#" class="buy">купить</a>

            <div class="price-block">
                <span class="weight">300 г</span>
                <span class="price">230 <span class="ico-rub"></span></span>
            </div>
        </div>
    </div>

<?

if (!empty($arResult['ITEMS'])) {
    if ($arParams["DISPLAY_TOP_PAGER"]) {
        ?><? echo $arResult["NAV_STRING"]; ?><?
    }

    $strElementEdit = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT");
    $strElementDelete = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE");
    $arElementDeleteParams = array("CONFIRM" => GetMessage('CT_BCS_TPL_ELEMENT_DELETE_CONFIRM'));
    ?>
    <div id="wok-garnish">
        <h2>Добавляем гарнир</h2><br/>
        <ul class="bx_catalog_list_home items"><?
            foreach ($arResult['ITEMS'] as $key => $arItem) {
                $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], $strElementEdit);
                $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], $strElementDelete,
                    $arElementDeleteParams);
                $orig_id = $arItem['ID'];
                $strMainID = $this->GetEditAreaId($arItem['ID']);

                $arItemIDs = array(
                    'ID' => $strMainID,
                    'PICT' => $strMainID . '_pict',
                    'SECOND_PICT' => $strMainID . '_secondpict',
                    'QUANTITY' => $strMainID . '_quantity',
                    'QUANTITY_DOWN' => $strMainID . '_quant_down',
                    'QUANTITY_UP' => $strMainID . '_quant_up',
                    'QUANTITY_MEASURE' => $strMainID . '_quant_measure',
                    'BUY_LINK' => $strMainID . '_buy_link',
                    'SUBSCRIBE_LINK' => $strMainID . '_subscribe',
                    'PRICE' => $strMainID . '_price',
                    'DSC_PERC' => $strMainID . '_dsc_perc',
                    'SECOND_DSC_PERC' => $strMainID . '_second_dsc_perc',
                    'PROP_DIV' => $strMainID . '_sku_tree',
                    'PROP' => $strMainID . '_prop_',
                    'DISPLAY_PROP_DIV' => $strMainID . '_sku_prop',
                    'BASKET_PROP_DIV' => $strMainID . '_basket_prop',
                );

                $strObName = 'ob' . preg_replace("/[^a-zA-Z0-9_]/", "x", $strMainID);

                $strTitle = (
                isset($arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"]) && '' != isset($arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"])
                    ? $arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"]
                    : $arItem['NAME']
                );
                ?>
                <li class="bx_catalog_item_container wok-garnish" id="<? echo $strMainID; ?>">

                <?
                $arJSParams = array(
                    'PRODUCT_TYPE' => $arItem['CATALOG_TYPE'],
                    'SHOW_QUANTITY' => $arParams['USE_PRODUCT_QUANTITY'],
                    'SHOW_ADD_BASKET_BTN' => false,
                    'SHOW_BUY_BTN' => true,
                    'SHOW_ABSENT' => true,
                    'PRODUCT' => array(
                        'ID' => $arItem['ID'],
                        'NAME' => $arItem['~NAME'],
                        'PICT' => $arItem['PREVIEW_PICTURE'],
                        'CAN_BUY' => $arItem["CAN_BUY"],
                        'SUBSCRIPTION' => ('Y' == $arItem['CATALOG_SUBSCRIPTION']),
                        'CHECK_QUANTITY' => $arItem['CHECK_QUANTITY'],
                        'MAX_QUANTITY' => $arItem['CATALOG_QUANTITY'],
                        'STEP_QUANTITY' => $arItem['CATALOG_MEASURE_RATIO'],
                        'QUANTITY_FLOAT' => is_double($arItem['CATALOG_MEASURE_RATIO']),
                        'ADD_URL' => $arItem['~ADD_URL'],
                        'SUBSCRIBE_URL' => $arItem['~SUBSCRIBE_URL']
                    ),
                    'BASKET' => array(
                        'ADD_PROPS' => ('Y' == $arParams['ADD_PROPERTIES_TO_BASKET']),
                        'QUANTITY' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
                        'PROPS' => $arParams['PRODUCT_PROPS_VARIABLE'],
                        'EMPTY_PROPS' => $emptyProductProperties
                    ),
                    'VISUAL' => array(
                        'ID' => $arItemIDs['ID'],
                        'PICT_ID' => $arItemIDs['PICT'],
                        'QUANTITY_ID' => $arItemIDs['QUANTITY'],
                        'QUANTITY_UP_ID' => $arItemIDs['QUANTITY_UP'],
                        'QUANTITY_DOWN_ID' => $arItemIDs['QUANTITY_DOWN'],
                        'PRICE_ID' => $arItemIDs['PRICE'],
                        'BUY_ID' => $arItemIDs['BUY_LINK'],
                        'BASKET_PROP_DIV' => $arItemIDs['BASKET_PROP_DIV']
                    ),
                    'LAST_ELEMENT' => $arItem['LAST_ELEMENT']
                );
                unset($emptyProductProperties);
                ?>
                <script type="text/javascript">
                var <? echo $strObName; ?> =
                new JCCatalogSection(<? echo CUtil::PhpToJSObject($arJSParams, false, true); ?>);
                </script>
                <!-- <? if ($arItem["SORT"]) { ?><span class="num"><?= str_pad($arItem["SORT"], 4, '0',
                    STR_PAD_LEFT) ?></span><? } //['DISPLAY_PROPERTIES']['ARTNUMBER']["VALUE"]
                ?> -->
                <? $APPLICATION->IncludeComponent("bitrix:catalog.brandblock", "menu", array(
                    "IBLOCK_TYPE" => $arItem['IBLOCK_TYPE'],
                    "IBLOCK_ID" => $arItem['IBLOCK_ID'],
                    "ELEMENT_ID" => $arItem['ID'],
                    "ELEMENT_CODE" => "",
                    "PROP_CODE" => "TYPE_REF",
                    "CACHE_TYPE" => $arParams['CACHE_TYPE'],
                    "CACHE_TIME" => $arParams['CACHE_TIME'],
                    "CACHE_GROUPS" => $arParams['CACHE_GROUPS'],
                    "WIDTH" => "",
                    "HEIGHT" => ""
                ),
                    $component,
                    array("HIDE_ICONS" => "Y")
                ); ?>
                <?
                if ($arItem["PREVIEW_PICTURE"]["ID"]) {
                    $arUserPhoto = CFile::GetFileArray($arItem["PREVIEW_PICTURE"]["ID"]);
                    $preview = CFile::ResizeImageGet($arUserPhoto, array("width" => 155, "height" => 155),
                        BX_RESIZE_IMAGE_EXACT);
                } else {
                    $preview['src'] = $arItem["PREVIEW_PICTURE"]["SRC"];
                }

                $constPic["src"] = SUSHMAN_MAIN_PATH . "/img/wok-pic1.png";
                if ($arItem["PROPERTIES"]["CONST_PIC"]["VALUE"]) {
                    $arConstPic = CFile::GetFileArray($arItem["PROPERTIES"]["CONST_PIC"]["VALUE"]);
                    $constPic = CFile::ResizeImageGet($arConstPic, array("width" => 390, "height" => 360),
                        BX_RESIZE_IMAGE_PROPORTIONAL);
                }
                ?>
                <a target="_blank" href="<? echo $arItem['DETAIL_PAGE_URL']; ?>"
                   data-popup_href="/menu/detail.php?SECTION_ID=<?= $arItem["SECTION_ID"] ?>&ELEMENT_ID=<?= $arItem["ID"] ?>"
                   id="<? echo $arItemIDs['PICT']; ?>" title="<? echo $strTitle; ?>"><span
                        class="pic" <?= ($constPic["src"] ? " const_pic='{$constPic["src"]}'" : "") ?>><img
                            src="<?= $preview['src'] ?>"/></span>
                    <span class="title valigned"><span><? echo $arItem['NAME']; ?></span></span></a>
                <?
                if (false && 'Y' == $arParams['SHOW_DISCOUNT_PERCENT']) {
                    ?>
                    <div
                        id="<? echo $arItemIDs['DSC_PERC']; ?>"
                        class="bx_stick_disc right bottom"
                        style="display:<? echo(0 < $arItem['MIN_PRICE']['DISCOUNT_DIFF_PERCENT'] ? '' : 'none'); ?>;">
                        -<? echo $arItem['MIN_PRICE']['DISCOUNT_DIFF_PERCENT']; ?>%
                    </div>
                    <?
                }
                if (false && $arItem['LABEL']) {
                    ?>
                    <div class="bx_stick average left top"
                         title="<? echo $arItem['LABEL_VALUE']; ?>"><? echo $arItem['LABEL_VALUE']; ?></div>
                    <?
                }
                ?>

                <div class="price-block">
                <div class="short-desc">
                    <?= $arItem["PREVIEW_TEXT"] ?>
                </div>
                <div class="price-block2">
	<span class="price bx_price" id="<? echo $arItemIDs['PRICE']; ?>"><?
        if (isset($arItem['OFFERS']) && !empty($arItem['OFFERS'])) {
            global $city, $cities;
            $cityCode = $cities[$city]["CODE"];
            $arItem['OFFERS_SELECTED'] = -1;
            foreach ($arItem['OFFERS'] as $k => $v) {
                if ($cityCode == $v["PROPERTIES"]["CITY"]["VALUE"]) {
                    $arItem['OFFERS_SELECTED'] = $k;
                }
            }
            $arItem = $arItem['OFFERS'][$arItem["OFFERS_SELECTED"]];
        }

        if (!empty($arItem['MIN_PRICE'])) {
            echo $priceShowed = str_replace("руб.", "<span class='ico-rub'></span>", $arItem['MIN_PRICE']['PRINT_DISCOUNT_VALUE']);

            if ('Y' == $arParams['SHOW_OLD_PRICE'] && $arItem['MIN_PRICE']['DISCOUNT_VALUE'] < $arItem['MIN_PRICE']['VALUE']) {
                ?> <span><? echo $arItem['MIN_PRICE']['PRINT_VALUE']; ?></span><?
            }
        }
        ?></span>
                    <?

                    $page = 'wok-item';
                    require($_SERVER['DOCUMENT_ROOT'].'/include/show-discount-prices.php');
                    echo $discountHtml;

                    $item_weight = $arItem["CATALOG_WEIGHT"];
                    if ($item_weight) { ?>
                        <span class="weight"><?= $item_weight ?>&nbsp;г</span>
                    <? } ?>
                    <div class="clear"></div>
                </div>
                <div class="q-block">
                    <?
                    if (!isset($arItem['OFFERS']) || empty($arItem['OFFERS'])) {
                        if ($arItem['CAN_BUY']) {
                            if ('Y' == $arParams['USE_PRODUCT_QUANTITY']) {
                                ?>
                                <span id="<? echo $arItemIDs['QUANTITY_DOWN']; ?>" class="arrow arrow-left"></span><span
                                    id="<? echo $arItemIDs['QUANTITY_UP']; ?>" class="arrow arrow-right"></span>
                                <input type="text" class="bx_col_input" id="<? echo $arItemIDs['QUANTITY']; ?>"
                                       name="<? echo $arParams["PRODUCT_QUANTITY_VARIABLE"]; ?>"
                                       value="<? echo $arItem['CATALOG_MEASURE_RATIO']; ?>">
                                <?
                            }
                            ?>
                            <?
                        } else {
                            ?><span class="bx_notavailable"><?
                            echo('' != $arParams['MESS_NOT_AVAILABLE'] ? $arParams['MESS_NOT_AVAILABLE'] : GetMessage('CT_BCS_TPL_MESS_PRODUCT_NOT_AVAILABLE'));
                            ?></span><?
                        }
                    }
                    ?>
                </div>

                </div><?
                if (true || !isset($arItem['OFFERS']) || empty($arItem['OFFERS'])) {
                    if ($arItem['CAN_BUY']) {
                        ?>
                        <a class="addto bx_bt_button bx_medium" href="#" rel="nofollow"
                           data-product-id="<?= $arItem["ID"] ?>" data-id="<?= $orig_id ?>"></a>
                        <?
                    } else {
                        if ('Y' == $arParams['PRODUCT_SUBSCRIPTION'] && 'Y' == $arItem['CATALOG_SUBSCRIPTION']) {
                            ?>
                            <a
                                id="<? echo $arItemIDs['SUBSCRIBE_LINK']; ?>"
                                class="buy bx_bt_button_type_2 bx_medium"
                                href="javascript:void(0)"></a>
                            <?
                        }
                    }
                    if (isset($arItem['DISPLAY_PROPERTIES']) && !empty($arItem['DISPLAY_PROPERTIES'])) { /*
?>
			<div class="bx_catalog_item_articul">
<?
			foreach ($arItem['DISPLAY_PROPERTIES'] as $arOneProp)
			{
				?><br><span style="display:none;"><? print_r($arOneProp);?></span><strong><? echo $arOneProp['NAME']; ?></strong> <?
					echo (
						is_array($arOneProp['DISPLAY_VALUE'])
						? implode('<br>', $arOneProp['DISPLAY_VALUE'])
						: $arOneProp['DISPLAY_VALUE']
					);
			}
?>
			</div>
<?
                    */
                    }
                    $emptyProductProperties = empty($arItem['PRODUCT_PROPERTIES']);
                    if ('Y' == $arParams['ADD_PROPERTIES_TO_BASKET'] && !$emptyProductProperties) {
                        ?>
                        <div id="<? echo $arItemIDs['BASKET_PROP_DIV']; ?>" style="display: none;">
                            <?
                            if (!empty($arItem['PRODUCT_PROPERTIES_FILL'])) {
                                foreach ($arItem['PRODUCT_PROPERTIES_FILL'] as $propID => $propInfo) {
                                    ?>
                                    <input
                                        type="hidden"
                                        name="<? echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<? echo $propID; ?>]"
                                        value="<? echo htmlspecialcharsbx($propInfo['ID']); ?>"
                                        >
                                    <?
                                    if (isset($arItem['PRODUCT_PROPERTIES'][$propID])) {
                                        unset($arItem['PRODUCT_PROPERTIES'][$propID]);
                                    }
                                }
                            }
                            $emptyProductProperties = empty($arItem['PRODUCT_PROPERTIES']);
                            if (!$emptyProductProperties) {
                                ?>
                                <table>
                                    <?
                                    foreach ($arItem['PRODUCT_PROPERTIES'] as $propID => $propInfo) {
                                        ?>
                                        <tr>
                                            <td><? echo $arItem['PROPERTIES'][$propID]['NAME']; ?></td>
                                            <td>
                                                <?
                                                if (
                                                    'L' == $arItem['PROPERTIES'][$propID]['PROPERTY_TYPE']
                                                    && 'C' == $arItem['PROPERTIES'][$propID]['LIST_TYPE']
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
                        </div></li>

                        <?
                    }

                }

            } ?>
        </ul>
    </div>
    <?
} else {
    ?>
    <br/><br/>
    <p>В этом разделе нет товаров</p>
    <?
}