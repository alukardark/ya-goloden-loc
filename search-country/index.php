<?
global $is_catalog_page;
$is_catalog_page = true;
$body_class = "inner nobg";
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Поиск по странам");

?>

    <div class="clear"></div>
    <div class="left-col">
        <div class="left-menu">
            <div class="title">Меню</div>
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
        <div id="sect-pic" class="no-image">
            <h2 class="sect sect-title">Поиск по странам</h2>
        </div>
        <div id="search-by">
            <?
            $_GET['sort_id'] = "UF_SORT";
            $_GET['sort_type'] = "ASC";
            $APPLICATION->IncludeComponent("bitrix:highloadblock.list", "search-country", Array(
                    "BLOCK_ID" => "8"
                )
            ); ?>
        </div>
        <?php

        use Bitrix\Highloadblock as HL;
        use Bitrix\Main\Entity;

        $hlblock_id = 8;
        $hlblock = HL\HighloadBlockTable::getById($hlblock_id)->fetch();
        $entity = HL\HighloadBlockTable::compileEntity($hlblock);
        $main_query = new Entity\Query($entity);
        $main_query->setSelect(array('ID', 'UF_XML_ID'));
        $result = $main_query->exec();
        $result = new CDBResult($result);
        $ingrs = array();
        while ($row = $result->Fetch()) {
            $ingrs[$row["ID"]] = $row["UF_XML_ID"];
        }

        $arFilter = array();
        foreach ($_POST['country'] as $ingr) {
            $ingr_id = (int)$ingr;
            if ($ingr_id > 0 && $ingrs[$ingr_id]) {
                $arFilter[] = array(
                    "ID" => CIBlockElement::SubQuery("ID",
                        array("IBLOCK_ID" => 2, "PROPERTY_TYPE_REF3" => $ingrs[$ingr_id]))
                );
            }
        }
        if (count($arFilter) == 0) {
            ?>
            <p>Выберите страны</p>
            <?
        } else {
            $arFilter[] = array_merge(array(
                "IBLOCK_ID" => 2
            ), $arFilter);
            $res = CIBlockElement::GetList(Array("SORT" => "ASC"), $arFilter, false, false, Array("ID"));
            while ($row = $res->GetNext()) {
                $ids[] = $row['ID'];
            }
            //$ids = array_slice($ids, 0, 2);
            if (count($ids) == 0) {
                ?>
                <p>Ничего не найдено</p>
                <?
            } else {
                global $arrFilter;
                $arrFilter = array(
                    "LOGIC" => "AND",
                    array("ID" => $ids),
                    array(
                        'ID' => CIBlockElement::SubQuery('PROPERTY_CML2_LINK', array(
                            'IBLOCK_ID' => 3,
                            'PROPERTY_CITY' => $cities[$city]["CODE"],
                            'ACTIVE' => 'Y'
                        ))
                    )
                );
                ?>
                <? $APPLICATION->IncludeComponent(
                    "bitrix:catalog.section",
                    "search",
                    Array(
                        "TEMPLATE_THEME" => "",
                        "PRODUCT_DISPLAY_MODE" => "Y",
                        "OFFER_ADD_PICT_PROP" => "MORE_PHOTO",
                        "OFFER_TREE_PROPS" => array(
                            0 => "SIZES_SHOES",
                            1 => "SIZES_CLOTHES",
                            2 => "COLOR_REF",
                            3 => "CITY",
                        ),
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
                        "MESS_NOT_AVAILABLE" => "Нет в наличии",
                        "AJAX_MODE" => "Y",
                        "IBLOCK_TYPE" => "menu",
                        "IBLOCK_ID" => "2",
                        "SECTION_ID" => "",
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
                        "INCLUDE_SUBSECTIONS" => "Y",
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
                        "LINE_ELEMENT_COUNT" => "3",
                        "PROPERTY_CODE" => array(
                            0 => "NEWPRODUCT",
                            1 => "SALELEADER",
                            2 => "SPECIALOFFER",
                            3 => "TYPE_REF",
                            4 => "ARTNUMBER",
                            5 => "PREVIEW_PICTURE"

                        ),
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
                        "OFFERS_SORT_FIELD" => "sort",
                        "OFFERS_SORT_ORDER" => "asc",
                        "OFFERS_SORT_FIELD2" => "active_from",
                        "OFFERS_SORT_ORDER2" => "desc",
                        "OFFERS_LIMIT" => "5",
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
                );
            }
        }
        ?>


    </div>
    <div class="clear"></div>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>