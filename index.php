<?
$body_class = 'index';
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetPageProperty("keywords", "суши, доставка, #LCCITY#, на дом, в офис");
$APPLICATION->SetPageProperty("title", "Доставка суши в #CITY2# на дом и в офисы - «SUSHMAN & PIZZMAN».");
$APPLICATION->SetPageProperty("description", "«Сушман и Пиццман» предлагает доставку суши в #CITY2#. Доступные цены на доставку суши, заказ можно сделать на сайте.");
global $city, $cities;
$APPLICATION->SetTitle("Сушман"); ?>
    <script type="text/javascript">(window.Image ? (new Image()) : document.createElement('img')).src = 'https://vk.com/rtrg?p=VK-RTRG-200359-8PQex';</script>
    <section>
        <div class="center-col">
            <div class="left-col">
                <? $APPLICATION->IncludeComponent("bitrix:main.include", "",
                    array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR . "include/promo.php"), false); ?>
                <? $APPLICATION->IncludeComponent("bitrix:main.include", "",
                    array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR . "include/recommends.php"), false); ?>
            </div>
            <div class="main-col">

                <? $arrFilter = Array(
                    "SECTION_CODE" => "main_slider_{$cities[$city]["CODE"]}"
                ); ?>
                <? $APPLICATION->IncludeComponent(
                    "bitrix:news.list",
                    "main_slider",
                    array(
                        "IBLOCK_TYPE" => "photos",
                        "IBLOCK_ID" => "sliders",
                        "SECTION_CODE" => "main_slider_{$cities[$city]["CODE"]}",
                        "SET_TITLE" => "N",
                        "NEWS_COUNT" => "",
                        "SORT_BY1" => "SORT",
                        "SORT_ORDER1" => "ASC",
                        "SORT_BY2" => "ACTIVE_FROM",
                        "SORT_ORDER2" => "ASC",
                        "FIELD_CODE" => array(
                            0 => "NAME",
                            1 => "PREVIEW_TEXT",
                            2 => "PREVIEW_PICTURE",
                            3 => "DETAIL_TEXT",
                            4 => "DETAIL_PICTURE",
                            5 => "PROPERTY_HREF",
                            6 => "PROPERTY_REAL_PICTURE",
                            7 => "PROPERTY_HREF_TARGET",
                            8 => "PROPERTY_ADD_BTN",
                            9 => "",
                        ),
                        "FILTER_NAME" => "arrFilter",
                        "PROPERTY_CODE" => array(
                            0 => "",
                            1 => "",
                        ),
                        "CHECK_DATES" => "Y",
                        "DETAIL_URL" => "",
                        "AJAX_MODE" => "N",
                        "AJAX_OPTION_JUMP" => "N",
                        "AJAX_OPTION_STYLE" => "Y",
                        "AJAX_OPTION_HISTORY" => "N",
                        "CACHE_TYPE" => "A",
                        "CACHE_TIME" => "36000000",
                        "CACHE_FILTER" => "N",
                        "CACHE_GROUPS" => "Y",
                        "PREVIEW_TRUNCATE_LEN" => "",
                        "ACTIVE_DATE_FORMAT" => "d.m.Y",
                        "SET_STATUS_404" => "N",
                        "INCLUDE_IBLOCK_INTO_CHAIN" => "Y",
                        "ADD_SECTIONS_CHAIN" => "Y",
                        "HIDE_LINK_WHEN_NO_DETAIL" => "N",
                        "PARENT_SECTION" => "",
                        "PARENT_SECTION_CODE" => "",
                        "INCLUDE_SUBSECTIONS" => "Y",
                        "PAGER_TEMPLATE" => ".default",
                        "DISPLAY_TOP_PAGER" => "N",
                        "DISPLAY_BOTTOM_PAGER" => "Y",
                        "PAGER_TITLE" => "Новости",
                        "PAGER_SHOW_ALWAYS" => "Y",
                        "PAGER_DESC_NUMBERING" => "N",
                        "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                        "PAGER_SHOW_ALL" => "Y",
                        "AJAX_OPTION_ADDITIONAL" => ""
                    ),
                    false
                ); ?>
                <? $APPLICATION->IncludeComponent("bitrix:menu", "main_cats", Array(
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
                <div class="clear"></div>

                <? $APPLICATION->IncludeComponent("bitrix:main.include", "",
                    array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR . "include/main-search-ingr.php"), false); ?>

                <?
                $APPLICATION->IncludeComponent("bitrix:main.include", "",
                    array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR . "include/main-search-country.php"), false);
                ?>

                <?
                $APPLICATION->IncludeFile("/include/seo_main_{$cities[$city]["CODE"]}.php", Array(), Array(
                    "MODE" => "text",
                    "NAME" => "Редактирование сео-текста на главной странице",
                ));
                ?>

            </div>
            <div class="clear"></div>
        </div>
    </section>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>