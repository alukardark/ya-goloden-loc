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

/*
if($arResult['ID'] == 64 and !in_array($_SERVER['REMOTE_ADDR'], array('158.46.22.198', '46.149.226.65', '158.46.18.231', '176.197.241.81'))){
    CHTTP::SetStatus("404 Not Found");
    @define("ERROR_404","Y");
}
*/
$this->setFrameMode(true);
?>
<?
$res = CIBlockSection::GetList(array(), array(
    "IBLOCK_ID" => 2,
    "ID" => $arResult["ID"],
), false, array("ID", "IBLOCK_ID", "UF_SEO_TEXT", "UF_SEO_TEXT_NSK"));
if ($row = $res->GetNext()) {
    $seo_text = html_entity_decode(convert_tpl(trim($row["UF_SEO_TEXT"])));
    $seo_text_nsk = html_entity_decode(convert_tpl(trim($row["UF_SEO_TEXT_NSK"])));
}

//if ($arResult["DETAIL_PICTURE"]) {
//	$pict = CFile::ResizeImageGet($arResult["DETAIL_PICTURE"],array("width" => 731,"height" => 9999) );
//	$img = $pict['src'];
//}

global $city, $cities;
$cityCode = $cities[$city]["CODE"];

if ($arResult["UF_IMAGE_" . mb_strtoupper($cityCode)]) {

    $pict = CFile::ResizeImageGet($arResult["UF_IMAGE_" . mb_strtoupper($cityCode)],
        array("width" => 731, "height" => 9999));
    $img = $pict['src'];
}

$strTitle = (
isset($arResult["IPROPERTY_VALUES"]["SECTION_DETAIL_PICTURE_FILE_TITLE"]) && '' != $arResult["IPROPERTY_VALUES"]["SECTION_DETAIL_PICTURE_FILE_TITLE"]
    ? $arResult["IPROPERTY_VALUES"]["SECTION_DETAIL_PICTURE_FILE_TITLE"]
    : $arResult['NAME']
);
$strAlt = (
isset($arResult["IPROPERTY_VALUES"]["SECTION_DETAIL_PICTURE_FILE_ALT"]) && '' != $arResult["IPROPERTY_VALUES"]["SECTION_DETAIL_PICTURE_FILE_ALT"]
    ? $arResult["IPROPERTY_VALUES"]["SECTION_DETAIL_PICTURE_FILE_ALT"]
    : $arResult['NAME']
);


global $pizza_section_ids;
$hasConstructor = in_array($arResult["ID"], $pizza_section_ids);
if ($hasConstructor) {
    ?>
    <div id="pizza-container-wrapper">
        <div id="pizza-container">
            <div class="pizza-pic"><span class="lp"></span><span class="rp"></span></div>
            <div class="pizza-title">Собери свою пиццу из двух<br/> половинок!</div>
            <ul class="steps">
                <li id="wok-selected-pizza1" class="valigned"><span class="t">Добавьте половинку</span><span
                        class="close"></span></li>
                <li id="wok-selected-pizza2" class="valigned"><span class="t">Добавьте еще половинку</span><span
                        class="close"></span></li>
            </ul>
            <a href="#" class="buy">купить</a>

            <div class="price-block">
                <span class="price">230 <span class="ico-rub"></span></span> /
                <span class="weight">300 г</span>
                <?
                require($_SERVER['DOCUMENT_ROOT'] . '/include/show-discount-prices.php');
                if ($discount['ID']) {
                    echo '<span class="cart-note">* Сумма без учета скидки</span>';
                }
                ?>
            </div>
        </div>
    </div>

    <?
} else {

    $section_title = trim($arResult["IPROPERTY_VALUES"]["SECTION_PAGE_TITLE"]);
    if (!$section_title) {
        $section_title = $arResult["NAME"];
    }

    global $city, $cities;
    $cityCode = $cities[$city]["CODE"];
    //if($city == 2 or $city == 3) $img = false;

    CModule::includeModule('ws.projectsettings');

    if (isset($_GET['cheap_rolls']) and WS_PSettings::getFieldValue("cheap_rolls_image_" . $cityCode)) {
        $img = WS_PSettings::getFieldValue("cheap_rolls_image_" . $cityCode);
    }

    ?>
    <div id="sect-pic" <?= (!$img ? " class='no-image'" : "") ?>>
        <? if ($img) { ?>
            <div class="pic"><img src="<?= $img ?>" alt="<?= $strAlt ?>" title="<?= $strTitle ?>"></div><? } ?>
        <?
        if ($arResult['UF_SHOW_TITLE']) {
            $section_title = isset($_GET['cheap_rolls']) ? 'Недорогие роллы' : $section_title;
            $section_title = isset($_GET['j_menu']) ? 'Всё японское меню' : $section_title;
            ?>
        <? }
        ?>
    </div>
    <h1 class="sect sect-title sect-title-new-design"><?= $section_title; ?></h1>
<? } ?>

