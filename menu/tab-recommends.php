<?
require_once($_SERVER["DOCUMENT_ROOT"] . SITE_TEMPLATE_PATH . "/utils.php");
$arVariables = getCatalogVariables();
$res = getRecommendsList($arVariables);
$res->NavStart();
global $city, $cities;
$cityCode = $cities[$city]["CODE"];
if ($res->NavRecordCount > 0) {
    ?>
    <div class="swiper-container items small-items">
        <div class="swiper-wrapper">

            <?
            global $cities, $city, $hidden_sections;
            $arPriceTypeList = array();
            $dbPriceType = CCatalogGroup::GetList(array(), array('CAN_BUY' => 'Y'), false, false, array('NAME', 'ID'));
            while ($arPriceType = $dbPriceType->Fetch()) {
                $arPriceTypeList[] = $arPriceType["NAME"];
            }
            $arResultPrices = CIBlockPriceTools::GetCatalogPrices(2, $arPriceTypeList);
            while ($ob = $res->GetNextElement()) {
                $arItem = $ob->GetFields();

                $arOffers = CIBlockPriceTools::GetOffersArray(
                    $arItem['IBLOCK_ID'],
                    $arItem['ID'],
                    array("ID" => "DESC"),
                    array("NAME"),
                    array("CITY"),
                    0,
                    $arResultPrices,
                    true
                );
                $found = false;
                if (!empty($arOffers) && is_array($arOffers) && count($arOffers) > 0) {
                    foreach ($arOffers as $offer) {
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
                        <? if ($arItem['PROPERTY_ARTNUMBER_VALUE']) { ?><span
                            class="num"><?= str_pad($arItem['PROPERTY_ARTNUMBER_VALUE'], 4, '0',
                            STR_PAD_LEFT) ?></span><? } ?>
                        <? $APPLICATION->IncludeComponent("bitrix:catalog.brandblock", "menu", array(
                            "IBLOCK_ID" => $arItem['IBLOCK_ID'],
                            "ELEMENT_ID" => $arItem['ID'],
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
                        if ($arItem["PREVIEW_PICTURE"]) {
                            $arUserPhoto = CFile::GetFileArray($arItem["PREVIEW_PICTURE"]);
                            $preview = CFile::ResizeImageGet($arUserPhoto, array("width" => 227, "height" => 208),
                                BX_RESIZE_IMAGE_EXACT);
                        } else {
                            $preview['src'] = '/bitrix/templates/sushman/img/no-photo.jpg';
                        }
                        $strTitle = htmlspecialcharsEx($arItem["NAME"]);
                        $arItem["PRICE_FORMATTED"] = SaleFormatCurrency($arItem["CATALOG_PRICE_1"],
                            $arItem["CATALOG_CURRENCY_1"]);
                        $arItem["BUY_URL"] = htmlspecialcharsex($APPLICATION->GetCurPageParam("action=BUY&id=" . ($found ? $found["ID"] : $arItem["ID"])),
                            array("action", "buy"));
                        $arItem["ADD_URL"] = htmlspecialcharsex($APPLICATION->GetCurPageParam("action=ADD2BASKET&id=" . ($found ? $found["ID"] : $arItem["ID"])),
                            array("action", "buy"));
                        ?>
                        <a href="<? echo $arItem['DETAIL_PAGE_URL']; ?>"
                           data-popup_href="/menu/detail.php?SECTION_ID=&ELEMENT_ID=<?= $arItem["ID"] ?>"
                           title="<? echo $strTitle; ?>"><span class="pic"><img src="<?= $preview['src'] ?>"/></span>
                            <span class="title valigned"><span><? echo $arItem['NAME']; ?></span></span></a>

                        <? if (!in_array($arItem["SECTION_ID"], $hidden_sections)) { ?>
                            <div class="price-block">
                            <span class="price"><?= str_replace("руб.", "<span class='ico-rub'></span>",
                                    ($found ? $found["PRICES"]["BASE"]["PRINT_DISCOUNT_VALUE"] : $arItem["PRICE_FORMATTED"])) ?></span>
                            </div>
                            <?
                            global $ignored_ids, $wok_section_ids, $pizza_section_ids;
                            if (!in_array($arItem["IBLOCK_SECTION_ID"], $ignored_ids)) { ?>
                                <a onclick="ga('send', 'event', 'Basket <?= strtoupper($cityCode == 'nkz' ? 'nk' : $cityCode) ?>', 'Add product <?= strtoupper($cityCode == 'nkz' ? 'nk' : $cityCode) ?>')" href="<?= $arItem["BUY_URL"] ?>" data-add-url="<?= $arItem["ADD_URL"] ?>"
                                   rel="nofollow"
                                   class="buy">Купить</a>
                            <? } else { ?>
                                <a href="#" data-id="<?= $arItem["ID"] ?>" rel="nofollow"
                                   class="buy btn-addto-constructor <?= (in_array($arItem["IBLOCK_SECTION_ID"],
                                       $wok_section_ids) ? "wok-cons" : "") . (in_array($arItem["IBLOCK_SECTION_ID"],
                                       $pizza_section_ids) ? "pizza-cons" : "") ?>">Добавить</a>
                            <? }
                        } ?>
                    </div>
                </div>

                <?
            }
            ?>
        </div>
    </div>
    <?
}
