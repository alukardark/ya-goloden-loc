<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?><?
    SetGlobalVars();
?><?$APPLICATION->IncludeComponent(
    "bitrix:catalog.element",
    "menu",
    Array(
        "TEMPLATE_THEME" => "blue",
        "DISPLAY_NAME" => "Y",
        "DETAIL_PICTURE_MODE" => "IMG",
        "ADD_DETAIL_TO_SLIDER" => "Y",
        "ADD_PICT_PROP" => "-",
        "LABEL_PROP" => "-",
        "DISPLAY_PREVIEW_TEXT_MODE" => "E",
        "PRODUCT_SUBSCRIPTION" => "Y",
        "SHOW_DISCOUNT_PERCENT" => "N",
        "SHOW_OLD_PRICE" => "Y",
        "SHOW_MAX_QUANTITY" => "N",
        "SHOW_CLOSE_POPUP" => "Y",
        "DISPLAY_COMPARE" => "Y",
        "MESS_BTN_BUY" => "Купить",
        "MESS_BTN_ADD_TO_BASKET" => "В корзину",
        "MESS_BTN_SUBSCRIBE" => "Подписаться",
        "MESS_BTN_COMPARE" => "Сравнение",
        "MESS_NOT_AVAILABLE" => "Недоступно",
        "USE_VOTE_RATING" => "N",
        "USE_COMMENTS" => "N",
        "BRAND_USE" => "N",
	"IBLOCK_TYPE" => "catalog",
	"IBLOCK_ID" => "2",
	"TEMPLATE_THEME" => "red",
        "ELEMENT_ID" => $_REQUEST["ELEMENT_ID"],
        "ELEMENT_CODE" => "",
        "SECTION_ID" => $_REQUEST["SECTION_ID"],
        "SECTION_CODE" => "",
        "SECTION_URL" => "",
        "DETAIL_URL" => "",
        "BASKET_URL" => "/cart/",
        "ACTION_VARIABLE" => "action",
        "PRODUCT_ID_VARIABLE" => "id",
        "PRODUCT_QUANTITY_VARIABLE" => "quantity",
        "ADD_PROPERTIES_TO_BASKET" => "Y",
        "PRODUCT_PROPS_VARIABLE" => "prop",
        "SECTION_ID_VARIABLE" => "SECTION_ID",
        "CHECK_SECTION_ID_VARIABLE" => "N",
        "SET_TITLE" => "Y",
        "SET_BROWSER_TITLE" => "Y",
        "BROWSER_TITLE" => "-",
        "SET_META_KEYWORDS" => "Y",
        "META_KEYWORDS" => "-",
        "SET_META_DESCRIPTION" => "Y",
        "META_DESCRIPTION" => "-",
        "SET_STATUS_404" => "N",
        "ADD_SECTIONS_CHAIN" => "Y",
        "ADD_ELEMENT_CHAIN" => "N",
        "PROPERTY_CODE" => array(
		0 => "NEWPRODUCT",
		1 => "SALELEADER",
		2 => "SPECIALOFFER",
		3 => "TYPE_REF",
        22 => "SEED",
        23 => "SEED2",
                4 => "ARTNUMBER",
                5 => "CATALOG_MEASURE_RATIO",
                6 => "CATALOG_WEIGHT",
        ),
        "OFFERS_FIELD_CODE" => array(
        ),
        "OFFERS_PROPERTY_CODE" => array(
		0 => "CITY",
        ),
        "OFFERS_SORT_FIELD" => "sort",
        "OFFERS_SORT_ORDER" => "asc",
        "OFFERS_SORT_FIELD2" => "name",
        "OFFERS_SORT_ORDER2" => "desc",
        "OFFERS_LIMIT" => "0",
	"PRICE_CODE" => array(
		0 => "BASE",
	),
	"USE_PRICE_COUNT" => "N",
	"SHOW_PRICE_COUNT" => "1",
	"PRICE_VAT_INCLUDE" => "Y",
	"PRICE_VAT_SHOW_VALUE" => "N",
        "PRODUCT_PROPERTIES" => array(),
        "PARTIAL_PRODUCT_PROPERTIES" => "N",
        "USE_PRODUCT_QUANTITY" => "Y",
        "LINK_IBLOCK_TYPE" => "",
        "LINK_IBLOCK_ID" => "",
        "LINK_PROPERTY_SID" => "",
        "LINK_ELEMENTS_URL" => "link.php?PARENT_ELEMENT_ID=#ELEMENT_ID#",
        "CACHE_TYPE" => "A",
        "CACHE_TIME" => "36000000",
        "CACHE_NOTES" => "",
        "CACHE_GROUPS" => "Y",
        "USE_ELEMENT_COUNTER" => "Y",
        "HIDE_NOT_AVAILABLE" => "N",
        "OFFERS_CART_PROPERTIES" => array(),
	"OFFER_TREE_PROPS" => array(
		3 => "CITY",
	),
        "ADD_TO_BASKET_ACTION" => array(
            0 => "BUY",
            1 => "ADD",
        ),
        "SHOW_BASIS_PRICE" => "N",
        "CONVERT_CURRENCY" => "Y",
        "CURRENCY_ID" => "RUB",
        "QUANTITY_FLOAT" => "N",
        "USE_ALSO_BUY" => "N",
        "ALSO_BUY_MIN_BUYES" => 1
    )
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");?>