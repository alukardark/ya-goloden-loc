<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
} ?>
<?
global $city, $cities;
$cityCode = $cities[$city]["CODE"];
if (!empty($arResult['ITEMS'])): ?>
    <div class="swiper-container items small-items">
        <div class="swiper-wrapper">
            <?php
            global $cities, $city, $hidden_sections;
            global $ignored_ids, $wok_section_ids, $pizza_section_ids;
            ?>

            <? foreach ($arResult['ITEMS'] as $arItem): ?>
                <?php
                $found = false;
                if (count($arItem["OFFERS"]) > 0) {
                    foreach ($arItem["OFFERS"] as $offer) {
                        if ($cities[$city]["CODE"] == $offer["PROPERTIES"]["CITY"]["VALUE"]) {
                            $found = $offer;
                        }
                    }
                    if (!$found) {
                        continue;
                    }
                }
                ?>
                <div class="swiper-slide">
                    <div class="bx_catalog_item_container">
                        <? if ($arItem["SORT"]) { ?><span class="num"><?= str_pad($arItem["SORT"], 4, '0',
                            STR_PAD_LEFT) ?></span><? }//['DISPLAY_PROPERTIES']['ARTNUMBER']["VALUE"]  ?>
                        <? $APPLICATION->IncludeComponent("bitrix:catalog.brandblock", "menu", array(
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
                        if ($arItem["DETAIL_PICTURE"]["ID"]) {
                            $arUserPhoto = CFile::GetFileArray($arItem["DETAIL_PICTURE"]["ID"]);
                            $preview = CFile::ResizeImageGet($arUserPhoto, array("width" => 227, "height" => 208),
                                BX_RESIZE_IMAGE_EXACT);
                        } else {
                            $preview['src'] = '/bitrix/templates/sushman/img/no-photo.png';
                        }
                        $strTitle = htmlspecialcharsEx($arItem["NAME"])

                        ?>
                        <a href="<? echo $arItem['DETAIL_PAGE_URL']; ?>"
                           data-popup_href="/menu/detail.php?ELEMENT_ID=<?= $arItem["ID"] ?>"
                           title="<? echo $strTitle; ?>"><span class="pic"><img src="<?= $preview['src'] ?>"/></span>
                            <span class="title valigned"><span><? echo $arItem['NAME']; ?></span></span></a>

                        <? if (!in_array($arItem["SECTION_ID"], $hidden_sections)) { ?>
                            <div class="price-block">
                            <span class="price"><?= ($arItem["CATALOG_PRICE_1"] > 0 ? str_replace("руб.",
                                    "<span class='ico-rub'></span>",
                                    $arItem["PRICES"]["BASE"]["PRINT_VALUE"]) : "") ?></span>
                            </div>
                            <?
                            if (!in_array($arItem["IBLOCK_SECTION_ID"], $ignored_ids)) { ?>
                                <a onclick="ga('send', 'event', 'Basket <?= strtoupper($cityCode == 'nkz' ? 'nk' : $cityCode) ?>', 'Add product <?= strtoupper($cityCode == 'nkz' ? 'nk' : $cityCode) ?>')" href="<?= $arItem["BUY_URL"] ?>" data-add-url="<?= $arItem["ADD_URL"] ?>"
                                   rel="nofollow"
                                   class="buy">Купить</a>
                            <? } else {
                                ?>
                                <a href="#" data-id="<?= $arItem["ID"] ?>" rel="nofollow"
                                   class="buy btn-addto-constructor <?= (in_array($arItem["IBLOCK_SECTION_ID"],
                                       $wok_section_ids) ? "wok-cons" : "") . (in_array($arItem["IBLOCK_SECTION_ID"],
                                       $pizza_section_ids) ? "pizza-cons" : "") ?>">Добавить</a>
                            <? }
                        } ?>
                    </div>
                </div>
            <? endforeach; ?>
        </div>
    </div>
<? endif; ?>