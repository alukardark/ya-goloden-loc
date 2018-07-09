<?php
global $is_catalog_page;
$is_catalog_page = true;
$body_class = "inner nobg";
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetPageProperty("title", "Меню SUSHMAN &amp; PIZZMAN в #CITY2#.");
$APPLICATION->SetPageProperty("keywords", "меню");
$APPLICATION->SetPageProperty("description", "Меню SUSHMAN &amp; PIZZMAN в #CITY2#.");
$APPLICATION->SetTitle("Меню");
$filterView = "";
global $arrFilter, $city, $cities;
$arrFilter = array(
//        array( 
//        "LOGIC" => "OR",
    'ID' => CIBlockElement::SubQuery('PROPERTY_CML2_LINK', array(
        'IBLOCK_ID' => 3,
        'PROPERTY_CITY' => $cities[$city]["CODE"],
        'ACTIVE' => 'Y'
    )),
    /*            '!ID' => CIBlockElement::SubQuery('PROPERTY_CML2_LINK', array(
                   'IBLOCK_ID' => 3
                ))
            )*/
);

//$price_code = $cities[$city]["PRICE"];

require_once($_SERVER["DOCUMENT_ROOT"] . SITE_TEMPLATE_PATH . "/utils.php");
$arVariables = getCatalogVariables();
if (!$arVariables["ELEMENT_CODE"]) {
    ?>

    <div class="clear"></div>
    <div class="left-col">
        <div class="left-menu">
            <div class="title">
                Меню
                <a class='div-link' href='/menu/' title='Меню'></a>
            </div>
            <? $APPLICATION->IncludeComponent("bitrix:menu", "inner_multilevel", Array(
                "ROOT_MENU_TYPE" => "left",
                "MENU_CACHE_TYPE" => "N",
                "MENU_CACHE_TIME" => "3600",
                "MENU_DD" => true,
                "MENU_CACHE_USE_GROUPS" => "Y",
                "MAX_LEVEL" => "1",
                "DELAY" => "N",
                "USE_EXT" => "Y"
            ),
                false
            ); ?>
        </div>

        <? $APPLICATION->IncludeComponent("bitrix:main.include", "",
            array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR . "include/search-ingr.php"), false); ?>
        <?
        $APPLICATION->IncludeComponent("bitrix:main.include", "",
            array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR . "include/search-country.php"), false);
        ?>

        <? $APPLICATION->IncludeComponent("bitrix:main.include", "",
            array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR . "include/promo.php"), false); ?>
    </div>
    <div class="main-col">
<? } ?>

