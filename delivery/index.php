<?
$bg_class="delivery";
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("title", "Бесплатная доставка суши и пиццы в #CITY2# при заказе от 400 рублей.");
$APPLICATION->SetPageProperty("keywords", "бесплатная доставка, суши, пицца, #LCCITY#, заказать");
$APPLICATION->SetPageProperty("description", "Сервис доставки «SUSHMAN & PIZZMAN» предлагает бесплатную доставку суши и пиццы в #CITY2#.");
$APPLICATION->SetTitle("Доставка");?>
<?
    global $city,$cities;
    $APPLICATION->IncludeFile("/include/delivery_{$cities[$city]["CODE"]}.php", Array(), Array(
        "MODE"      => "html",                   
        "NAME"      => "Доставка",
    ));
?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>