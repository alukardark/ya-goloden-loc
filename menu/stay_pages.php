<?
global $is_catalog_page;
$is_catalog_page = true;
$body_class = "inner nobg";

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");


$APPLICATION->SetPageProperty("keywords", "меню");
$APPLICATION->SetPageProperty("description",
    "Суши в #CITY2# от \"Сушман &amp; Пиццман\". Бесплатная доставка, вкусное меню для отличной компании!");

$filterView = "";
global $arrFilter, $city, $cities;

?>

<?
$cityCode = $cities[$city]["CODE"];
$arFilter = Array("IBLOCK_ID" => 9, "ACTIVE" => "Y", "CODE" => $_REQUEST['CODE']); //, 'PROPERTY_CITY' => $cityCode
$res = CIBlockElement::GetList(array(), $arFilter, false, Array(), array('NAME', 'PROPERTY_CITY', 'PROPERTY_SECTIONS'));
$sections = array();
$count = 0;
if ($res->SelectedRowsCount()) {
    while ($obj = $res->GetNext()) {
        $page = $obj;
        $sort = 999999;

        if ($obj['PROPERTY_CITY_VALUE'] == $cityCode) {
            $count++;
        } else {
            continue;
        }

        $rsResult = CIBlockSection::GetList(array("SORT" => "ASC"),
            array("IBLOCK_ID" => 2, 'ID' => $obj['PROPERTY_SECTIONS_VALUE']), false, array("UF_*"));
        while ($arResult = $rsResult->GetNext()) {
            $sort = is_null($arResult['UF_STAY_PAGES_SORT']) ? 999999 : $arResult['UF_STAY_PAGES_SORT'];
        }
        $sections[$obj['PROPERTY_SECTIONS_VALUE']] = [
            'ID' => $obj['PROPERTY_SECTIONS_VALUE'],
            'SORT' => $sort
        ];
    }
} else {
    CHTTP::SetStatus("404 Not Found");
    @define("ERROR_404", "Y");
}

if (!$count) {
    header("Location: /vse-yaponskoe-menu-" . $cityCode);
}

function my_cmp($a, $b)
{
    return strcmp($a["SORT"], $b["SORT"]);
}

usort($sections, "my_cmp");

//$APPLICATION->SetPageProperty("title", $filelds['NAME'] . " SUSHMAN &amp; PIZZMAN в #CITY2#.");
$APPLICATION->SetPageProperty("title", "Суши в #CITY2# - Сушман &amp; Пиццман");
//$APPLICATION->SetTitle($filelds['NAME']);

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
    <h1 class="pageTitle">Всё японское меню</h1><br/>
    <?
    foreach ($sections as $sec) {
        $arrFilter = array(

            'SECTION_ID' => $sec['ID'],
            'ID' => CIBlockElement::SubQuery('PROPERTY_CML2_LINK', array(
                'IBLOCK_ID' => 3,
                'PROPERTY_CITY_VALUE' => $page['PROPERTY_CITY_VALUE'],
                'ACTIVE' => 'Y'
            )),

        );

        $APPLICATION->IncludeComponent(
            "bitrix:catalog.section",
            "j_menu_dooble",
            array(
                "TEMPLATE_THEME" => "",
                "PRODUCT_DISPLAY_MODE" => "N",
                "PRODUCT_SUBSCRIPTION" => "N",
                "SHOW_DISCOUNT_PERCENT" => "N",
                "SHOW_OLD_PRICE" => "N",
                "SHOW_CLOSE_POPUP" => "N",
                "MESS_BTN_BUY" => "Купить",
                "MESS_BTN_ADD_TO_BASKET" => "В корзину",
                "MESS_BTN_SUBSCRIBE" => "Подписаться",
                "MESS_BTN_DETAIL" => "Подробнее",
                "MESS_NOT_AVAILABLE" => "Недоступно",
                "AJAX_MODE" => "N",
                "IBLOCK_TYPE" => "catalog",
                "IBLOCK_ID" => "2",
                "SECTION_ID" => "",
                "SECTION_CODE" => "",
                "SECTION_USER_FIELDS" => array(
                    0 => "",
                    1 => "",
                ),
                "ELEMENT_SORT_FIELD" => "IBLOCK_SECTION_ID",
                "ELEMENT_SORT_ORDER" => "asc",
                "ELEMENT_SORT_FIELD2" => "IBLOCK_SECTION_ID",
                "ELEMENT_SORT_ORDER2" => "asc",
                "FILTER_NAME" => "arrFilter",
                "INCLUDE_SUBSECTIONS" => "N",
                "SHOW_ALL_WO_SECTION" => "Y",
                "SECTION_URL" => "",
                "DETAIL_URL" => "",
                "BASKET_URL" => "/",
                "ACTION_VARIABLE" => "action",
                "PRODUCT_ID_VARIABLE" => "id",
                "PRODUCT_QUANTITY_VARIABLE" => "quantity",
                "ADD_PROPERTIES_TO_BASKET" => "Y",
                "PRODUCT_PROPS_VARIABLE" => "prop",
                "PARTIAL_PRODUCT_PROPERTIES" => "Y",
                "SECTION_ID_VARIABLE" => "SECTION_ID",
                "ADD_SECTIONS_CHAIN" => "N",
                "DISPLAY_COMPARE" => "N",
                "SET_TITLE" => "N",
                "SET_BROWSER_TITLE" => "",
                "BROWSER_TITLE" => "-",
                "SET_META_KEYWORDS" => "N",
                "META_KEYWORDS" => "",
                "SET_META_DESCRIPTION" => "N",
                "META_DESCRIPTION" => "",
                "SET_STATUS_404" => "N",
                "PAGE_ELEMENT_COUNT" => "1000",
                "LINE_ELEMENT_COUNT" => "3",
                "PROPERTY_CODE" => array(
                    0 => "ARTNUMBER",
                    1 => "TYPE_REF",
                    2 => "NEWPRODUCT",
                    3 => "SALELEADER",
                    4 => "SPECIALOFFER",
                    5 => "PREVIEW_PICTURE",
                    6 => "",
                ),
                "OFFERS_SORT_FIELD" => "",
                "OFFERS_SORT_ORDER" => "asc",
                "OFFERS_SORT_FIELD2" => "",
                "OFFERS_SORT_ORDER2" => "desc",
                "OFFERS_LIMIT" => "0",
                "OFFERS_FIELD_CODE" => array(
                    0 => "NAME",
                    1 => "PREVIEW_PICTURE",
                    2 => "DETAIL_PICTURE",
                    3 => "",
                ),
                "OFFERS_PROPERTY_CODE" => array(
                    0 => "CITY",
                    1 => "SIZES_SHOES",
                    2 => "SIZES_CLOTHES",
                    3 => "COLOR_REF",
                    4 => "MORE_PHOTO",
                    5 => "ARTNUMBER",
                    6 => "",
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
                "CACHE_TYPE" => "N",
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
                "AJAX_OPTION_JUMP" => "N",
                "AJAX_OPTION_STYLE" => "Y",
                "AJAX_OPTION_HISTORY" => "N",
                "ADD_PICT_PROP" => "-",
                "LABEL_PROP" => "-",
                "AJAX_OPTION_ADDITIONAL" => "",
                "OFFERS_CART_PROPERTIES" => array(
                    0 => "CITY",
                )
            ),
            false
        );
    }
    ?>
</div>
<div class="clear"></div>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