<?
if (!empty($arResult['ITEMS'])) {
    if ($arParams["DISPLAY_TOP_PAGER"]) {
        ?><? echo $arResult["NAV_STRING"]; ?><?
    }

    $strElementEdit = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT");
    $strElementDelete = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE");
    $arElementDeleteParams = array("CONFIRM" => GetMessage('CT_BCS_TPL_ELEMENT_DELETE_CONFIRM'));
    ?>

    <div class="filter-block">
        <form action="" method='GET' id='filter_form'>
            <div class="sort-field">

            </div>
            <div class="filter-field">
                <?= (isset($_GET['cheap_rolls']) ? '<input type="hidden" name="cheap_rolls">' : '') ?>
                <select name="my_sort">
                    <?
                    $arTypes = array(
                        "" => "Сортировка",
                        "PRICE_ASC" => "По цене от самого дешевого",
                        "PRICE_DESC" => "По цене от самого дорогого",
                        "NAME_ASC" => "По алфавиту",
                        "NAME_DESC" => "По алфавиту в обратном порядке",
                    );

                    foreach ($arTypes as $key => $value) {
                        $sel = "";
                        if ($_GET[my_sort] == $key || $_COOKIE[my_sort] == $key) {
                            $sel = "selected";
                        }
                        echo "<option {$sel} value='{$key}'>{$value}</option>";
                    }
                    ?>
                </select>
            </div>
            <input type="submit">
        </form>
    </div>


    <ul class="bx_catalog_list_home items"><?

        foreach ($arResult['ITEMS'] as $key => $arItem)
        {


        if (isset($_GET['cheap_rolls'])) {
            if (intval($arItem["CATALOG_PRICE_1"])>=100) {
                continue;
            }
        } elseif($arItem['IBLOCK_SECTION_ID']==19) {
            if (intval($arItem["CATALOG_PRICE_1"])<=100) {
                continue;
            }
        }
            $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], $strElementEdit);
            $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], $strElementDelete, $arElementDeleteParams);
                $orig_id = $arItem['ID'];
                $strMainID = $this->GetEditAreaId($arItem['ID']);

            $arItemIDs = array(
                'ID' => $strMainID,
                'PICT' => $strMainID.'_pict',
                'SECOND_PICT' => $strMainID.'_secondpict',

                'QUANTITY' => $strMainID.'_quantity',
                'QUANTITY_DOWN' => $strMainID.'_quant_down',
                'QUANTITY_UP' => $strMainID.'_quant_up',
                'QUANTITY_MEASURE' => $strMainID.'_quant_measure',
                'BUY_LINK' => $strMainID.'_buy_link',
                'SUBSCRIBE_LINK' => $strMainID.'_subscribe',

                'PRICE' => $strMainID.'_price',
                'DSC_PERC' => $strMainID.'_dsc_perc',
                'SECOND_DSC_PERC' => $strMainID.'_second_dsc_perc',

                'PROP_DIV' => $strMainID.'_sku_tree',
                'PROP' => $strMainID.'_prop_',
                'DISPLAY_PROP_DIV' => $strMainID.'_sku_prop',
                'BASKET_PROP_DIV' => $strMainID.'_basket_prop',
            );

            $strObName = 'ob'.preg_replace("/[^a-zA-Z0-9_]/", "x", $strMainID);

            $strTitle = (
                isset($arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"]) && '' != isset($arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"])
                ? $arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"]
                : $arItem['NAME']
            );
            $strAlt = (
                isset($arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_ALT"]) && '' != isset($arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_ALT"])
                ? $arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_ALT"]
                : $arItem['NAME']
            );
            ?><li class="bx_catalog_item_container" id="<? echo $strMainID; ?>">

        <?
            if (!isset($arItem['OFFERS']) || empty($arItem['OFFERS']))
            {

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
                ?><script type="text/javascript">
                var <? echo $strObName; ?> = new JCCatalogSection(<? echo CUtil::PhpToJSObject($arJSParams, false, true); ?>);
                </script>

        <? } else { ?>
                <?

                global $city, $cities;
                $cityCode = $cities[$city]["CODE"];
                $arProp = $arItem['SKU_PROPS']['CITY'];
                $arItem['OFFERS_SELECTED'] = -1;
                foreach ($arItem['OFFERS'] as $k => $v)
                {
                   if ($cityCode==$v["PROPERTIES"]["CITY"]["VALUE"])
                    {$arItem['OFFERS_SELECTED'] = $k;}
                }

            $arSkuTemplate = array();
            if (!empty($arResult['SKU_PROPS']))
            {
                foreach ($arResult['SKU_PROPS'] as &$arProp)
                {
                    ob_start();
                    if ('TEXT' == $arProp['SHOW_MODE'])
                    {
                        if (5 < $arProp['VALUES_COUNT'])
                        {
                            $strClass = 'bx_item_detail_size full';
                            $strWidth = ($arProp['VALUES_COUNT']*20).'%';
                            $strOneWidth = (100/$arProp['VALUES_COUNT']).'%';
                            $strSlideStyle = '';
                        }
                        else
                        {
                            $strClass = 'bx_item_detail_size';
                            $strWidth = '100%';
                            $strOneWidth = '20%';
                            $strSlideStyle = 'display: none;';
                        }
                        ?><div class="<? echo $strClass; ?>" id="#ITEM#_prop_<? echo $arProp['ID']; ?>_cont" style="display:none;">
        <ul id="#ITEM#_prop_<? echo $arProp['ID']; ?>_list"><?
                        foreach ($arProp['VALUES'] as $arOneValue)
                        {
                            ?><li
                                data-treevalue="<? echo $arProp['ID'].'_'.$arOneValue['ID']; ?>"
                                data-onevalue="<? echo $arOneValue['ID']; ?>"
                            ></li><?
                        }
        ?></ul>
                <div class="bx_slide_left" id="#ITEM#_prop_<? echo $arProp['ID']; ?>_left" data-treevalue="<? echo $arProp['ID']; ?>" style="<? echo $strSlideStyle; ?>"></div>
                <div class="bx_slide_right" id="#ITEM#_prop_<? echo $arProp['ID']; ?>_right" data-treevalue="<? echo $arProp['ID']; ?>" style="<? echo $strSlideStyle; ?>"></div>
        </div><?
                    }
                    elseif ('PICT' == $arProp['SHOW_MODE'])
                    {
                        if (5 < $arProp['VALUES_COUNT'])
                        {
                            $strClass = 'bx_item_detail_scu full';
                            $strWidth = ($arProp['VALUES_COUNT']*20).'%';
                            $strOneWidth = (100/$arProp['VALUES_COUNT']).'%';
                            $strSlideStyle = '';
                        }
                        else
                        {
                            $strClass = 'bx_item_detail_scu';
                            $strWidth = '100%';
                            $strOneWidth = '20%';
                            $strSlideStyle = 'display: none;';
                        }
                        ?><div class="<? echo $strClass; ?>" id="#ITEM#_prop_<? echo $arProp['ID']; ?>_cont" style="display:none;">
        <span class="bx_item_section_name_gray"><? echo htmlspecialcharsex($arProp['NAME']); ?></span>
        <div class="bx_scu_scroller_container"><div class="bx_scu"><ul id="#ITEM#_prop_<? echo $arProp['ID']; ?>_list" style="width: <? echo $strWidth; ?>;"><?
                        foreach ($arProp['VALUES'] as $arOneValue)
                        {
                            ?><li
                                data-treevalue="<? echo $arProp['ID'].'_'.$arOneValue['ID'] ?>"
                                data-onevalue="<? echo $arOneValue['ID']; ?>"
                                style="width: <? echo $strOneWidth; ?>; padding-top: <? echo $strOneWidth; ?>;"
                                ><i title="<? echo htmlspecialcharsbx($arOneValue['NAME']); ?>"></i>
                                <span class="cnt"><span class="cnt_item"
                                style="background-image:url('<? echo $arOneValue['PICT']['SRC']; ?>');"
                                title="<? echo htmlspecialcharsbx($arOneValue['NAME']); ?>"
                            ></span></span></li><?
                        }
        ?></ul></div>
                <div class="bx_slide_left" id="#ITEM#_prop_<? echo $arProp['ID']; ?>_left" data-treevalue="<? echo $arProp['ID']; ?>" style="<? echo $strSlideStyle; ?>"></div>
                <div class="bx_slide_right" id="#ITEM#_prop_<? echo $arProp['ID']; ?>_right" data-treevalue="<? echo $arProp['ID']; ?>" style="<? echo $strSlideStyle; ?>"></div>
            </div>
        </div><?
                    }
                    $arSkuTemplate[$arProp['CODE']] = ob_get_contents();
                    ob_end_clean();
                }
                unset($arProp);
            }


                $boolShowOfferProps = ('Y' == $arParams['PRODUCT_DISPLAY_MODE'] && $arItem['OFFERS_PROPS_DISPLAY']);
                $boolShowProductProps = (isset($arItem['DISPLAY_PROPERTIES']) && !empty($arItem['DISPLAY_PROPERTIES']));
                if ('Y' == $arParams['PRODUCT_DISPLAY_MODE'])
                {
                    if (!empty($arItem['OFFERS_PROP']))
                    {
                        $arSkuProps = array();
                        ?><div class="bx_catalog_item_scu" id="<? echo $arItemIDs['PROP_DIV']; ?>"><?
                        foreach ($arSkuTemplate as $code => $strTemplate)
                        {
                            if (!isset($arItem['OFFERS_PROP'][$code]))
                                {continue;}
                            echo '<div>', str_replace('#ITEM#_prop_', $arItemIDs['PROP'], $strTemplate), '</div>';
                        }
                        foreach ($arResult['SKU_PROPS'] as $arOneProp)
                        {
                            if (!isset($arItem['OFFERS_PROP'][$arOneProp['CODE']]))
                                {continue;}
                            $arSkuProps[] = array(
                                'ID' => $arOneProp['ID'],
                                'SHOW_MODE' => $arOneProp['SHOW_MODE'],
                                'VALUES_COUNT' => $arOneProp['VALUES_COUNT']
                            );
                        }
                        foreach ($arItem['JS_OFFERS'] as &$arOneJs)
                        {
                            if (0 < $arOneJs['PRICE']['DISCOUNT_DIFF_PERCENT'])
                                {$arOneJs['PRICE']['DISCOUNT_DIFF_PERCENT'] = '-'.$arOneJs['PRICE']['DISCOUNT_DIFF_PERCENT'].'%';}
                        }
                        unset($arOneJs);
                        ?></div><?
                        if ($arItem['OFFERS_PROPS_DISPLAY'])
                        {
                            foreach ($arItem['JS_OFFERS'] as $keyOffer => $arJSOffer)
                            {
                                $strProps = '';
                                if (!empty($arJSOffer['DISPLAY_PROPERTIES']))
                                {
                                    foreach ($arJSOffer['DISPLAY_PROPERTIES'] as $arOneProp)
                                    {
                                        $strProps .= '<br>'.$arOneProp['NAME'].' <strong>'.(
                                            is_array($arOneProp['VALUE'])
                                            ? implode(' / ', $arOneProp['VALUE'])
                                            : $arOneProp['VALUE']
                                        ).'</strong>';
                                    }
                                }
                                $arItem['JS_OFFERS'][$keyOffer]['DISPLAY_PROPERTIES'] = $strProps;
                            }
                        }
                        $arJSParams = array(
                            'PRODUCT_TYPE' => $arItem['CATALOG_TYPE'],
                            'SHOW_QUANTITY' => $arParams['USE_PRODUCT_QUANTITY'],
                            'SHOW_ADD_BASKET_BTN' => false,
                            'SHOW_BUY_BTN' => true,
                            'SHOW_ABSENT' => true,
                            'SHOW_SKU_PROPS' => $arItem['OFFERS_PROPS_DISPLAY'],
                            'SECOND_PICT' => $arItem['SECOND_PICT'],
                            'SHOW_OLD_PRICE' => ('Y' == $arParams['SHOW_OLD_PRICE']),
                            'SHOW_DISCOUNT_PERCENT' => ('Y' == $arParams['SHOW_DISCOUNT_PERCENT']),
                            'DEFAULT_PICTURE' => array(
                                'PICTURE' => $arItem['PRODUCT_PREVIEW'],
                                'PICTURE_SECOND' => $arItem['PRODUCT_PREVIEW_SECOND']
                            ),
                            'VISUAL' => array(
                                'ID' => $arItemIDs['ID'],
                                'PICT_ID' => $arItemIDs['PICT'],
                                'SECOND_PICT_ID' => $arItemIDs['SECOND_PICT'],
                                'QUANTITY_ID' => $arItemIDs['QUANTITY'],
                                'QUANTITY_UP_ID' => $arItemIDs['QUANTITY_UP'],
                                'QUANTITY_DOWN_ID' => $arItemIDs['QUANTITY_DOWN'],
                                'QUANTITY_MEASURE' => $arItemIDs['QUANTITY_MEASURE'],
                                'PRICE_ID' => $arItemIDs['PRICE'],
                                'TREE_ID' => $arItemIDs['PROP_DIV'],
                                'TREE_ITEM_ID' => $arItemIDs['PROP'],
                                'BUY_ID' => $arItemIDs['BUY_LINK'],
                                'ADD_BASKET_ID' => $arItemIDs['ADD_BASKET_ID'],
                                'DSC_PERC' => $arItemIDs['DSC_PERC'],
                                'SECOND_DSC_PERC' => $arItemIDs['SECOND_DSC_PERC'],
                                'DISPLAY_PROP_DIV' => $arItemIDs['DISPLAY_PROP_DIV'],
                            ),
                            'BASKET' => array(
                                'QUANTITY' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
                                'PROPS' => $arParams['PRODUCT_PROPS_VARIABLE'],
                                'SKU_PROPS' => $arItem['OFFERS_PROP_CODES']
                            ),
                            'PRODUCT' => array(
                                'ID' => $arItem['ID'],
                                'NAME' => $arItem['~NAME']
                            ),
                            'OFFERS' => $arItem['JS_OFFERS'],
                            'OFFER_SELECTED' => $arItem['OFFERS_SELECTED'],
                            'TREE_PROPS' => $arSkuProps,
                            'LAST_ELEMENT' => $arItem['LAST_ELEMENT']
                        );
                        ?>
        <script type="text/javascript">
        var <? echo $strObName; ?> = new JCCatalogSection(<? echo CUtil::PhpToJSObject($arJSParams, false, true); ?>);
        </script>
                        <?
                    }
                }
        ?>
        <? } ?>
        <?php
            $type_block_id = $arItem['IBLOCK_ID'];
            $type_element_id = $arItem['ID'];
        if (isset($arItem['OFFERS']) && !empty($arItem['OFFERS']))
        {
                global $city, $cities;
                $cityCode = $cities[$city]["CODE"];
                $arProp = $arItem['SKU_PROPS']['CITY'];
                $curOffer = null;
                foreach ($arItem['OFFERS'] as $k => $v)
                {
                   if ($cityCode==$v["PROPERTIES"]["CITY"]["VALUE"]) {
                    $curOffer = $v;
                    if (is_array($curOffer["PROPERTIES"]["TYPE_REF"]["VALUE"]) && count($curOffer["PROPERTIES"]["TYPE_REF"]["VALUE"])>0) {
                        $type_block_id = $curOffer["IBLOCK_ID"];
                        $type_element_id = $curOffer["ID"];
                      }
                   }


                }
        }
        ?>

                                            <? if ($arItem["SORT"]) { ?><span class="num"><?=str_pad($arItem["SORT"],4,'0',STR_PAD_LEFT)?></span><? } //['DISPLAY_PROPERTIES']['ARTNUMBER']["VALUE"] ?>
                                            <?$APPLICATION->IncludeComponent("bitrix:catalog.brandblock", "menu", array(
                                                            //"IBLOCK_TYPE" => $arItem['IBLOCK_TYPE'],
                                                            "IBLOCK_ID" => $type_block_id,
                                                            "ELEMENT_ID" => $type_element_id,
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
                                                    );?>
                                            <?
                                            if ($arItem["PREVIEW_PICTURE"]["ID"]) {
                                                $arUserPhoto = CFile::GetFileArray($arItem["PREVIEW_PICTURE"]["ID"]);
                                                $preview = CFile::ResizeImageGet($arUserPhoto,array("width" => 227,"height" => 208),BX_RESIZE_IMAGE_EXACT );
                                            } else {
                                                $preview['src'] = $arItem["PREVIEW_PICTURE"]["SRC"];
                                            }
                                            $constPic["src"] = SUSHMAN_MAIN_PATH."/img/pizza-default.png";
                                            if ($arItem["PROPERTIES"]["CONST_PIC"]["VALUE"]) {
                                                $arConstPic = CFile::GetFileArray($arItem["PROPERTIES"]["CONST_PIC"]["VALUE"]);
                                                $constPic = CFile::ResizeImageGet($arConstPic,array("width" => 310,"height" => 310),BX_RESIZE_IMAGE_EXACT );
                                            }
                                            ?>
                                            <a class="overlay" <?=($hasConstructor ? " target='_blank'" : "")?> href="<? echo $arItem['DETAIL_PAGE_URL']; ?>" data-popup_href="/menu/detail.php?SECTION_ID=<?=$arItem["SECTION_ID"]?>&ELEMENT_ID=<?=$arItem["ID"]?>" id="<? echo $arItemIDs['PICT']; ?>" title="<? echo $strTitle; ?>"></a>
                                            <a<?=($hasConstructor ? " target='_blank'" : "")?> href="<? echo $arItem['DETAIL_PAGE_URL']; ?>" data-popup_href="/menu/detail.php?SECTION_ID=<?=$arItem["SECTION_ID"]?>&ELEMENT_ID=<?=$arItem["ID"]?>" id="<? echo $arItemIDs['PICT']; ?>" title="<? echo $strTitle; ?>"><span class="pic"<?=($constPic["src"] ? " const_pic='{$constPic["src"]}'" : "")?>><img src="<?=$preview['src']?>" alt="<?=$strAlt?>"/></span>
                                                <span class="title valigned f" href="<? echo $arItem['DETAIL_PAGE_URL']; ?>" title="<? echo $strTitle; ?>"><span><? echo $arItem['NAME']; ?></span></span></a>

        <?
            if (false && 'Y' == $arParams['SHOW_DISCOUNT_PERCENT'])
            {
            ?>
                    <div
                        id="<? echo $arItemIDs['DSC_PERC']; ?>"
                        class="bx_stick_disc right bottom"
                        style="display:<? echo (0 < $arItem['MIN_PRICE']['DISCOUNT_DIFF_PERCENT'] ? '' : 'none'); ?>;">-<? echo $arItem['MIN_PRICE']['DISCOUNT_DIFF_PERCENT']; ?>%</div>
            <?
            }
            if (false && $arItem['LABEL'])
            {
            ?>
                    <div class="bx_stick average left top" title="<? echo $arItem['LABEL_VALUE']; ?>"><? echo $arItem['LABEL_VALUE']; ?></div>
            <?
            }
            ?>

            <?
            global $hidden_sections;
            $hid = '';
            if (in_array($arResult["ID"], $hidden_sections)){
                $hid = 'display:none;';
            }
            ?>
                <div class="price-block" style="<? echo $hid; ?>">

					<span class="price bx_price" style="display:none;" id="<? echo $arItemIDs['PRICE']; ?>"><?

			global $city, $cities;
			$t = getdate();
			$set_discount_time = false;
			if($t['wday'] >= 1 && $t['wday'] <= 5) {
				if($t['hours'] >= 17) {
					$set_discount_time = true;
				}
			} else {
				if($t['hours'] >= 14) {
					$set_discount_time = true;
				}
			}
			$set_discount_time = false;

            if (!empty($arItem['MIN_PRICE']))
            {
                if ('N' == $arParams['PRODUCT_DISPLAY_MODE'] && isset($arItem['OFFERS']) && !empty($arItem['OFFERS']))
                {
					echo $priceShowed = str_replace("руб.","<span class='ico-rub'></span>",GetMessage(
                        'CT_BCS_TPL_MESS_PRICE_SIMPLE_MODE',
                        array(
                            '#PRICE#' => $arItem['MIN_PRICE']['PRINT_DISCOUNT_VALUE'],
                            '#MEASURE#' => GetMessage(
                                'CT_BCS_TPL_MESS_MEASURE_SIMPLE_MODE',
                                array(
                                    '#VALUE#' => $arItem['MIN_PRICE']['CATALOG_MEASURE_RATIO'],
                                    '#UNIT#' => $arItem['MIN_PRICE']['CATALOG_MEASURE_NAME']
                                )
                            )
                        )
                    ));
                }
                else
                {
					$showPrice = $arItem['MIN_PRICE']['PRINT_DISCOUNT_VALUE'];
					if($set_discount_time and in_array($arResult["ID"], array(17)) and $cities[$city]["CODE"] != 'nsk') {
						$showPrice = round((int)str_replace(' ', '', $showPrice) / 2);
					}
					echo $priceShowed = str_replace(" руб.","", $showPrice . "<span class='ico-rub'></span>");
                }
                if ('Y' == $arParams['SHOW_OLD_PRICE'] && $arItem['MIN_PRICE']['DISCOUNT_VALUE'] < $arItem['MIN_PRICE']['VALUE'])
                {
                    ?> <span><? echo str_replace("руб.","<span class='ico-rub'></span>",$arItem['MIN_PRICE']['PRINT_VALUE']); ?></span><?
                }
            }
            ?></span>
				<span class="price bx_price">
					<? echo $priceShowed; ?>
				</span>
            <?
            CModule::includeModule('ws.projectsettings');

            //if(WS_PSettings::getFieldValue("show_purchasing_price") and $arItem['OFFERS'][$city]['CATALOG_PURCHASING_PRICE']){

			if($set_discount_time and in_array($arResult["ID"], array(17)) and $cities[$city]["CODE"] != 'nsk') {
				echo '<span class="preview_item_purchase_price">'.str_replace(' руб.','', $arItem['MIN_PRICE']['PRINT_DISCOUNT_VALUE']).'<span class="ico-rub"></span></span>';
			} else {
				if(WS_PSettings::getFieldValue("show_purchasing_price") and intval($curOffer['CATALOG_PURCHASING_PRICE'])){
	
					echo '<span class="preview_item_purchase_price">'.str_replace('.00','<span class="ico-rub"></span>',$curOffer['CATALOG_PURCHASING_PRICE']).'</span>';
				}
			}
            $SECTION_ID = $arItem['IBLOCK_SECTION_ID'];
			//require($_SERVER['DOCUMENT_ROOT'].'/include/show-discount-prices.php');
			//echo $discountHtml;
            ?>

        <?
        $item_weight = $arItem["CATALOG_WEIGHT"];
        if ($item_weight) { ?>
            <span class="weight"><?=$item_weight?>&nbsp;г</span>
        <?}?>
        <div class="clear"></div>
                    <? if (!$hasConstructor) { ?>
                <div class="q-block">
                    <?
                    if (true || !isset($arItem['OFFERS']) || empty($arItem['OFFERS']))
                    {
                if ($arItem['CAN_BUY'])
                {
                    if ('Y' == $arParams['USE_PRODUCT_QUANTITY'])
                    {
                    ?>
                                <span id="<? echo $arItemIDs['QUANTITY_DOWN']; ?>" class="arrow arrow-left"></span><span id="<? echo $arItemIDs['QUANTITY_UP']; ?>" class="arrow arrow-right"></span>
                    <input type="text" class="bx_col_input" id="<? echo $arItemIDs['QUANTITY']; ?>" name="<? echo $arParams["PRODUCT_QUANTITY_VARIABLE"]; ?>" value="<? echo $arItem['CATALOG_MEASURE_RATIO']; ?>">
                    <?
                    }
                    ?>
                    <?
                }
                else
                {
                    ?><span class="bx_notavailable"><?
                    echo ('' != $arParams['MESS_NOT_AVAILABLE'] ? $arParams['MESS_NOT_AVAILABLE'] : GetMessage('CT_BCS_TPL_MESS_PRODUCT_NOT_AVAILABLE'));
                    ?></span><?
                }
                    }
                ?>
                </div>
                    <? } ?>

                </div><?
            if (true || !isset($arItem['OFFERS']) || empty($arItem['OFFERS']))
            {
                if ($arItem['CAN_BUY'] and !in_array($arResult["ID"], $hidden_sections))
                {
                    ?>

                                <?if ($hasConstructor) { ?>
                            <a id="<? echo $arItemIDs['BUY_LINK']; ?>_addto" class="addto bx_bt_button bx_medium" href="javascript:void(0)" rel="nofollow" data-product-id="<?=$arItem["ID"]?>" data-id="<?=$orig_id?>">
                                <? } else { ?>
                            <a onclick="ga('send', 'event', 'Basket <?= strtoupper($cityCode == 'nkz' ? 'nk' : $cityCode) ?>', 'Add product <?= strtoupper($cityCode == 'nkz' ? 'nk' : $cityCode) ?>')" id="<? echo $arItemIDs['BUY_LINK']; ?>" class="buy bx_bt_button bx_medium" href="javascript:void(0)" rel="nofollow">
                                <? } ?>
                                <?
                    echo ('' != $arParams['MESS_BTN_BUY'] ? $arParams['MESS_BTN_BUY'] : GetMessage('CT_BCS_TPL_MESS_BTN_BUY'));
                    ?></a>
                    <?
                }
                else
                {
                    if ('Y' == $arParams['PRODUCT_SUBSCRIPTION'] && 'Y' == $arItem['CATALOG_SUBSCRIPTION'])
                    {
                    ?>
                        <a
                            id="<? echo $arItemIDs['SUBSCRIBE_LINK']; ?>"
                            class="buy bx_bt_button_type_2 bx_medium"
                            href="javascript:void(0)"><?
                            echo ('' != $arParams['MESS_BTN_SUBSCRIBE'] ? $arParams['MESS_BTN_SUBSCRIBE'] : GetMessage('CT_BCS_TPL_MESS_BTN_SUBSCRIBE'));
                            ?>
                        </a>
                                <?
                    }
                }
                if (isset($arItem['DISPLAY_PROPERTIES']) && !empty($arItem['DISPLAY_PROPERTIES']))
                { /*
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
                if ('Y' == $arParams['ADD_PROPERTIES_TO_BASKET'] && !$emptyProductProperties)
                {
        ?>
                <div id="<? echo $arItemIDs['BASKET_PROP_DIV']; ?>" style="display: none;">
        <?
                    if (!empty($arItem['PRODUCT_PROPERTIES_FILL']))
                    {
                        foreach ($arItem['PRODUCT_PROPERTIES_FILL'] as $propID => $propInfo)
                        {
        ?>
                            <input
                                type="hidden"
                                name="<? echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<? echo $propID; ?>]"
                                value="<? echo htmlspecialcharsbx($propInfo['ID']); ?>"
                                >
        <?
                            if (isset($arItem['PRODUCT_PROPERTIES'][$propID]))
                                {unset($arItem['PRODUCT_PROPERTIES'][$propID]);}
                        }
                    }
                    $emptyProductProperties = empty($arItem['PRODUCT_PROPERTIES']);
                    if (!$emptyProductProperties)
                    {
        ?>
                        <table>
        <?
                            foreach ($arItem['PRODUCT_PROPERTIES'] as $propID => $propInfo)
                            {
        ?>
                                <tr><td><? echo $arItem['PROPERTIES'][$propID]['NAME']; ?></td>
                                    <td>
        <?
                                        if(
                                            'L' == $arItem['PROPERTIES'][$propID]['PROPERTY_TYPE']
                                            && 'C' == $arItem['PROPERTIES'][$propID]['LIST_TYPE']
                                        )
                                        {
                                            foreach($propInfo['VALUES'] as $valueID => $value)
                                            {
                                                ?><label><input
                                                type="radio"
                                                name="<? echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<? echo $propID; ?>]"
                                                value="<? echo $valueID; ?>"
                                                <? echo ($valueID == $propInfo['SELECTED'] ? '"checked"' : ''); ?>
                                                ><? echo $value; ?></label><br><?
                                            }
                                        }
                                        else
                                        {
                                            ?><select name="<? echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<? echo $propID; ?>]"><?
                                            foreach($propInfo['VALUES'] as $valueID => $value)
                                            {
                                                ?><option
                                                value="<? echo $valueID; ?>"
                                                <? echo ($valueID == $propInfo['SELECTED'] ? '"selected"' : ''); ?>
                                                ><? echo $value; ?></option><?
                                            }
                                            ?></select><?
                                        }
        ?>
                                    </td></tr>
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
    <div class="clear"></div>
    <script type="text/javascript">
        BX.message({
            MESS_BTN_BUY: '<? echo ('' != $arParams['MESS_BTN_BUY'] ? CUtil::JSEscape($arParams['MESS_BTN_BUY']) : GetMessageJS('CT_BCS_TPL_MESS_BTN_BUY')); ?>',
            MESS_BTN_ADD_TO_BASKET: '<? echo ('' != $arParams['MESS_BTN_ADD_TO_BASKET'] ? CUtil::JSEscape($arParams['MESS_BTN_ADD_TO_BASKET']) : GetMessageJS('CT_BCS_TPL_MESS_BTN_ADD_TO_BASKET')); ?>',
            MESS_NOT_AVAILABLE: '<? echo ('' != $arParams['MESS_NOT_AVAILABLE'] ? CUtil::JSEscape($arParams['MESS_NOT_AVAILABLE']) : GetMessageJS('CT_BCS_TPL_MESS_PRODUCT_NOT_AVAILABLE')); ?>',
            BTN_MESSAGE_BASKET_REDIRECT: '<? echo GetMessageJS('CT_BCS_CATALOG_BTN_MESSAGE_BASKET_REDIRECT'); ?>',
            BASKET_URL: '<? echo $arParams["BASKET_URL"]; ?>',
            ADD_TO_BASKET_OK: '<? echo GetMessageJS('ADD_TO_BASKET_OK'); ?>',
            TITLE_ERROR: '<? echo GetMessageJS('CT_BCS_CATALOG_TITLE_ERROR') ?>',
            TITLE_BASKET_PROPS: '<? echo GetMessageJS('CT_BCS_CATALOG_TITLE_BASKET_PROPS') ?>',
            TITLE_SUCCESSFUL: '<? echo GetMessageJS('ADD_TO_BASKET_OK'); ?>',
            BASKET_UNKNOWN_ERROR: '<? echo GetMessageJS('CT_BCS_CATALOG_BASKET_UNKNOWN_ERROR') ?>',
            BTN_MESSAGE_SEND_PROPS: '<? echo GetMessageJS('CT_BCS_CATALOG_BTN_MESSAGE_SEND_PROPS'); ?>',
            BTN_MESSAGE_CLOSE: '<? echo GetMessageJS('CT_BCS_CATALOG_BTN_MESSAGE_CLOSE') ?>'
        });
    </script>
    <?
    if ($seo_text || $seo_text_nsk) {
        echo "<div class='additional-text'>" . ($cityCode == 'nsk' ? $seo_text_nsk : $seo_text) . "</div>";
    }
} else {
    ?>
    <br/><br/>
    <p>В этом разделе нет товаров</p>
    <?
}