<?
if ($arVariables["SECTION_CODE"] == "wok" || $arVariables["SECTION_CODE"] == "wok-new") {

    $arFilter = Array('IBLOCK_ID' => 2, 'ACTIVE' => 'Y', 'CODE' => $arVariables["SECTION_CODE"]);
    $r = CIBlockSection::GetList(Array(), $arFilter, true);
    if ($rr = $r->GetNext()) {
        $ipropValues = new \Bitrix\Iblock\InheritedProperty\SectionValues(2, $rr['ID']);
        $iprops = $ipropValues->getValues();
        if ($iprops["SECTION_META_TITLE"]) {
            $APPLICATION->SetPageProperty("title", $iprops["SECTION_META_TITLE"]);
        }
        if ($iprops["SECTION_META_KEYWORDS"]) {
            $APPLICATION->SetPageProperty("keywords", $iprops["SECTION_META_KEYWORDS"]);
        }
        if ($iprops["SECTION_META_DESCRIPTION"]) {
            $APPLICATION->SetPageProperty("description", $iprops["SECTION_META_DESCRIPTION"]);
        }

    }

    ?>
    <? $APPLICATION->IncludeComponent(
        "bitrix:catalog.section",
        "wok_garnish",
        Array(
            "TEMPLATE_THEME" => "",
            "PRODUCT_DISPLAY_MODE" => "N",
            //"ADD_PICT_PROP" => "MORE_PHOTO",
            //"LABEL_PROP" => "NEW_BOOK",
            //"OFFER_ADD_PICT_PROP" => "FILE",
            //"OFFER_TREE_PROPS" => array("-"),
            "PRODUCT_SUBSCRIPTION" => "N",
            "SHOW_DISCOUNT_PERCENT" => "N",
            "SHOW_OLD_PRICE" => "N",
            "SHOW_CLOSE_POPUP" => "N",
            "MESS_BTN_BUY" => "Купить",
            "MESS_BTN_ADD_TO_BASKET" => "В корзину",
            "MESS_BTN_SUBSCRIBE" => "Подписаться",
            "MESS_BTN_DETAIL" => "Подробнее",
            "MESS_NOT_AVAILABLE" => "Недоступно",
            "AJAX_MODE" => "Y",
            "IBLOCK_TYPE" => "menu",
            "IBLOCK_ID" => "2",
            "SECTION_ID" => $arVariables["SECTION_CODE"] == "wok-new" ? 47 : 36,
            "SECTION_CODE" => "",
            "SECTION_USER_FIELDS" => array(),
            "ELEMENT_SORT_FIELD" => "sort",
            "ELEMENT_SORT_ORDER" => "asc",
            "ELEMENT_SORT_FIELD2" => "name",
            "ELEMENT_SORT_ORDER2" => "asc",
            "FILTER_NAME" => "arrFilter",
            "INCLUDE_SUBSECTIONS" => "N",
            "SHOW_ALL_WO_SECTION" => "Y",
            "SECTION_URL" => "",
            "DETAIL_URL" => "",
            "BASKET_URL" => "/cart/",
            "ACTION_VARIABLE" => "action",
            "PRODUCT_ID_VARIABLE" => "id",
            "PRODUCT_QUANTITY_VARIABLE" => "quantity",
            "ADD_PROPERTIES_TO_BASKET" => "N",
            "PRODUCT_PROPS_VARIABLE" => "prop",
            "PARTIAL_PRODUCT_PROPERTIES" => "N",
            "SECTION_ID_VARIABLE" => "SECTION_ID",
            "ADD_SECTIONS_CHAIN" => "N",
            "DISPLAY_COMPARE" => "N",
            "SET_TITLE" => "N",
            "SET_BROWSER_TITLE" => "",
            "BROWSER_TITLE" => "",
            "SET_META_KEYWORDS" => "N",
            "META_KEYWORDS" => "",
            "SET_META_DESCRIPTION" => "N",
            "META_DESCRIPTION" => "",
            "SET_STATUS_404" => "N",
            "PAGE_ELEMENT_COUNT" => "0",
            "LINE_ELEMENT_COUNT" => "3",
            "PROPERTY_CODE" => array(
                0 => "NEWPRODUCT",
                1 => "SALELEADER",
                2 => "SPECIALOFFER",
                3 => "TYPE_REF",
                4 => "ARTNUMBER",
                5 => "PREVIEW_PICTURE"

            ),
            "OFFERS_SORT_FIELD" => "sort",
            "OFFERS_SORT_ORDER" => "asc",
            "OFFERS_SORT_FIELD2" => "active_from",
            "OFFERS_SORT_ORDER2" => "desc",
            "OFFERS_LIMIT" => "0",
            "OFFERS_FIELD_CODE" => array(
                0 => "NAME",
                1 => "PREVIEW_PICTURE",
                2 => "DETAIL_PICTURE",
                3 => "",
            ),
            "OFFERS_PROPERTY_CODE" => array(
                0 => "SIZES_SHOES",
                1 => "SIZES_CLOTHES",
                2 => "COLOR_REF",
                3 => "MORE_PHOTO",
                4 => "ARTNUMBER",
                5 => "CITY",
            ),
            "PRICE_CODE" => array(
                0 => "BASE",
            ),
            "USE_PRICE_COUNT" => "N",
            "SHOW_PRICE_COUNT" => "1",
            "PRICE_VAT_INCLUDE" => "Y",
            "PRICE_VAT_SHOW_VALUE" => "N",
            "PRODUCT_PROPERTIES" => array(),
            "USE_PRODUCT_QUANTITY" => "Y",
            "CONVERT_CURRENCY" => "Y",
            "CURRENCY_ID" => "RUB",
            "QUANTITY_FLOAT" => "N",
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => "36000000",
            "CACHE_FILTER" => "Y",
            "CACHE_GROUPS" => "Y",
            "DISPLAY_TOP_PAGER" => "N",
            "DISPLAY_BOTTOM_PAGER" => "N",
            "PAGER_TITLE" => "Товары",
            "PAGER_SHOW_ALWAYS" => "Y",
            "PAGER_TEMPLATE" => "",
            "PAGER_DESC_NUMBERING" => "Y",
            "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
            "PAGER_SHOW_ALL" => "Y",
            "HIDE_NOT_AVAILABLE" => "Y",
            //"OFFERS_CART_PROPERTIES" => array(),
            "AJAX_OPTION_JUMP" => "N",
            "AJAX_OPTION_STYLE" => "Y",
            "AJAX_OPTION_HISTORY" => "N",
        )
    ); ?>


    </div>
    <? $APPLICATION->IncludeComponent(
        "bitrix:catalog.section",
        "wok_filling",
        Array(
            "TEMPLATE_THEME" => "",
            "PRODUCT_DISPLAY_MODE" => "N",
            //"ADD_PICT_PROP" => "MORE_PHOTO",
            //"LABEL_PROP" => "NEW_BOOK",
            //"OFFER_ADD_PICT_PROP" => "FILE",
            //"OFFER_TREE_PROPS" => array("-"),
            "PRODUCT_SUBSCRIPTION" => "N",
            "SHOW_DISCOUNT_PERCENT" => "N",
            "SHOW_OLD_PRICE" => "N",
            "SHOW_CLOSE_POPUP" => "N",
            "MESS_BTN_BUY" => "Купить",
            "MESS_BTN_ADD_TO_BASKET" => "В корзину",
            "MESS_BTN_SUBSCRIBE" => "Подписаться",
            "MESS_BTN_DETAIL" => "Подробнее",
            "MESS_NOT_AVAILABLE" => "Недоступно",
            "AJAX_MODE" => "Y",
            "IBLOCK_TYPE" => "menu",
            "IBLOCK_ID" => "2",
            "SECTION_ID" => $arVariables["SECTION_CODE"] == "wok-new" ? 48 : 37,
            "SECTION_CODE" => "",
            "SECTION_USER_FIELDS" => array(),
            "ELEMENT_SORT_FIELD" => "sort",
            "ELEMENT_SORT_ORDER" => "asc",
            "ELEMENT_SORT_FIELD2" => "name",
            "ELEMENT_SORT_ORDER2" => "asc",
            "FILTER_NAME" => "arrFilter",
            "FILTER_PRICE_CODE" => array(
                0 => "BASE",
            ),
            "INCLUDE_SUBSECTIONS" => "N",
            "SHOW_ALL_WO_SECTION" => "Y",
            "SECTION_URL" => "",
            "DETAIL_URL" => "",
            "BASKET_URL" => "/cart/",
            "ACTION_VARIABLE" => "action",
            "PRODUCT_ID_VARIABLE" => "id",
            "PRODUCT_QUANTITY_VARIABLE" => "quantity",
            "ADD_PROPERTIES_TO_BASKET" => "N",
            "PRODUCT_PROPS_VARIABLE" => "prop",
            "PARTIAL_PRODUCT_PROPERTIES" => "N",
            "SECTION_ID_VARIABLE" => "SECTION_ID",
            "ADD_SECTIONS_CHAIN" => "N",
            "DISPLAY_COMPARE" => "N",
            "SET_TITLE" => "N",
            "SET_BROWSER_TITLE" => "N",
            "BROWSER_TITLE" => "",
            "SET_META_KEYWORDS" => "N",
            "META_KEYWORDS" => "",
            "SET_META_DESCRIPTION" => "N",
            "META_DESCRIPTION" => "",
            "SET_STATUS_404" => "N",
            "PAGE_ELEMENT_COUNT" => "0",
            //"LINE_ELEMENT_COUNT" => "3",
            "PROPERTY_CODE" => array(
                0 => "NEWPRODUCT",
                1 => "SALELEADER",
                2 => "SPECIALOFFER",
                3 => "TYPE_REF",
                4 => "ARTNUMBER",
                5 => "PREVIEW_PICTURE"

            ),
            "OFFERS_SORT_FIELD" => "sort",
            "OFFERS_SORT_ORDER" => "asc",
            "OFFERS_SORT_FIELD2" => "active_from",
            "OFFERS_SORT_ORDER2" => "desc",
            "OFFERS_LIMIT" => "5",
            "OFFERS_LIMIT" => "0",
            "OFFERS_FIELD_CODE" => array(
                0 => "NAME",
                1 => "PREVIEW_PICTURE",
                2 => "DETAIL_PICTURE",
                3 => "",
            ),
            "OFFERS_PROPERTY_CODE" => array(
                0 => "SIZES_SHOES",
                1 => "SIZES_CLOTHES",
                2 => "COLOR_REF",
                3 => "MORE_PHOTO",
                4 => "ARTNUMBER",
                5 => "CITY",
            ),
            "PRICE_CODE" => array(
                0 => "BASE",
            ),
            "USE_PRICE_COUNT" => "N",
            "SHOW_PRICE_COUNT" => "1",
            "PRICE_VAT_INCLUDE" => "Y",
            "PRICE_VAT_SHOW_VALUE" => "N",
            "PRODUCT_PROPERTIES" => array(),
            "USE_PRODUCT_QUANTITY" => "Y",
            "CONVERT_CURRENCY" => "Y",
            "CURRENCY_ID" => "RUB",
            "QUANTITY_FLOAT" => "N",
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => "36000000",
            "CACHE_FILTER" => "Y",
            "CACHE_GROUPS" => "Y",
            "DISPLAY_TOP_PAGER" => "N",
            "DISPLAY_BOTTOM_PAGER" => "N",
            "PAGER_TITLE" => "Товары",
            "PAGER_SHOW_ALWAYS" => "Y",
            "PAGER_TEMPLATE" => "",
            "PAGER_DESC_NUMBERING" => "Y",
            "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
            "PAGER_SHOW_ALL" => "Y",
            "HIDE_NOT_AVAILABLE" => "Y",
            //"OFFERS_CART_PROPERTIES" => array(),
            "AJAX_OPTION_JUMP" => "N",
            "AJAX_OPTION_STYLE" => "Y",
            "AJAX_OPTION_HISTORY" => "N",
            //"ADD_TO_BASKET_ACTION" => "ADD",
        )
    ); ?>

    <?

    $res = CIBlockSection::GetList(array(), array(
        "IBLOCK_ID" => 2,
        "ID" => 21,
    ), false, array("ID", "IBLOCK_ID", "UF_SEO_TEXT", "UF_SEO_TEXT_NSK"));
    if ($row = $res->GetNext()) {
        $seo_text = html_entity_decode(convert_tpl(trim($row["UF_SEO_TEXT"])));
        $seo_text_nsk = html_entity_decode(convert_tpl(trim($row["UF_SEO_TEXT_NSK"])));
    }

    if ($seo_text || $seo_text_nsk) {
        echo "<div class='additional-text'>" . ((strpos($_SERVER[SERVER_NAME],
                    'nsk.') !== false) ? $seo_text_nsk : $seo_text) . "</div>";
    }

} else {
    ?>
    <?

    $arParams = array(
        "IBLOCK_TYPE" => "catalog",
        "IBLOCK_ID" => "2",
        "TEMPLATE_THEME" => "red",
        "HIDE_NOT_AVAILABLE" => "N",
        "BASKET_URL" => "/cart/",
        "ACTION_VARIABLE" => "action",
        "PRODUCT_ID_VARIABLE" => "id",
        "SECTION_ID_VARIABLE" => "SECTION_ID",
        "PRODUCT_QUANTITY_VARIABLE" => "quantity",
        "PRODUCT_PROPS_VARIABLE" => "prop",
        "SEF_MODE" => "Y",
        "SEF_FOLDER" => "/menu/",
        "AJAX_MODE" => "N",
        "AJAX_OPTION_JUMP" => "N",
        "AJAX_OPTION_STYLE" => "Y",
        "AJAX_OPTION_HISTORY" => "N",
        "CACHE_TYPE" => "A",
        "CACHE_TIME" => "36000000",
        "CACHE_FILTER" => "N",
        "CACHE_GROUPS" => "Y",
        "SET_TITLE" => "N",
        "ADD_SECTION_CHAIN" => "Y",
        "ADD_ELEMENT_CHAIN" => "Y",
        "SET_STATUS_404" => "Y",
        "DETAIL_DISPLAY_NAME" => "Y",
        "USE_ELEMENT_COUNTER" => "Y",
        "USE_FILTER" => "Y",
        "FILTER_NAME" => "arrFilter",
        "FILTER_VIEW_MODE" => $filterView,
        "ADD_PROPERTIES_TO_BASKET" => "N",
        "FILTER_FIELD_CODE" => array(
            0 => "",
            1 => "",
        ),
        "FILTER_PROPERTY_CODE" => array(
            0 => "",
            1 => "",
        ),
        "FILTER_PRICE_CODE" => array(
            0 => "BASE",
        ),
        "FILTER_OFFERS_FIELD_CODE" => array(
            0 => "PREVIEW_PICTURE",
            1 => "DETAIL_PICTURE",
            2 => "",
        ),
        "FILTER_OFFERS_PROPERTY_CODE" => array(
            0 => "CITY",
            1 => "",
        ),
        "USE_REVIEW" => "N",
        "MESSAGES_PER_PAGE" => "10",
        "USE_CAPTCHA" => "N",
        "REVIEW_AJAX_POST" => "Y",
        "PATH_TO_SMILE" => "/bitrix/images/forum/smile/",
        "FORUM_ID" => "11",
        "URL_TEMPLATES_READ" => "",
        "SHOW_LINK_TO_FORUM" => "Y",
        "USE_COMPARE" => "N",
        "PRICE_CODE" => array(
            0 => "BASE",
        ),
        "USE_PRICE_COUNT" => "N",
        "SHOW_PRICE_COUNT" => "1",
        "PRICE_VAT_INCLUDE" => "Y",
        "PRICE_VAT_SHOW_VALUE" => "N",
        "PRODUCT_PROPERTIES" => array(),
        "USE_PRODUCT_QUANTITY" => "Y",
        "CONVERT_CURRENCY" => "Y",
        "CURRENCY_ID" => "RUB",
        "QUANTITY_FLOAT" => "N",
        "OFFERS_CART_PROPERTIES" => array(
            0 => "CITY",
            1 => "SIZES_CLOTHES",
            2 => "COLOR_REF",
        ),
        "SHOW_TOP_ELEMENTS" => "N",
        "SECTION_COUNT_ELEMENTS" => "N",
        "SECTION_TOP_DEPTH" => "1",
        "SECTIONS_VIEW_MODE" => "TEXT",
        "SECTIONS_SHOW_PARENT_NAME" => "N",
        "PAGE_ELEMENT_COUNT" => "100",
        "LINE_ELEMENT_COUNT" => "3",
        "ELEMENT_SORT_FIELD" => "sort",
        "ELEMENT_SORT_ORDER" => "asc",
        "ELEMENT_SORT_FIELD2" => "id",
        "ELEMENT_SORT_ORDER2" => "desc",
        "LIST_PROPERTY_CODE" => array(
            0 => "NEWPRODUCT",
            1 => "SALELEADER",
            2 => "SPECIALOFFER",
            3 => "TYPE_REF",
            4 => "ARTNUMBER",
            5 => "PREVIEW_PICTURE",
            6 => "CITIES",
            7 => "CONST_PIC"
        ),
        "INCLUDE_SUBSECTIONS" => "N",
        "LIST_META_KEYWORDS" => "UF_KEYWORDS",
        "LIST_META_DESCRIPTION" => "UF_META_DESCRIPTION",
        "LIST_BROWSER_TITLE" => "SECTION_META_TITLE",
        "LIST_OFFERS_FIELD_CODE" => array(
            0 => "NAME",
            1 => "PREVIEW_PICTURE",
            2 => "DETAIL_PICTURE",
            3 => "",
        ),
        "LIST_OFFERS_PROPERTY_CODE" => array(
            0 => "SIZES_SHOES",
            1 => "SIZES_CLOTHES",
            2 => "COLOR_REF",
            3 => "MORE_PHOTO",
            4 => "ARTNUMBER",
            5 => "CITY",
            66 => "TYPE_REF"
        ),
        "LIST_OFFERS_LIMIT" => "0",
        "DETAIL_PROPERTY_CODE" => array(
            0 => "NEWPRODUCT",
            1 => "SALELEADER",
            2 => "SPECIALOFFER",
            22 => "SEED",
            23 => "SEED2",
            24 => "SEO_TEXT",
            3 => "TYPE_REF",
            4 => "ARTNUMBER",
            5 => "PREVIEW_PICTURE",
            6 => "ITEM_CONTENT",
            7 => "ITEM_CONTENT_QUANTITY",
            8 => "CITIES"

        ),
        "DETAIL_META_KEYWORDS" => "KEYWORDS",
        "DETAIL_META_DESCRIPTION" => "META_DESCRIPTION",
        "DETAIL_BROWSER_TITLE" => "SECTION_META_TITLE",
        "DETAIL_OFFERS_FIELD_CODE" => array(
            0 => "NAME",
            1 => "DETAIL_TEXT",
        ),
        "DETAIL_OFFERS_PROPERTY_CODE" => array(
            0 => "ARTNUMBER",
            1 => "SIZES_SHOES",
            2 => "SIZES_CLOTHES",
            3 => "COLOR_REF",
            3 => "WEIGHT",
            4 => "MORE_PHOTO",
            5 => "CITY",
            6 => "ITEM_CONTENT",
            66 => "TYPE_REF"
        ),
        "LINK_IBLOCK_TYPE" => "",
        "LINK_IBLOCK_ID" => "",
        "LINK_PROPERTY_SID" => "",
        "LINK_ELEMENTS_URL" => "link.php?PARENT_ELEMENT_ID=#ELEMENT_ID#",
        "USE_ALSO_BUY" => "Y",
        "ALSO_BUY_ELEMENT_COUNT" => "4",
        "ALSO_BUY_MIN_BUYES" => "1",
        "OFFERS_SORT_FIELD" => "sort",
        "OFFERS_SORT_ORDER" => "asc",
        "OFFERS_SORT_FIELD2" => "id",
        "OFFERS_SORT_ORDER2" => "desc",
        "PAGER_TEMPLATE" => "arrows",
        "DISPLAY_TOP_PAGER" => "N",
        "DISPLAY_BOTTOM_PAGER" => "N",
        "PAGER_TITLE" => "Товары",
        "PAGER_SHOW_ALWAYS" => "N",
        "PAGER_DESC_NUMBERING" => "N",
        "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000000",
        "PAGER_SHOW_ALL" => "N",
        "ADD_PICT_PROP" => "MORE_PHOTO",
        "LABEL_PROP" => "NEWPRODUCT",
        "PRODUCT_DISPLAY_MODE" => "Y",
        "OFFER_ADD_PICT_PROP" => "MORE_PHOTO",
        "OFFER_TREE_PROPS" => array(
            0 => "SIZES_SHOES",
            1 => "SIZES_CLOTHES",
            2 => "COLOR_REF",
            3 => "CITY",
        ),
        "SHOW_DISCOUNT_PERCENT" => "N",
        "SHOW_OLD_PRICE" => "N",
        "MESS_BTN_BUY" => "Купить",
        "MESS_BTN_ADD_TO_BASKET" => "В корзину",
        "MESS_BTN_COMPARE" => "Сравнение",
        "MESS_BTN_DETAIL" => "Подробнее",
        "MESS_NOT_AVAILABLE" => "Недоступно",
        "DETAIL_USE_VOTE_RATING" => "Y",
        "DETAIL_VOTE_DISPLAY_AS_RATING" => "rating",
        "DETAIL_USE_COMMENTS" => "N",
        "DETAIL_BLOG_USE" => "N",
        "DETAIL_VK_USE" => "N",
        "DETAIL_FB_USE" => "N",
        "AJAX_OPTION_ADDITIONAL" => "",
        "USE_STORE" => "Y",
        "USE_STORE_PHONE" => "Y",
        "USE_STORE_SCHEDULE" => "Y",
        "USE_MIN_AMOUNT" => "N",
        "STORE_PATH" => "/store/#store_id#",
        "MAIN_TITLE" => "Наличие на складах",
        "MIN_AMOUNT" => "10",
        "DETAIL_BRAND_USE" => "N",
        "DETAIL_BRAND_PROP_CODE" => "BRAND_REF",
        "SEF_URL_TEMPLATES" => array(
            "sections" => "",
            "section" => "#SECTION_CODE#/",
            "element" => "#SECTION_CODE#/#ELEMENT_CODE#/",
            "compare" => "compare/",
        )
    );

    if (
        (isset($_GET[my_sort]) && !empty($_GET[my_sort]))
        ||
        (isset($_COOKIE[my_sort]) && !empty($_COOKIE[my_sort]))
    ) {

        $sort = explode("_", $_GET[my_sort]);
        if (empty($_GET[my_sort])) {
            $sort = explode("_", $_COOKIE[my_sort]);
        }

        if ($sort[0] == "PRICE") {
            $arParams[ELEMENT_SORT_FIELD] = $arParams[ELEMENT_SORT_FIELD2] = "catalog_PRICE_1";
            $arParams[ELEMENT_SORT_ORDER] = $arParams[ELEMENT_SORT_ORDER2] = strtolower($sort[1]);
        }

        if ($sort[0] == "NAME") {
            $arParams[ELEMENT_SORT_FIELD] = $arParams[ELEMENT_SORT_FIELD2] = "name";
            $arParams[ELEMENT_SORT_ORDER] = $arParams[ELEMENT_SORT_ORDER2] = strtolower($sort[1]);
        }
    }


    $APPLICATION->IncludeComponent("bitrix:catalog", "menu", $arParams,
        false
    ); ?>
<? } ?>
</div>

<?
if (!$arVariables["ELEMENT_CODE"]) { ?>
    <div class="clear"></div>
    <?
}
if (!$arVariables["ELEMENT_CODE"]) {
    $tabs = array(
        array("id" => 'recommends', 'title' => 'Также рекомендуем'),
        array("id" => 'viewed', 'title' => 'Ранее просмотренные')
    );
} else {
    $tabs = array(
        array("id" => 'recommends', 'title' => 'Также рекомендуем'),
        array("id" => 'alsobuy', 'title' => 'С этим товаром обычно покупают'),
        array("id" => 'viewed', 'title' => 'Ранее просмотренные')
    );
}
include("tabs.php");
?>
<div>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>