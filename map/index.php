<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("title", "Карта сайта SUSHMAN &amp; PIZZMAN, #LCCITY#.");
$APPLICATION->SetPageProperty("keywords", "карта сайта");
$APPLICATION->SetPageProperty("description", "Карта сайта SUSHMAN &amp; PIZZMAN, #LCCITY#.");
$APPLICATION->SetTitle("Карта сайта");
?>
<?$APPLICATION->IncludeComponent("bitrix:main.map","",Array(
        "LEVEL" => "3", 
        "COL_NUM" => "1", 
        "SHOW_DESCRIPTION" => "N", 
        "SET_TITLE" => "N", 
        "CACHE_TYPE" => "A", 
        "CACHE_TIME" => "3600" 
    )
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>